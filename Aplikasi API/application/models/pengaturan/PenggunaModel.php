<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PenggunaModel extends Render_Model
{
	public function getAllData()
	{
		$exe = $this->db->select('*')
			->from('users a')
			->join('level c', 'c.lev_id = a.level_id', 'left')
			->where('a.user_status <>', 3)
			->get();

		return $exe->result_array();
	}


	public function getDataDetail($id)
	{
		return $this->db
			->select('*')
			->from('users a')
			->where('user_id', $id)
			->get()->row_array();
	}


	public function getDataLevel()
	{
		$exe 						= $this->db->get('level');

		return $exe->result_array();
	}


	public function insert($level, $nama, $telepon, $username, $password, $status)
	{
		$data['user_nama'] = $nama;
		$data['user_email'] = $username;
		$data['user_password'] = $this->b_password->bcrypt_hash($password);
		$data['user_phone'] = $telepon;
		$data['user_status'] = $status;
		$data['level_id'] = $level;

		// Insert users
		$execute  = $this->db->insert('users', $data);
		$execute  = $this->db->insert_id();

		$exe['id'] 					= $execute;
		$level = $this->db->select('lev_nama')->from('level')->where('lev_id', $level)->get()->row_array();
		$level = is_null($level) ? ['lev_nama' => ''] : $level;
		$exe['level'] = $level['lev_nama'];

		return $exe;
	}


	public function update($id, $level, $nama, $telepon, $username, $password, $status)
	{
		$data['user_nama'] 			= $nama;
		$data['user_email'] 		= $username;
		$data['user_phone'] 		= $telepon;
		$data['user_status'] 		= $status;
		$data['level_id'] = $level;
		$data['updated_at'] 		= Date("Y-m-d H:i:s", time());
		if ($password != '') {
			$data['user_password'] 		= $this->b_password->bcrypt_hash($password);
		}

		// Update users
		$execute = $this->db->where('user_id', $id)->update('users', $data);
		$level = $this->db->select('lev_nama')->from('level')->where('lev_id', $level)->get()->row_array();
		$level = is_null($level) ? ['lev_nama' => ''] : $level;
		$exe['level'] = $level['lev_nama'];
		$exe['id'] = $id;

		return $exe;
	}


	public function delete($id)
	{
		// Delete users
		$exe 						= $this->db->where('user_id', $id);
		$exe 						= $this->db->delete('users');
		return $exe;
	}
}

/* End of file PenggunaModel.php */
/* Location: ./application/models/pengaturan/PenggunaModel.php */