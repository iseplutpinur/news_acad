$(function () {
  $('#pengurus').select2({
    ajax: {
      url: '<?= base_url() ?>admin/jabatan/pengurus_cari',
      dataType: 'json',
      method: 'post',
      data: function (params) {
        return {
          q: params.term
        };
      },
    },
    dropdownParent: $(".card-body"),
    minimumInputLength: 1,
  })

  function dynamic() {
    const table_html = $('#dt_basic');
    table_html.dataTable().fnDestroy()
    const new_table = table_html.DataTable({
      "ajax": {
        "url": "<?= base_url()?>admin/jabatan/pengurus_datatable",
        "data": {
          pengurus_jabatan_id: pengurus_jabatan_id
        },
        "type": 'POST'
      },
      "processing": true,
      "serverSide": true,
      "responsive": true,
      "lengthChange": true,
      "autoWidth": false,
      "columns": [
        { "data": null },
        { "data": 'thn_angkatan' },
        { "data": "user_nama" },
        {
          "data": "id", render(data, type, full, meta) {
            return `<button class="btn btn-danger btn-xs" onclick="Hapus(${data})">
              <i class="fa fa-trash"></i> Hapus
            </button>`;
          }
        }
      ],
      columnDefs: [{
        orderable: false,
        targets: [0, 3]
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

  $('#OkCheck').click(() => {
    let id = $("#idCheck").val()
    $.LoadingOverlay("show");
    $.ajax({
      method: 'post',
      url: '<?= base_url() ?>admin/jabatan/pengurus_delete',
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

  $("#fmain").submit(function (ev) {
    ev.preventDefault();
    const form = new FormData(this);
    $.LoadingOverlay("show");
    $.ajax({
      method: 'post',
      url: '<?= base_url() ?>admin/jabatan/pengurus_insert',
      data: form,
      cache: false,
      contentType: false,
      processData: false,
    }).done((data) => {
      Toast.fire({
        icon: 'success',
        title: 'Data berhasil disimpan'
      })
      dynamic();
    }).fail(($xhr) => {
      Toast.fire({
        icon: 'error',
        title: 'Data gagal disimpan'
      })
    }).always(() => {
      $.LoadingOverlay("hide");
      $('#modal_aktifkan').modal('toggle')
    })
  });
})


// Click Hapus
const Hapus = (id) => {
  $("#idCheck").val(id)
  $("#LabelCheck").text('Form Hapus')
  $("#ContentCheck").text('Apakah anda yakin akan menghapus data ini?')
  $('#ModalCheck').modal('toggle')
}