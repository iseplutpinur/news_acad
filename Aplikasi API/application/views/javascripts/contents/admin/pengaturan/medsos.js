$(document).ready(() => {

  function dt_sosmed() {
    const table_html = $('#tsosmed');
    table_html.dataTable().fnDestroy()
    const new_table = table_html.DataTable({
      "ajax": {
        "url": "<?= base_url()?>admin/pengaturan/mediaSosial/ajax/",
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
        { "data": "name" },
        { "data": "icon" },
        { "data": "link" },
        { "data": "status_str" },
        {
          "data": "id", render(data, type, full, meta) {
            return `<div class="pull-right">
              <button class="btn btn-primary btn-xs"
                                    data-id="${data}"
                                    data-name="${full.name}"
                                    data-link="${full.link}"
                                    data-icon="${full.icon}"
                                    data-status="${full.status}"
                                    data-toggle="modal" data-target="#modal_sosmed"
                                onclick="ubah_sosmed(this)">
                <i class="fa fa-edit"></i> Ubah
              </button>
              <button class="btn btn-danger btn-xs"
              data-toggle="modal" data-target="#modal_sosmed_delete"
              data-id="${data}" onclick="hapus_sosmed(this)">
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
        targets: [0, 4]
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
  dt_sosmed();

  $("#btn-tambah-sosmed").click(() => {
    $("#modal_sosmedLabel").text("Tambah Sosial Media");
    $('#sosmed_id').val('');
    $('#sosmed_name').val('');
    $('#sosmed_icon').val('');
    $('#sosmed_link').val('');
    $('#sosmed_status').val('1');
  });

  $("#fsosmedmodel").submit(function (ev) {
    ev.preventDefault();
    const form = new FormData(this);
    $.LoadingOverlay("show");
    $.ajax({
      method: 'post',
      url: '<?= base_url() ?>admin/pengaturan/mediaSosial/' + ($("#sosmed_id").val() == "" ? 'insert' : 'update'),
      data: form,
      cache: false,
      contentType: false,
      processData: false,
    }).done((data) => {
      Toast.fire({
        icon: 'success',
        title: 'Data berhasil disimpan'
      })
      dt_sosmed();
    }).fail(($xhr) => {
      Toast.fire({
        icon: 'error',
        title: 'Data gagal disimpan'
      })
    }).always(() => {
      $.LoadingOverlay("hide");
      $('#modal_sosmed').modal('toggle')
    })
  });

  $("#fhapussosmedmodel").submit(function (ev) {
    ev.preventDefault();
    const form = new FormData(this);
    $.LoadingOverlay("show");
    $.ajax({
      method: 'post',
      url: `<?= base_url() ?>admin/pengaturan/mediaSosial/delete`,
      data: form,
      cache: false,
      contentType: false,
      processData: false,
    }).done((data) => {
      Toast.fire({
        icon: 'success',
        title: 'Data berhasil dihapus'
      })
      dt_sosmed();
    }).fail(($xhr) => {
      Toast.fire({
        icon: 'error',
        title: 'Data gagal dihapus'
      })
    }).always(() => {
      $.LoadingOverlay("hide");
      $("#modal_sosmed_delete").modal('toggle');
    })
  });

});

const ubah_list = datas => {
  const data = datas.dataset;
  $('#list_id').val(data.id);
  $('#list_link').val(data.link);
  $('#list_name').val(data.name);
  $('#list_status').val(data.status);
  $("#modal_listLabel").text("Ubah List Data");
}
const hapus_list = datas => {
  const data = datas.dataset;
  $('#list_delete_id').val(data.id);
  $("#modal_list_deleteLabel").text("Hapus List Data");
}

const ubah_sosmed = datas => {
  const data = datas.dataset;
  $('#sosmed_id').val(data.id);
  $('#sosmed_link').val(data.link);
  $('#sosmed_icon').val(data.icon);
  $('#sosmed_name').val(data.name);
  $('#sosmed_status').val(data.status);
  $("#modal_sosmedLabel").text("Ubah Sosial Media");
}

const hapus_sosmed = datas => {
  const data = datas.dataset;
  $('#sosmed_delete_id').val(data.id);
  $("#modal_sosmed_deleteLabel").text("Hapus Sosial Media");
}