let modal_create = false;
$(function () {
  function dynamic() {
    const table_html = $('#dt_basic');
    table_html.dataTable().fnDestroy()
    const new_table = table_html.DataTable({
      "ajax": {
        "url": "<?= base_url()?>admin/pengurus/ajax_data",
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
        { "data": "user_nama" },
        { "data": "user_email" },
        { "data": 'npp' },
        { "data": 'thn_angkatan' },
        { "data": "status_str" },
        {
          "data": "id", render(data, type, full, meta) {
            const btn_ubah = `
                <button
                data-id="${full.id}"
                data-npp="${full.npp}"
                data-angkatan="${full.thn_angkatan}"
                data-nama="${full.user_nama}"
                data-email="${full.user_email}"
                data-status="${full.user_status}"
                  class="btn btn-primary btn-xs"
                  onclick="Ubah(this)">
                  <i class="fa fa-edit"></i> Ubah
                </button>
            `;

            const btn_hapus = `
                <button class="btn btn-danger btn-xs" onclick="Hapus(${data})">
                  <i class="fa fa-trash"></i> Hapus
                </button>
            `;

            const btn_detail = `
                <button class="btn btn-secondary btn-xs">
                  <i class="fa fa-user"></i> Detail
                </button>
            `;
            let btn = btn_detail;
            btn += btn_ubah;
            btn += btn_hapus;
            return `<div class="pull-right">${btn}</div>`
          }
        }
      ],
      columnDefs: [{
        orderable: false,
        targets: [0, 6]
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

  $("#btn-tambah").click(() => {
    if (!modal_create) {
      $("#tambahModalTitle").text("Tambah pengurus");
      $('#id').val('');
      $('#npp').val('');
      $('#angkatan').val('');
      $('#nama').val('');
      $('#email').val('');
      $('#password').val('123456');
      $('#status').val('');
      modal_create = true;
    }
  });

  $("#fmain").submit(function (ev) {
    ev.preventDefault();
    const form = new FormData(this);
    $.LoadingOverlay("show");
    $.ajax({
      method: 'post',
      url: '<?= base_url() ?>admin/pengurus/' + ($("#id").val() == "" ? 'insert' : 'update'),
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
      modal_create = false;
    }).fail(($xhr) => {
      Toast.fire({
        icon: 'error',
        title: 'Data gagal disimpan'
      })
    }).always(() => {
      $.LoadingOverlay("hide");
      $('#tambahModal').modal('toggle')
    })
  });

  // hapus
  $('#OkCheck').click(() => {
    let id = $("#idCheck").val()
    $.LoadingOverlay("show");
    $.ajax({
      method: 'post',
      url: '<?= base_url() ?>admin/pengurus/delete',
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


// Click Hapus
const Hapus = (id) => {
  $("#idCheck").val(id)
  $("#LabelCheck").text('Form Hapus')
  $("#ContentCheck").text('Apakah anda yakin akan menghapus data ini?')
  $('#ModalCheck').modal('toggle')
}

// Click Ubah
const Ubah = (datas) => {
  const data = datas.dataset;
  modal_create = false;
  $('#id').val(data.id);
  $('#npp').val(data.npp);
  $('#angkatan').val(data.angkatan);
  $('#nama').val(data.nama);
  $('#email').val(data.email);
  $('#status').val(data.status);
  $('#password').val('');
  $('#tambahModal').modal('toggle');
  $("#tambahModalTitle").text("Ubah pengurus");
}

// Detail
const modal_detail = (datas) => {
  const data = datas.dataset;
  const datakep = datapengurus.get(data.id);
  $("#modal_detail_title").html(`Detail Jabatan ${datakep.nama}`);
  $("#modal_detail_body").html(`
                                <h4>Nama:</h4>  ${datakep.nama}
                                <h4>Logo:</h4>  <img  class="img-fluid" src="<?= base_url() ?>/files/front/pengurus/${datakep.foto}">
                                <h4>Visi:</h4>  ${datakep.visi}
                                <h4>Misi:</h4>  ${datakep.misi}
                                <h4>Slogan:</h4>  ${datakep.slogan}
                                <h4>Keterangan:</h4>  ${datakep.keterangan}
  `);
}

const Aktifkan = (id) => {
  $("#aktifkan_id").val(id)
  $('#modal_aktifkan').modal('toggle')
}
