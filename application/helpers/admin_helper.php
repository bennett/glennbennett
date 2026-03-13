<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('is_admin_logged'))
{
	function is_admin_logged()
	{
		$CI =& get_instance();
		return ! empty($CI->session->userdata('admin_logged_in'));
	}
}

if ( ! function_exists('admin_logged'))
{
	function admin_logged($key = false)
	{
		$CI =& get_instance();

		if ( ! is_admin_logged())
		{
			return false;
		}

		$user = $CI->session->userdata('admin_user');

		if ( ! $user)
		{
			return false;
		}

		return $key ? (isset($user[$key]) ? $user[$key] : null) : (object) $user;
	}
}
