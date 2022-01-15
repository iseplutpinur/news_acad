<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MediaSosial extends Render_Controller
{
  public function index()
  {
    // Page Settings
    $this->title           = 'Media Sosial';
    $this->content           = 'admin/pengaturan/medsos';
    $this->navigation         = ['Media Sosial'];
    $this->plugins           = ['datatables'];

    // Breadcrumb setting
    $this->breadcrumb_1       = 'Dashboard';
    $this->breadcrumb_1_url     = base_url() . 'dashboard';
    $this->breadcrumb_2       = 'Pengaturan';
    $this->breadcrumb_2_url     = '#';
    $this->breadcrumb_3       = 'Media Sosial';
    $this->breadcrumb_3_url     = '#';

    $this->render();
  }

  public function ajax()
  {
    $order = ['order' => $this->input->post('order'), 'columns' => $this->input->post('columns')];
    $start = $this->input->post('start');
    $draw = $this->input->post('draw');
    $draw = $draw == null ? 1 : $draw;
    $length = $this->input->post('length');
    $cari = $this->input->post('search');

    if (isset($cari['value'])) {
      $_cari = $cari['value'];
    } else {
      $_cari = null;
    }
    $data = $this->model->ajax($draw, $length, $start, $_cari, $order)->result_array();
    $count = $this->model->ajax(null, null, null, $_cari, $order)->num_rows();

    $this->output_json(['recordsTotal' => $count, 'recordsFiltered' => $count, 'draw' => $draw, 'search' => $_cari, 'data' => $data]);
  }

  public function insert()
  {
    $this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));
    $this->cache->delete($this->cache_sosmed);
    $this->db->trans_start();
    $name = $this->input->post("name");
    $link = $this->input->post("link");
    $icon = $this->input->post("icon");
    $status = $this->input->post("status");
    $user_id = $this->id;
    $result = $this->model->insert($user_id, $name, $link, $icon, $status);

    $this->db->trans_complete();
    $code = $result ? 200 : 500;
    $this->output_json(["data" => $result], $code);
  }

  public function update()
  {
    $this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));
    $this->cache->delete($this->cache_sosmed);
    $this->db->trans_start();
    $id = $this->input->post("id");
    $name = $this->input->post("name");
    $link = $this->input->post("link");
    $icon = $this->input->post("icon");
    $status = $this->input->post("status");
    $user_id = $this->id;
    $result = $this->model->update($id, $user_id, $name, $link, $icon, $status);

    $this->db->trans_complete();
    $code = $result ? 200 : 500;
    $this->output_json(["data" => $result], $code);
  }

  public function delete()
  {
    $this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));
    $this->cache->delete($this->cache_sosmed);
    $this->db->trans_start();
    $id = $this->input->post("id");
    $result = $this->model->delete($id);

    $this->db->trans_complete();
    $code = $result ? 200 : 500;
    $this->output_json(["data" => $result], $code);
  }


  function __construct()
  {
    parent::__construct();
    $this->sesion->cek_session();
    $akses = ['Super Admin'];
    $get_lv = $this->session->userdata('data')['level'];
    if (!in_array($get_lv, $akses)) {
      redirect('my404', 'refresh');
    }
    $this->id = $this->session->userdata('data')['id'];
    $this->photo_path = './files/logo/';
    $this->load->model("admin/pengaturan/MediaSosialModel", 'model');
    $this->default_template = 'templates/dashboard';
    $this->load->library('plugin');
    $this->load->helper('url');
  }
}
