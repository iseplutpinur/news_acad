<?php
defined('BASEPATH') or exit('No direct script access allowed');

class DaftarArtikel extends Render_Controller
{
    public function index()
    {
        // Page Settings
        $this->title = 'Daftar Artikel';
        $this->navigation = ['Daftar Artikel'];
        $this->plugins = ['datatables'];

        // Breadcrumb setting
        $this->breadcrumb_1 = 'Dashboard';
        $this->breadcrumb_1_url = base_url() . 'admin/dashboard';
        $this->breadcrumb_3 = 'Artikel';
        $this->breadcrumb_3_url = '#';
        // content
        $this->content      = 'admin/artikel/daftar_artikel';

        // Send data to view
        $this->render();
    }

    public function buat($id = null)
    {
        // Page Settings
        $this->title = is_null($id) ? 'Tambah Artikel' : 'Ubah Artikel';
        $this->navigation = ['Daftar Artikel'];
        $this->plugins = ['datatables', 'select2'];
        // Breadcrumb setting
        $this->breadcrumb_1 = 'Dashboard';
        $this->breadcrumb_1_url = base_url() . 'admin/dashboard';
        $this->breadcrumb_2 = 'Artikel';
        $this->breadcrumb_2_url = base_url() . 'admin/artikel/DaftarArtikel/';
        $this->breadcrumb_3 = 'Tambah';
        $this->breadcrumb_3_url = base_url() . 'admin/artikel/DaftarArtikel/buat';
        $this->data['isUbah'] = $id != null;

        // content
        $this->content      = 'admin/artikel/buat';

        $ceknew = $this->model->cekNew($id);
        if ($ceknew == null) {
            redirect('/admin/artikel/buat');
            return;
        }

        $this->data['getID'] = $ceknew['id'];
        $this->data['artikel'] = $ceknew;
        $this->data['categories'] = $this->model->getListCategory($ceknew['id']);
        $this->data['tags'] = $this->model->getListTag($ceknew['id']);
        $this->data['isUbah'] = $id != null;
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

    public function simpan()
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

        $nama = $this->input->post("nama");
        $slug = $this->input->post("slug");
        $excerpt = $this->input->post("excerpt");
        $detail = $this->input->post("detail", 'false');
        $tag = $this->input->post("tag");
        $kategori = $this->input->post("kategori");
        $user_id = $this->id;
        $status = $this->input->post('publish') ? 2 : 1;
        $result = $this->model->update($id, $user_id, $nama, $slug, $foto, $detail, $excerpt, $kategori, $tag, $status);
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
        $this->photo_path = './files/artikel/daftar_artikel/';
        $this->load->model("admin/artikel/DaftarArtikelModel", 'model');
        $this->default_template = 'templates/dashboard';
        $this->load->library('plugin');
        $this->load->helper('url');
    }
}
