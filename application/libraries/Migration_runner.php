<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_runner {

    private $ci;
    private $migration_path;
    private $table = 'migrations';

    public function __construct()
    {
        $this->ci = get_instance();
        $this->migration_path = FCPATH . 'database/migrations/';
        $this->ensure_table();
    }

    /**
     * Create the migrations tracking table if it doesn't exist.
     */
    private function ensure_table()
    {
        $this->ci->db->query("
            CREATE TABLE IF NOT EXISTS `{$this->table}` (
                `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `migration` VARCHAR(255) NOT NULL,
                `batch` INT UNSIGNED NOT NULL,
                `ran_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8
        ");
    }

    /**
     * Get all migration files sorted by filename.
     *
     * @return array Filenames (without path)
     */
    private function get_files()
    {
        if (!is_dir($this->migration_path)) {
            return array();
        }

        $files = glob($this->migration_path . '*.php');
        $names = array();

        foreach ($files as $file) {
            $names[] = basename($file);
        }

        sort($names);
        return $names;
    }

    /**
     * Get list of already-run migration names.
     *
     * @return array
     */
    private function get_ran()
    {
        $rows = $this->ci->db->select('migration')
            ->get($this->table)
            ->result();

        $ran = array();
        foreach ($rows as $row) {
            $ran[] = $row->migration;
        }

        return $ran;
    }

    /**
     * Get the next batch number.
     *
     * @return int
     */
    private function next_batch()
    {
        $row = $this->ci->db->select_max('batch')
            ->get($this->table)
            ->row();

        return ($row && $row->batch) ? (int) $row->batch + 1 : 1;
    }

    /**
     * Run all pending migrations.
     *
     * @return array Names of migrations that were run
     */
    public function migrate()
    {
        $files = $this->get_files();
        $ran = $this->get_ran();
        $pending = array_diff($files, $ran);

        if (empty($pending)) {
            return array();
        }

        $batch = $this->next_batch();
        $completed = array();

        foreach ($pending as $file) {
            $migration = require $this->migration_path . $file;

            if (!isset($migration['up']) || !is_callable($migration['up'])) {
                continue;
            }

            call_user_func($migration['up'], $this->ci);

            $this->ci->db->insert($this->table, array(
                'migration' => $file,
                'batch' => $batch,
            ));

            $completed[] = $file;
        }

        return $completed;
    }

    /**
     * Roll back the last batch of migrations.
     *
     * @return array Names of migrations that were rolled back
     */
    public function rollback()
    {
        $row = $this->ci->db->select_max('batch')
            ->get($this->table)
            ->row();

        if (!$row || !$row->batch) {
            return array();
        }

        $batch = (int) $row->batch;

        $rows = $this->ci->db->where('batch', $batch)
            ->order_by('id', 'DESC')
            ->get($this->table)
            ->result();

        $rolled_back = array();

        foreach ($rows as $row) {
            $path = $this->migration_path . $row->migration;

            if (file_exists($path)) {
                $migration = require $path;

                if (isset($migration['down']) && is_callable($migration['down'])) {
                    call_user_func($migration['down'], $this->ci);
                }
            }

            $this->ci->db->where('id', $row->id)->delete($this->table);
            $rolled_back[] = $row->migration;
        }

        return $rolled_back;
    }

    /**
     * Get status of all migrations.
     *
     * @return array Each element: ['file' => name, 'ran' => bool, 'batch' => int|null, 'ran_at' => string|null]
     */
    public function status()
    {
        $files = $this->get_files();

        $rows = $this->ci->db->get($this->table)->result();
        $ran_map = array();
        foreach ($rows as $row) {
            $ran_map[$row->migration] = $row;
        }

        $status = array();
        foreach ($files as $file) {
            $entry = array(
                'file' => $file,
                'ran' => isset($ran_map[$file]),
                'batch' => isset($ran_map[$file]) ? $ran_map[$file]->batch : null,
                'ran_at' => isset($ran_map[$file]) ? $ran_map[$file]->ran_at : null,
            );
            $status[] = $entry;
        }

        return $status;
    }
}
