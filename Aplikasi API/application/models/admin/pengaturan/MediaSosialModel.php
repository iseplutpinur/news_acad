<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MediaSosialModel extends Render_Model
{
  // sosmed
  public function ajax($draw = null, $show = null, $start = null, $cari = null, $order = null)
  {
    $this->db->select("a.*,
        IF(a.status = '0' , 'Tidak Aktif', IF(a.status = '1' , 'Aktif', 'Tidak Diketahui')) as status_str
        ");
    $this->db->from("home_sosmed a");
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

    // initial data table
    if ($draw == 1) {
      $this->db->limit(10, 0);
    }

    // pencarian
    if ($cari != null) {
      $this->db->where("(
                a.name LIKE '%$cari%' or
                a.link LIKE '%$cari%' or
                a.icon LIKE '%$cari%' or
                a.name LIKE '%$cari%' or
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

  public function insert($user_id, $name, $link, $icon, $status)
  {
    $data = [
      'name' => $name,
      'status' => $status,
      'link' => $link,
      'icon' => $icon,
      'created_by' => $user_id,
    ];
    $execute = $this->db->insert('home_sosmed', $data);
    $execute = $this->db->insert_id();
    return $execute;
  }

  public function update($id, $user_id, $name, $link, $icon, $status)
  {
    $data = [
      'name' => $name,
      'status' => $status,
      'link' => $link,
      'icon' => $icon,
      'updated_by' => $user_id,
      'updated_at' => Date("Y-m-d H:i:s", time()),
    ];
    $execute = $this->db->where('id', $id);
    $execute = $this->db->update('home_sosmed', $data);
    return  $execute;
  }

  public function delete($id)
  {
    $exe = $this->db->where('id', $id)->delete('home_sosmed');
    return $exe;
  }
}
