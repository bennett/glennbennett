<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_user_model extends MY_Model {

	public $table = 'admin_users';

	public function attempt($username, $password)
	{
		$user = $this->db->get_where($this->table, ['username' => $username])->row();

		if ( ! $user)
		{
			return false;
		}

		if ( ! password_verify($password, $user->password_hash))
		{
			return false;
		}

		return $user;
	}

	public function login($user)
	{
		$this->session->set_userdata([
			'admin_logged_in' => true,
			'admin_user' => [
				'id'       => $user->id,
				'username' => $user->username,
			]
		]);
	}

	public function change_password($id, $new_password)
	{
		return $this->db->update($this->table, [
			'password_hash' => password_hash($new_password, PASSWORD_BCRYPT)
		], ['id' => $id]);
	}

	public function logout()
	{
		$this->session->unset_userdata(['admin_logged_in', 'admin_user']);
		$this->session->sess_destroy();
	}
}
