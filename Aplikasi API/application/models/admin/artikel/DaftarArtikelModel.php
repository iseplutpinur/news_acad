<?php
defined('BASEPATH') or exit('No direct script access allowed');

class DaftarArtikelModel extends Render_Model
{
    public function getAllData($draw = null, $show = null, $start = null, $cari = null, $order = null)
    {
        // select tabel
        $this->db->select("a.*,
        IF(a.status = '0' , 'Dibuat',
            IF(a.status = '1' , 'Disimpan',
                IF(a.status = '2' , 'Dipublish', 'Tidak Diketahui')
            )
        ) as status_str");
        $this->db->from("artikel a");
        $this->db->where('a.status <>', 0);

        // order by
        if ($order['order'] != null) {
            $columns = $order['columns'];
            $dir = $order['order'][0]['dir'];
            $order = $order['order'][0]['column'];
            $columns = $columns[$order];

            $order_colum = $columns['data'];
            $this->db->order_by($order_colum, $dir);
        }

        // initial data table
        if ($draw == 1) {
            $this->db->limit(10, 0);
        }

        // pencarian
        if ($cari != null) {
            $this->db->where("(
                a.nama LIKE '%$cari%' or
                a.slug LIKE '%$cari%' or
                a.detail LIKE '%$cari%' or
                IF(a.status = '0' , 'Dibuat',
                    IF(a.status = '1' , 'Disimpan',
                        IF(a.status = '2' , 'Dipublish', 'Tidak Diketahui')
                    )
                ) LIKE '%$cari%'
            )");
        }

        // pagination
        if ($show != null && $start != null) {
            $this->db->limit($show, $start);
        }

        $result = $this->db->get();
        return $result;
    }

    // halaman buat ====================================================================================================
    public function cekNew($id = null)
    {
        if ($id == null) {
            $cek = $this->db->get_where("artikel", ["status" => 0]);
            $this->db->trans_start();
            if ($cek->num_rows() == 0) {
                $this->db->insert("artikel", [
                    "status" => 0,
                    "created_by" => $this->session->userdata('data')['id']
                ]);
                $getID = $this->db->insert_id();
            } else {
                $getID = $cek->row_array()['id'];
            }
            $return = $this->db->select('*')->from('artikel')->where('id', $getID)->get()->row_array();
            $this->db->trans_complete();

            return $return;
        } else {
            $id = $this->db->select('*')->from('artikel')->where('id', $id)->get()->row_array();
            return $id;
        }
    }

    // get list color
    public function getListCategory($artikel_id)
    {
        $result = $this->db->select("a.id, a.nama as text, (
            select count(*) from artikel_kategori_detail as b where a.id = b.artikel_kategori_id and b.artikel_id = '$artikel_id'
        ) as selected")
            ->from('artikel_kategori a')
            ->get()->result_array();
        return $result;
    }

    public function getListTag($artikel_id)
    {
        $result = $this->db->select("a.id, a.nama as text,  (
            select count(*) from artikel_tag_detail as b where a.id = b.artikel_tag_id and b.artikel_id = '$artikel_id'
        ) as selected")
            ->from('artikel_tag a')

            ->get()->result_array();
        return $result;
    }


    public function update($id, $user_id, $nama, $slug, $foto, $detail, $excerpt, $kategori, $tag, $status)
    {
        $this->db->trans_start();
        $data = [
            'nama' => $nama,
            'slug' => $slug,
            'detail' => $detail,
            'status' => $status,
            'foto' => $foto,
            'excerpt' => $excerpt,
            'updated_by' => $user_id,
        ];
        // Update users
        $execute = $this->db->where('id', $id);
        $execute = $this->db->update('artikel', $data);

        // update tag
        $this->updateTag($user_id, $id, $tag);

        // update kategori
        $this->updateKategori($user_id, $id, $kategori);

        // commit
        $this->db->trans_complete();
        return  $execute;
    }

    private function updateTag($user_id, $artikel_id, $tags)
    {
        $delete = $this->db->where('artikel_id', $artikel_id)->delete('artikel_tag_detail');
        $insert = true;

        if (is_array($tags)) {
            $res = true;
            foreach ($tags as $tags) {
                $res_inset = $this->db->insert('artikel_tag_detail', [
                    'artikel_id' => $artikel_id,
                    'artikel_tag_id' => $tags,
                    'created_by' => $user_id,
                ]);
                if (!$res) {
                    $res = $res_inset;
                }
            }
            $insert = $res;
        }
        return $delete && $insert;
    }

    private function updateKategori($user_id, $artikel_id, $kategoris)
    {
        $delete = $this->db->where('artikel_id', $artikel_id)->delete('artikel_kategori_detail');
        $insert = true;

        if (is_array($kategoris)) {
            $res = true;
            foreach ($kategoris as $kategori) {
                $res_inset = $this->db->insert('artikel_kategori_detail', [
                    'artikel_id' => $artikel_id,
                    'artikel_kategori_id' => $kategori,
                    'created_by' => $user_id,
                ]);
                if (!$res) {
                    $res = $res_inset;
                }
            }
            $insert = $res;
        }
        return $delete && $insert;
    }

    public function delete($id)
    {
        // Delete users
        $exe = $this->db->where('id', $id)->delete('artikel');
        return $exe;
    }









    public function getList()
    {
        return $this->db->select('id, nama as text')
            ->from('artikel_kategori')
            ->where('status', 1)
            ->get()->result_array();
    }
}
