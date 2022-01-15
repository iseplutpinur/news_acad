<?php
defined('BASEPATH') or exit('No direct script access allowed');

class NewsModel extends Render_Model
{

  public function get_berita($category, $q)
  {
    $query = $this->db->select('a.*, d.user_nama')
      ->from('artikel a')
      ->join('artikel_kategori_detail b', 'a.id = b.artikel_id')
      ->join('users d', 'a.created_by = d.user_id')
      ->where('a.status', 2);
    if (!in_array($category, ['', null])) {
      $query->join('artikel_kategori c', 'b.artikel_kategori_id = c.id')
        ->where('c.nama', $category);
    }

    if (!in_array($q, ['', null])) {
      $search = "
          a.nama like '%$q%'
          or a.slug like '%$q%'
          or a.excerpt like '%$q%'
          or a.foto like '%$q%'
          or a.detail like '%$q%'
      ";
      if (!in_array($category, ['', null])) {
        $search .= "  or c.nama like '%$q%'
                      or c.slug like '%$q%'
                      or c.foto like '%$q%'
                      or c.keterangan like '%$q%'
        ";
      }
      $query->where("($search)");
    }
    $result = $query->get()->result_array();

    return $this->build($result);
  }


  private function build($inputs)
  {
    if ($inputs == null || !is_array($inputs)) {
      return [
        'data' => [],
        'length' => 0
      ];
    }

    $result = [];
    $counter = 0;
    foreach ($inputs as $input) {
      $result[] = [
        "source" => [
          'id' => $input['id'],
          'name' => $input['nama']
        ],
        "author" => $input['user_nama'],
        "title" => $input['nama'],
        "description" => $input['excerpt'],
        "url" => base_url('artikel/detail/') . $input['slug'],
        "urlToImage" => base_url('/files/artikel/daftar_artikel/') . $input['foto'],
        "publishedAt" => $input['created_at'],
        "content" => $input['detail'],
      ];
      $counter++;
    }

    return [
      'data' => $result,
      'length' => $counter
    ];
  }


  public function provinsi(String $key): array
  {
    $data = $this->db->select('id, name as text')
      ->from('address_provinces')
      ->where("name like '%$key%'")
      ->limit(10)
      ->get();

    $return  = [
      'length' => $data->num_rows(),
      'results' => $data->result_array()
    ];
    return $return;
  }

  public function provinsi_all(): array
  {
    $data = $this->db->select('id, name as text')
      ->from('address_provinces')
      ->get();

    $return  = [
      'length' => $data->num_rows(),
      'results' => $data->result_array()
    ];
    return $return;
  }

  public function kabupaten_kota($id_provinsi, String $key): array
  {
    $data = $this->db->select('id, name as text')
      ->from('address_regencies')
      ->where("province_id", $id_provinsi)
      ->where("(name like '%$key%')")
      ->limit(10)
      ->get();

    $return  = [
      'length' => $data->num_rows(),
      'results' => $data->result_array()
    ];
    return $return;
  }

  public function kecamatan($id_kabupaten_kota, String $key): array
  {
    $data = $this->db->select('id, name as text')
      ->from('address_districts')
      ->where("regency_id", $id_kabupaten_kota)
      ->where("(name like '%$key%')")
      ->limit(10)
      ->get();

    $return  = [
      'length' => $data->num_rows(),
      'results' => $data->result_array()
    ];
    return $return;
  }

  public function desa_kelurahan($id_kecamatan, String $key): array
  {
    $data = $this->db->select('id, name as text')
      ->from('address_villages')
      ->where("district_id", $id_kecamatan)
      ->where("(name like '%$key%')")
      ->limit(10)
      ->get();

    $return  = [
      'length' => $data->num_rows(),
      'results' => $data->result_array()
    ];
    return $return;
  }
}
