<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Jabatan extends Render_Controller
{

    public function index($id = null)
    {
        // Page Settings
        $this->title = 'Periode Jabatan';
        $this->navigation = ['Kepengurusan'];
        $this->plugins = ['datatables', 'summernote'];

        // Breadcrumb setting
        $this->breadcrumb_1 = 'Dashboard';
        $this->breadcrumb_1_url = base_url() . 'admin/dashboard';
        $this->breadcrumb_3 = 'Kepengurusan';
        $this->breadcrumb_3_url = base_url() . 'admin/kepengurusan';
        $this->breadcrumb_4 = 'Jabatan';
        $this->breadcrumb_4_url = '#';
        // get data
        $this->data['kepengurusan'] = $this->kepengurusan->getOne($id);
        if (is_null($this->data['kepengurusan'])) {
            $this->render404();
            return;
        }
        // content
        $this->content      = 'admin/kepengurusan/jabatan';
        // Send data to view
        $this->render();
    }

    public function get_parrent()
    {
        $pengurus_periode_id = $this->input->get('pengurus_periode_id');
        $result = $this->model->getParrent($pengurus_periode_id);
        $this->output_json($result, 200);
    }

    public function ajax_data()
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

        $data = $this->model->getAllData($draw, $length, $start, $_cari, $order,  $filter)->result_array();
        $count = $this->model->getAllData(null, null,    null,   $_cari, $order,  $filter)->num_rows();

        $this->output_json(['recordsTotal' => $count, 'recordsFiltered' => $count, 'draw' => $draw, 'search' => $_cari, 'data' => $data]);
    }

    public function getList()
    {
        $result = $this->model->getList();
        $this->output_json($result, 200);
    }

    public function insert()
    {
        $this->db->trans_start();
        $foto = '';
        $nama = $this->input->post("nama");
        if ($_FILES['foto']['name'] != '') {
            $foto = $this->uploadImage('foto', $nama);
            $foto = $foto['data'];
        }

        $slug = $this->input->post("slug");
        $pengurus_periode_id = $this->input->post("pengurus_periode_id");
        $parrent_id = $this->input->post("parrent_id");
        $parrent_id = $parrent_id == '' ? null : $parrent_id;
        $keterangan = $this->input->post("keterangan");
        $slogan = $this->input->post("slogan");
        $no_urut = $this->input->post("no_urut");
        $status = $this->input->post("status");
        $visi = $this->input->post("visi", false);
        $misi = $this->input->post("misi", false);
        $user_id = $this->id;
        $result = $this->model->insert($user_id, $foto, $no_urut, $pengurus_periode_id, $parrent_id, $nama, $slug, $keterangan, $slogan, $visi, $misi, $status);

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

    public function get_child()
    {
        $parrent_id = $this->input->get('parrent_id');
        $result = $this->model->getChild($parrent_id);
        $this->output_json($result);
    }

    public function update()
    {
        $id = $this->input->post("id");
        $nama = $this->input->post("nama");
        $temp_foto = $this->input->post("temp_foto");
        if ($_FILES['foto']['name'] != '') {
            $foto = $this->uploadImage('foto', $nama);
            $foto = $foto['data'];
            $this->deleteFile($temp_foto);
        } else {
            $foto = $temp_foto;
        }
        $slug = $this->input->post("slug");
        $pengurus_periode_id = $this->input->post("pengurus_periode_id");
        $parrent_id = $this->input->post("parrent_id");
        $parrent_id = $parrent_id == '' ? null : $parrent_id;
        $keterangan = $this->input->post("keterangan");
        $slogan = $this->input->post("slogan");
        $no_urut = $this->input->post("no_urut");
        $status = $this->input->post("status");
        $visi = $this->input->post("visi", false);
        $misi = $this->input->post("misi", false);
        $user_id = $this->id;
        $result = $this->model->update($id, $user_id, $foto, $no_urut, $pengurus_periode_id, $parrent_id, $nama, $slug, $keterangan, $slogan, $visi, $misi, $status);
        $code = $result ? 200 : 500;
        $this->output_json(["data" => $result], $code);
    }

    public function delete()
    {
        $id = $this->input->post("id");
        $result = $this->model->delete($id);
        $code = $result ? 200 : 500;
        $this->output_json(["data" => $result], $code);
    }

    // pengurus per jabatan
    // =================================================================================================================
    public function pengurus($jabatan_id = null)
    {
        // Page Settings
        $this->title = 'Pengurus Jabatan';
        $this->navigation = ['Kepengurusan'];
        $this->plugins = ['datatables', 'select2'];

        // Breadcrumb setting
        $this->breadcrumb_1 = 'Dashboard';
        $this->breadcrumb_1_url = base_url() . 'admin/dashboard';
        $this->breadcrumb_3 = 'Kepengurusan';
        $this->breadcrumb_3_url = base_url() . 'admin/kepengurusan';
        $this->breadcrumb_4 = 'Pengurus Jabatan';
        $this->breadcrumb_4_url = '#';
        // get data
        $this->data['jabatan'] = $this->model->getOne($jabatan_id);
        if (is_null($this->data['jabatan'])) {
            $this->render404();
            return;
        }

        // content
        $this->content      = 'admin/kepengurusan/pengurus_jabatan';
        // Send data to view
        $this->render();
    }

    public function pengurus_insert()
    {
        $user_id = $this->input->post('user_id');
        $pengurus_jabatan_id = $this->input->post('pengurus_jabatan_id');
        $by = $this->id;
        $result =  $this->model->pengurus_insert($user_id, $pengurus_jabatan_id, $by);
        $this->output_json(["results" => $result]);
    }

    public function pengurus_delete()
    {
        $id = $this->input->post('id');
        $result =  $this->model->pengurus_delete($id);
        $this->output_json(["results" => $result]);
    }

    public function pengurus_cari()
    {
        $key = $this->input->post('q');
        // jika inputan ada
        if ($key) {
            $this->output_json([
                "results" => $this->model->pengurus_cari($key)
            ]);
        } else {
            $this->output_json([
                "results" => []
            ]);
        }
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
        $pengurus_jabatan_id = $this->input->post('pengurus_jabatan_id');
        if ($pengurus_jabatan_id) {
            $filter = [
                'jabatan' => $pengurus_jabatan_id
            ];
        }
        if (isset($cari['value'])) {
            $_cari = $cari['value'];
        } else {
            $_cari = null;
        }

        $data = $this->model->pengurus_datatable($draw, $length, $start, $_cari, $order,  $filter)->result_array();
        $count = $this->model->pengurus_datatable(null, null,    null,   $_cari, $order,  $filter)->num_rows();

        $this->output_json(['recordsTotal' => $count, 'recordsFiltered' => $count, 'draw' => $draw, 'search' => $_cari, 'data' => $data]);
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
        $this->photo_path = './files/front/jabatan/';
        $this->load->model("admin/JabatanModel", 'model');
        $this->load->model("admin/KepengurusanModel", 'kepengurusan');
        $this->default_template = 'templates/dashboard';
        $this->load->library('plugin');
        $this->load->helper('url');
    }
}
