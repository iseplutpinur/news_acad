<?php
defined('BASEPATH') or exit('No direct script access allowed');

class DefaultModel extends Render_Model
{

	public function menu()
	{
		$user = $this->session->userdata('data');

		if ($user) {
			$query = $this->db->select('*')
				->from('role_aplikasi a')
				->join('menu b', 'b.menu_id = a.rola_menu_id')
				->where('a.rola_lev_id', $user['level_id'])
				->where('b.menu_menu_id', 0)
				->order_by('b.menu_index', 'asc')
				->get()->result_array();
		} else {
			$query = [];
		}
		return $query;
	}

	public function sub_menu($menu_id = null)
	{
		if (is_null($this->session->userdata('data'))) {
			return null;
		}
		$session_level_id = $this->session->userdata('data')['level_id'];

		$query = $this->db->select('*')
			->from('role_aplikasi a')
			->join('menu b', 'b.menu_id = a.rola_menu_id')
			->where([
				'b.menu_menu_id' 	=> $menu_id,
				'b.menu_status' 	=> 'Aktif',
				'a.rola_lev_id' 	=> $session_level_id
			])->get()->result_array();

		return $query;
	}
}
