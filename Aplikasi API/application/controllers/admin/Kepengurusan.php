<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kepengurusan extends Render_Controller
{
    public function index()
    {
        // Page Settings
        $this->title = 'Periode Kepengurusan';
        $this->navigation = ['Kepengurusan'];
        $this->plugins = ['datatables', 'summernote'];

        // Breadcrumb setting
        $this->breadcrumb_1 = 'Dashboard';
        $this->breadcrumb_1_url = base_url() . 'admin/dashboard';
        $this->breadcrumb_3 = 'Kepengurusan';
        $this->breadcrumb_3_url = '#';
        // content
        $this->content      = 'admin/kepengurusan/kepengurusan';
        // Send data to view
        $this->render();
    }

    public function ajax_data()
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

        $data = $this->model->getAllData($draw, $length, $start, $_cari, $order)->result_array();
        $count = $this->model->getAllData(null, null, null, $_cari, $order, null)->num_rows();

        $this->output_json(['recordsTotal' => $count, 'recordsFiltered' => $count, 'draw' => $draw, 'search' => $_cari, 'data' => $data]);
    }

    public function getList()
    {
        $result = $this->model->getList();
        $code = $result ? 200 : 500;
        $this->output_json($result, $code);
    }

    public function insert()
    {
        $this->db->trans_start();
        $foto = '';
        if ($_FILES['foto']['name'] != '') {
            $foto = $this->uploadImage('foto');
            $foto = $foto['data'];
        }

        $slug = $this->input->post("slug");
        $dari = $this->input->post("dari");
        $sampai = $this->input->post("sampai");
        $nama = $this->input->post("nama");
        $keterangan = $this->input->post("keterangan");
        $slogan = $this->input->post("slogan");
        $visi = $this->input->post("visi", false);
        $misi = $this->input->post("misi", false);
        $user_id = $this->id;
        $result = $this->model->insert($user_id, $foto, $dari, $sampai, $nama, $slug, $keterangan, $slogan, $visi, $misi);

        $this->db->trans_complete();
        $code = $result ? 200 : 500;
        $this->output_json(["data" => $result], $code);
    }

    public function edit()
    {
        $id = $this->input->get('id');
        $result = $this->model->getOne($id);
        $this->output_json($result);
    }

    public function update()
    {
        $id = $this->input->post("id");
        $temp_foto = $this->input->post("temp_foto");
        if ($_FILES['foto']['name'] != '') {
            $foto = $this->uploadImage('foto');
            $foto = $foto['data'];
            $this->deleteFile($temp_foto);
        } else {
            $foto = $temp_foto;
        }
        $slug = $this->input->post("slug");
        $dari = $this->input->post("dari");
        $sampai = $this->input->post("sampai");
        $nama = $this->input->post("nama");
        $keterangan = $this->input->post("keterangan");
        $slogan = $this->input->post("slogan");
        $visi = $this->input->post("visi", false);
        $misi = $this->input->post("misi", false);
        $user_id = $this->id;
        $result = $this->model->update($id, $user_id, $foto, $dari, $sampai, $nama, $slug, $keterangan, $slogan, $visi, $misi);
        $code = $result ? 200 : 500;
        $this->output_json(["data" => $result], $code);
    }

    public function delete()
    {
        $id = $this->input->post("id");
        $result = $this->model->delete($this->id, $id);
        $code = $result ? 200 : 500;
        $this->output_json(["data" => $result], $code);
    }

    public function cari()
    {
        $key = $this->input->post('q');
        // jika inputan ada
        if ($key) {
            $this->output_json([
                "results" => $this->model->cari($key)
            ]);
        } else {
            $this->output_json([
                "results" => []
            ]);
        }
    }

    public function activate()
    {
        $id = $this->input->post("id");
        $result = $this->model->activate($this->id, $id);
        $this->output_json(["data" => $result], 200);
    }
    public function pengurus_datatable()
    {
        $order = ['order' => $this->input->post('order'), 'columns' => $this->input->post('columns')];
        $start = $this->input->post('start');
        $draw = $this->input->post('draw');
        $draw = $draw == null ? 1 : $draw;
        $length = $this->input->post('length');
        $cari = $this->input->post('search');
        $filter = null;
        $pengurus_periode_id = $this->input->post('pengurus_periode_id');
        if ($pengurus_periode_id) {
            $filter = [
                'periode' => $pengurus_periode_id
            ];
        }
        if (isset($cari['value'])) {
            $_cari = $cari['value'];
        } else {
            $_cari = null;
        }

        $data = $this->model->pengurus_datatable($draw, $length, $start, $_cari, $order,  $filter)->result_array();
        $this->output_json(['details' => $data]);
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
        $this->photo_path = './files/front/kepengurusan/';
        $this->load->model("admin/KepengurusanModel", 'model');
        $this->default_template = 'templates/dashboard';
        $this->load->library('plugin');
        $this->load->helper('url');
    }
}
