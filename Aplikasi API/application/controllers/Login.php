<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login extends Render_Controller
{

	public function index()
	{
		$this->sesion->cek_login();
		$this->content = 'login_token';
		$this->render();
	}

	// login token KPU
	public function login_token()
	{
		$token = $this->input->post('token');
		$getPemilih = $this->db->select('*')->from('kpu_pemilih')->where('token', $token)->get()->row();
		if ($getPemilih == null) {
			$this->output_json(['status' => 0]);
			return;
		}

		$this->db->where('id', $getPemilih->id)->update('kpu_pemilih', ['last_login' => date("Y-m-d H:i:s")]);
		$session = array(
			'status' => true,
			'data'	 => array(
				'id' => $getPemilih->id,
				'nama' => $getPemilih->nama,
				'email' => '',
				'level' => 'Pemilih',
				'level_id' => '127',
			)
		);

		$this->session->set_userdata($session);

		$this->output_json(['status' => 1]);
	}

	// login admin
	public function doLogin()
	{
		$username 	= $this->input->post('email');
		$password 	= $this->input->post('password');

		// Cek login ke model
		$login 		= $this->login->cekLogin($username, $password);

		// berhasil
		if ($login->status == 0) {
			switch ($login->data->user_status) {
				case 0: // akun di nonaktifkan
					$this->output_json(['status' => 3]);
					return;
					break;

				case 1: // akun aktif
					$session = array(
						'status' => true,
						'data'	 => [
							'id' => $login->data->user_id,
							'nama' => $login->data->user_nama,
							'email' => $login->data->user_email,
							'level' => $login->data->lev_nama,
							'level_id' => $login->data->lev_id,
						]
					);
					$this->session->set_userdata($session);
					$this->output_json(['status' => 0]);
					return;
					break;

				default:
					$this->output_json(['status' => 1]);
					return;
			}
		}
		$this->output_json(['status' => $login->status]);
		return;
	}

	public function logout()
	{
		$session = array('status', 'data');

		$this->session->unset_userdata($session);

		redirect('admin/login', 'refresh');
	}

	function __construct()
	{
		parent::__construct();
		$this->load->model('loginModel', 'login');
		$this->default_template = 'templates/login_token';
		$this->load->library('plugin');
		$this->load->helper('url');
	}
}

/* End of file Login.php */
/* Location: ./application/controllers/Login.php */