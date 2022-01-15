$(function () {
  function dynamic() {
    const table_html = $('#dt_basic');
    table_html.dataTable().fnDestroy()
    const new_table = table_html.DataTable({
      "ajax": {
        "url": "<?= base_url()?>admin/artikel/daftarArtikel/ajax_data/",
        "data": null,
        "type": 'POST'
      },
      "processing": true,
      "serverSide": true,
      "responsive": true,
      "lengthChange": true,
      "autoWidth": false,
      "columns": [
        { "data": null },
        { "data": "nama" },
        { "data": "excerpt" },
        {
          "data": "foto", render(data, type, full, meta) {
            return `<button
                      class="btn btn-success btn-sm btn-gambar"
                      data-toggle="modal"
                      data-data="${data}"
                      data-target="#gambar_modal"
                      onclick="view_gambar(this)"
                      id="btn-gambar"><i class="fas fa-eye"></i></button>`
          }, className: "nowrap"
        },
        { "data": "status_str" },
        {
          "data": "id", render(data, type, full, meta) {
            return `<div class="pull-right">
            <a class="btn btn-info btn-xs" href=""><i class="fa fa-eye"></i> Lihat</a>
            <a class="btn btn-primary btn-xs" href="<?= base_url()?>admin/artikel/daftarArtikel/buat/${data}"><i class="fa fa-edit"></i> Ubah</a>
                <button class="btn btn-danger btn-xs" onclick="Hapus(${data})">
                  <i class="fa fa-trash"></i> Hapus
                </button>
              </div>`
          }, className: "nowrap"
        }
      ],
      order: [
        [1, 'asc']
      ],
      columnDefs: [{
        orderable: false,
        targets: [0, 5]
      }],
    });
    new_table.on('draw.dt', function () {
      var PageInfo = table_html.DataTable().page.info();
      new_table.column(0, {
        page: 'current'
      }).nodes().each(function (cell, i) {
        cell.innerHTML = i + 1 + PageInfo.start;
      });
    });
  }
  dynamic();

  // hapus
  $('#OkCheck').click(() => {
    let id = $("#idCheck").val()
    $.LoadingOverlay("show");
    $.ajax({
      method: 'post',
      url: '<?= base_url() ?>admin/artikel/daftarArtikel/delete',
      data: {
        id: id
      }
    }).done((data) => {
      Toast.fire({
        icon: 'success',
        title: 'Data berhasil dihapus'
      })
      dynamic();
    }).fail(($xhr) => {
      Toast.fire({
        icon: 'error',
        title: 'Data gagal dihapus'
      })
    }).always(() => {
      $('#ModalCheck').modal('toggle')
      $.LoadingOverlay("hide");
    })
  })
})

const view_gambar = (datas) => {
  $("#img-view").attr('src', `<?= base_url() ?>files/artikel/daftar_artikel/${datas.dataset.data}`)
}

// Click Hapus
const Hapus = (id) => {
  $("#idCheck").val(id)
  $("#LabelCheck").text('Form Hapus')
  $("#ContentCheck").text('Apakah anda yakin akan menghapus data ini?')
  $('#ModalCheck').modal('toggle')
}
