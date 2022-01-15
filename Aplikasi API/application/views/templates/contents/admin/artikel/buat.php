<div class="card card-primary card-outline mb-3">
  <div class="card-header">
    <div class="d-flex justify-content-between w-100">
      <h3 class="card-title">Data Artikel</h3>
    </div>
  </div>
  <!-- /.card-header -->
  <div class="card-body">
    <form id="main-form" enctype="multipart/form-data">
      <input type="hidden" name="id" id="id" value="<?= $getID; ?>">
      <input type="hidden" name="temp_foto" id="temp_foto" value="<?= is_null($artikel['foto']) ? '' : $artikel['foto']; ?>">
      <input type="hidden" name="is-ubah" id="is-ubah" value="<?= $isUbah ? 1 : 0; ?>">
      <div class="row">

        <div class="col-md-12">
          <div class="form-group">
            <label for="nama">Nama Artikel<span class="text-red">*</span></label>
            <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama Artikel" required value="<?= $artikel['nama'] ?>" />
          </div>
        </div>

        <div class="col-md-6">
          <div class="form-group">
            <label for="slug">Slug<span class="text-red">*</span></label>
            <input type="text" class="form-control" id="slug" name="slug" placeholder="Untuk url" required value="<?= $artikel['slug'] ?>" />
            <small>Slug digunakan untuk akses artikel lewat url atau alamt web, slug diatas tidak boleh sama dengan slug dari artikel yang lain.</small>
          </div>
        </div>

        <div class="col-md-6">
          <div class="form-group">
            <label for="foto">Foto Artikel<span class="text-red">*</span>
              <?php if (!is_null($artikel['foto'])) : ?>
                <a href="#" data-toggle="modal" data-target="#gambar_modal">Lihat</a>
              <?php endif; ?>
            </label>
            <input type="file" class="form-control" id="foto" name="foto" placeholder="Foto Artikel" <?= is_null($artikel['foto']) ? 'required' : ''; ?> />
          </div>
        </div>

        <div class="col-12">
          <div class="form-group">
            <label for="excerpt">Kutipan</label>
            <textarea name="excerpt" id="excerpt" rows="3" class="form-control" placeholder="Kutipan detail artikel, atau ringkasan deskripsi artikel"><?= $artikel['excerpt'] ?></textarea>
          </div>
        </div>

        <div class="col-12">
          <div class="form-group">
            <label for="detail">Deskripsi Artikel</label>
            <textarea name="detail" id="detail" rows="3" class="form-control summernote" placeholder="Deskripsi Artikel"><?= $artikel['detail'] ?></textarea>
          </div>
        </div>

        <div class="col-md-6">
          <label for="tag">Artikel Tag</label>
          <br>
          <select class="select2-class w-100" name="tag[]" id="tag" multiple="multiple">
            <?php foreach ($tags as $tag) : ?>
              <option value="<?= $tag['id'] ?>" <?= $tag['selected'] != '0' ? 'selected' : '' ?>><?= $tag['text'] ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-6">
          <label for="kategori">Kategori</label>
          <br>
          <select class="select2-class w-100" name="kategori[]" multiple="multiple">
            <?php foreach ($categories as $categori) : ?>
              <option value="<?= $categori['id'] ?>" <?= $categori['selected'] != '0' ? 'selected' : '' ?>><?= $categori['text'] ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="col-md-6">
          <br>
          <div class="form-group">
            <input type="checkbox" id="publish" name="publish" title="Publikasikan Artikel" <?= $artikel['status'] == 2 ? 'checked' : ''; ?> form="main-form" />
            <label for="publish">Publikasikan Artikel</label>
          </div>
        </div>
      </div>
    </form>
  </div>

  <!-- /.card-body -->
  <div class="card-footer text-right">
    <button type="submit" form="main-form" class="btn btn-primary">
      <i class="fa fa-save"></i> Simpan
    </button>
    <a class="btn btn-danger" href="<?= base_url() ?>admin/artikel/daftarArtikel/"><i class="fa fa-save"></i> Kembali</a>
  </div>
</div>
<script>
  global_id_user = "<?= $artikel['id_user'] ?? 0 ?>";
</script>

<div class="modal fade" id="gambar_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header outline-info">
        <h5 class="modal-title text-center">Gambar</h5>
      </div>
      <div class="modal-body">
        <img src="<?= base_url() ?>files/artikel/daftar_artikel/<?= $artikel['foto'] ?>" class="img-fluid" alt="" id="img-view">
      </div>
      <div class="modal-footer">
        <button class="btn btn-success btn-ef btn-ef-3 btn-ef-3c" data-dismiss="modal"><i class="fa fa-arrow-left"></i> Kembali</button>
      </div>
    </div>
  </div>
</div>