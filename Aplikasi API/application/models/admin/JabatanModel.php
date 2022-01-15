<?php
defined('BASEPATH') or exit('No direct script access allowed');

class JabatanModel extends Render_Model
{
    public function getAllData($draw = null, $show = null, $start = null, $cari = null, $order = null, $filter = null)
    {
        $get_parrent_no_urut = "(select z.no_urut from pengurus_jabatan as z where a.parrent_id = z.id)";
        // select tabel
        $this->db->select("a.*,
        if(isnull(a.parrent_id),'',
            (select z.nama from pengurus_jabatan as z where a.parrent_id = z.id)
        ) as parrent,
        concat( if(isnull(a.parrent_id),'',
                concat($get_parrent_no_urut, '.')
            ), a.no_urut
        ) as kode,

        (if(isnull(a.parrent_id), a.no_urut,
            $get_parrent_no_urut)
        ) as parrent_no_urut,

        (
            if(isnull(a.parrent_id), 0, a.no_urut)
        )as child_no_urut,

        IF(a.status = '0' , 'Tidak Aktif', IF(a.status = '1' , 'Aktif', 'Tidak Diketahui')) as status_str
        ");
        $this->db->from("pengurus_jabatan a");
        $this->db->where('a.status <>', 3);

        // order by
        if ($order['order'] != null) {
            $columns = $order['columns'];
            $dir = $order['order'][0]['dir'];
            $order = $order['order'][0]['column'];
            $columns = $columns[$order];

            $order_colum = $columns['data'];
            $this->db->order_by($order_colum, $dir);
        }

        $this->db->order_by('parrent_no_urut');
        $this->db->order_by('child_no_urut');

        if ($filter) {
            if ($filter['periode'] != '') {
                $this->db->where('a.pengurus_periode_id', $filter['periode']);
            }
        }

        // initial data table
        if ($draw == 1) {
            $this->db->limit(10, 0);
        }

        // pencarian
        if ($cari != null) {
            $this->db->where("(
                (select z.nama from pengurus_jabatan as z where a.parrent_id = z.id) like '%$cari%' or
                a.slug like '%$cari%' or
                a.pengurus_periode_id like '%$cari%' or
                a.parrent_id like '%$cari%' or
                a.nama like '%$cari%' or
                a.keterangan like '%$cari%' or
                a.slogan like '%$cari%' or
                a.status like '%$cari%' or
                a.visi like '%$cari%' or
                a.misi like '%$cari%' or
                a.no_urut like '%$cari%' or
                IF(a.status = '0' , 'Tidak Aktif', IF(a.status = '1' , 'Aktif', 'Tidak Diketahui')) LIKE '%$cari%'
            )");
        }

        // pagination
        if ($show != null && $start != null) {
            $this->db->limit($show, $start);
        }

        $result = $this->db->get();
        return $result;
    }

    public function insert($user_id, $foto, $no_urut, $pengurus_periode_id, $parrent_id, $nama, $slug, $keterangan, $slogan, $visi, $misi, $status)
    {
        $data = [
            'pengurus_periode_id' => $pengurus_periode_id,
            'parrent_id' => $parrent_id,
            'no_urut' => $no_urut,
            'foto' => $foto,
            'nama' => $nama,
            'slug' => $slug,
            'keterangan' => $keterangan,
            'slogan' => $slogan,
            'visi' => $visi,
            'misi' => $misi,
            'status' => $status,
            'created_by' => $user_id,
        ];
        // Insert users
        $execute = $this->db->insert('pengurus_jabatan', $data);
        $execute = $this->db->insert_id();
        return $execute;
    }

    public function update($id, $user_id, $foto, $no_urut, $pengurus_periode_id, $parrent_id, $nama, $slug, $keterangan, $slogan, $visi, $misi, $status)
    {
        $data = [
            'pengurus_periode_id' => $pengurus_periode_id,
            'parrent_id' => $parrent_id,
            'no_urut' => $no_urut,
            'foto' => $foto,
            'nama' => $nama,
            'slug' => $slug,
            'keterangan' => $keterangan,
            'slogan' => $slogan,
            'visi' => $visi,
            'misi' => $misi,
            'status' => $status,
            'updated_by' => $user_id,
        ];
        // Update users
        $execute = $this->db->where('id', $id);
        $execute = $this->db->update('pengurus_jabatan', $data);
        return  $execute;
    }

    // hard delete
    public function delete($id)
    {
        $exe = $this->db->where('id', $id)->delete('pengurus_jabatan');
        return $exe;
    }

    public function getParrent($pengurus_periode_id = null)
    {
        $result = $this->db->select('id, nama as text')
            ->from('pengurus_jabatan')
            ->where('parrent_id', null);
        if ($pengurus_periode_id) {
            $result->where('pengurus_periode_id', $pengurus_periode_id);
        }
        $result->order_by('no_urut');
        return $result->get()->result_array();
    }

    public function getChild($parrent_id)
    {
        return is_null($parrent_id) ? null : $this->db->select('id, nama as text')->from('pengurus_jabatan')->where('parrent_id', $parrent_id)->where('status <>', 3)->get()->result_array();
    }

    public function getList()
    {
        return $this->db->select('id, nama as text')
            ->from('pengurus_jabatan')
            ->where('status', 1)
            ->get()->result_array();
    }

    // pengurus per jabatan
    // =================================================================================================================
    public function getOne($id)
    {
        return $this->db->select("a.*,
        concat(a.nama, if(a.parrent_id is null, '', concat(' -> ', b.nama)))
        as with_parrent")
            ->from('pengurus_jabatan a')
            ->join('pengurus_jabatan b', 'a.parrent_id = b.id', 'left')
            ->where('a.id', $id)
            ->where('a.status <>', 3)
            ->get()->row_array();
    }

    public function pengurus_insert($user_id, $pengurus_jabatan_id, $by)
    {
        $this->db->trans_start();
        $pengurus_periode_id = $this->get_pengurus_periode_by_jabatan($pengurus_jabatan_id);

        // Insert pengurus jabatan
        $execute = $this->db->insert('pengurus_jabatan_detail', [
            'user_id' => $user_id,
            'pengurus_jabatan_id' => $pengurus_jabatan_id,
            'created_by' => $by,
        ]);

        $execute = $this->db->insert_id();

        $execute1 = $this->db->insert('pengurus_periode_detail', [
            'user_id' => $user_id,
            'pengurus_periode_id' => $pengurus_periode_id,
            'created_by' => $by,
        ]);
        $this->db->trans_complete();
        return $execute;
    }

    public function pengurus_delete($id)
    {
        $this->db->trans_start();
        $pengurus_jabatan = $this->get_pengurus_jabatan_by_jabatan_detail($id);
        $pengurus_periode_id = $this->get_pengurus_periode_by_jabatan($pengurus_jabatan['id']);

        // Delete pengurus jabatan
        $execute = $this->db->where('id', $id)->delete('pengurus_jabatan_detail');
        $execute1 = $this->db
            ->where('user_id', $pengurus_jabatan['user_id'])
            ->where('pengurus_periode_id', $pengurus_periode_id)
            ->delete('pengurus_periode_detail');
        $this->db->trans_complete();
        return $execute && $execute1;
    }

    public function pengurus_cari($q)
    {
        return $this->db->select("user_id as id, concat(if(thn_angkatan is null, '-', thn_angkatan),' | ', user_nama) as text")
            ->from('users')->join('level', 'level.lev_id = users.level_id')
            ->where("( user_nama  like '%$q%' or
            nama_belakang  like '%$q%' or
            nama_depan  like '%$q%' or
            alamat_kabupaten  like '%$q%' or
            alamat_kecamatan  like '%$q%' or
            alamat_desa  like '%$q%' or
            npp  like '%$q%' or
            user_nik  like '%$q%' or
            user_tgl_lahir  like '%$q%' or
            thn_angkatan  like '%$q%' or
            user_jenis_kelamin  like '%$q%' or
            user_password  like '%$q%' or
            user_email  like '%$q%' or
            user_email_status  like '%$q%' or
            user_phone  like '%$q%' or
            user_foto  like '%$q%' or
            user_status  like '%$q%' or
            level_id  like '%$q%' )")
            ->where('users.level_id', $this->pengurus_level)
            ->get()->result_array();
    }

    public function pengurus_datatable($draw = null, $show = null, $start = null, $cari = null, $order = null, $filter)
    {
        // select tabel
        $this->db->select("b.thn_angkatan, b.user_nama, a.id");
        $this->db->from('pengurus_jabatan_detail a');
        $this->db->join('users b', 'a.user_id = b.user_id');
        $this->db->join('level c', 'c.lev_id = b.level_id');
        $this->db->where('b.level_id', $this->pengurus_level);

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

        if ($filter) {
            if ($filter['jabatan'] != '') {
                $this->db->where('a.pengurus_jabatan_id', $filter['jabatan']);
            }
        }

        // pencarian
        if ($cari != null) {
            $this->db->where("( user_nama  like '%$cari%' or
            b.nama_belakang  like '%$cari%' or
            b.nama_depan  like '%$cari%' or
            b.alamat_kabupaten  like '%$cari%' or
            b.alamat_kecamatan  like '%$cari%' or
            b.alamat_desa  like '%$cari%' or
            b.npp  like '%$cari%' or
            b.user_nik  like '%$cari%' or
            b.user_tgl_lahir  like '%$cari%' or
            b.thn_angkatan  like '%$cari%' or
            b.user_jenis_kelamin  like '%$cari%' or
            b.user_password  like '%$cari%' or
            b.user_email  like '%$cari%' or
            b.user_email_status  like '%$cari%' or
            b.user_phone  like '%$cari%' or
            b.user_foto  like '%$cari%' or
            b.user_status  like '%$cari%' or
            b.level_id  like '%$cari%' )");
        }

        // pagination
        if ($show != null && $start != null) {
            $this->db->limit($show, $start);
        }

        $result = $this->db->get();
        return $result;
    }

    private function get_pengurus_periode_by_jabatan($pengurus_jabatan_id)
    {
        $query = $this->db->select('pengurus_periode_id')
            ->from('pengurus_jabatan')
            ->where('id', $pengurus_jabatan_id)
            ->get()->row_array();
        $query = isset($query['pengurus_periode_id']) ? $query['pengurus_periode_id'] : '0';

        return $query;
    }

    private function get_pengurus_jabatan_by_jabatan_detail($id)
    {
        $query = $this->db->select('user_id, pengurus_jabatan_id as id')
            ->from('pengurus_jabatan_detail')
            ->where('id', $id)
            ->get()->row_array();
        $query = is_null($query) ? ['user_id' => 0, 'pengurus_jabatan_id' => 0] : $query;

        return $query;
    }
}
