<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Share_image_model extends MY_Model {

	public $table = 'share_images';

	public function find_or_create($summary, $location, $start_date, $end_date, $uid = null, $description = null)
	{
		$hash = substr(md5($summary . $start_date . $end_date), 0, 8);

		$row = $this->db->get_where($this->table, ['hash' => $hash])->row();
		if ($row)
		{
			$updates = [];
			if ($row->location !== $location) $updates['location'] = $location;
			if ($row->summary !== $summary) $updates['summary'] = $summary;
			if ($uid !== null && ($row->uid ?? null) !== $uid) $updates['uid'] = $uid;
			if ($description !== null && ($row->description ?? null) !== $description) $updates['description'] = $description;

			if ($updates)
			{
				$this->db->update($this->table, $updates, ['id' => $row->id]);
				foreach ($updates as $k => $v) $row->$k = $v;
			}
			return $row;
		}

		$this->db->insert($this->table, [
			'hash'        => $hash,
			'uid'         => $uid,
			'summary'     => $summary,
			'location'    => $location,
			'start_date'  => $start_date,
			'end_date'    => $end_date,
			'description' => $description,
		]);

		$this->prune();

		return $this->db->get_where($this->table, ['hash' => $hash])->row();
	}

	public function get_by_uid($uid)
	{
		return $this->db->get_where($this->table, ['uid' => $uid])->row();
	}

	public function get_by_hash($hash)
	{
		return $this->db->get_where($this->table, ['hash' => $hash])->row();
	}

	private function prune()
	{
		$count = $this->db->count_all_results($this->table);
		if ($count > 100)
		{
			$delete_count = $count - 100;
			$oldest = $this->db->order_by('created_at', 'ASC')
				->limit($delete_count)
				->get($this->table)
				->result();

			foreach ($oldest as $row)
			{
				$this->db->delete($this->table, ['id' => $row->id]);
			}
		}
	}
}
