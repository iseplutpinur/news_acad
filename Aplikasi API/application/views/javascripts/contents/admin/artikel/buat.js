$(document).ready(() => {
  // $('.summernote').summernote({
  //   toolbar: [
  //     ['fontsize', ['fontsize']], ['fontname', ['fontname']], ['style', ['bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear']],
  //     ['para', ['ul', 'ol', 'paragraph']], ['height', ['height']], ['color', ['color']], ['float', ['floatLeft', 'floatRight', 'floatNone']], ['remove', ['removeMedia']], ['table', ['table']], ['insert', ['link', 'unlink', 'video', 'audio', 'hr']], ['mybutton', ['myVideo']], ['view', ['fullscreen', 'codeview']], ['help', ['help']]],
  //   height: (200),
  // })

  $("#nama").keyup(function () {
    var Text = $(this).val();
    $("#slug").val(Text.toLowerCase()
      .replace(/[^\w ]+/g, '')
      .replace(/ +/g, '-'));
  });

  $('.select2-class').select2();
  $("#main-form").submit(function (ev) {
    ev.preventDefault();
    const form = new FormData(this);
    $.LoadingOverlay("show");
    $.ajax({
      method: 'post',
      url: '<?= base_url() ?>admin/artikel/daftarArtikel/simpan',
      data: form,
      cache: false,
      contentType: false,
      processData: false,
    }).done((data) => {
      Toast.fire({
        icon: 'success',
        title: 'Data berhasil disimpan'
      })

      setTimeout(() => {
        window.location.reload();
      }, 300)
    }).fail(($xhr) => {
      Toast.fire({
        icon: 'error',
        title: 'Data gagal disimpan, Mungkin slug sudah terdaftar'
      })
    }).always(() => {
      $.LoadingOverlay("hide");
    })
  });
});