<div class="card card-primary card-outline">
  <div class="card-header">
    <div class="d-flex justify-content-between w-100">
      <h3 class="card-title">List Sosial Media</h3>
      <button type="button" class="btn btn-info btn-xs" data-toggle="modal" data-target="#modal_sosmed" id="btn-tambah-sosmed"><i class="fa fa-plus"></i> Tambah</button>
    </div>
  </div>
  <!-- /.card-header -->
  <div class="card-body">
    <table id="tsosmed" class="table table-bordered table-striped table-hover">
      <thead>
        <tr>
          <th>No</th>
          <th>Nama</th>
          <th>Icon</th>
          <th>Alamat</th>
          <th>Status</th>
          <th>Aksi</th>
        </tr>
      </thead>
    </table>
  </div>
</div>

<div class="modal fade" id="modal_sosmed" tabindex="-1" role="dialog" aria-labelledby="modal_sosmedLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header outline-info">
        <h5 class="modal-title text-center" id="modal_sosmedLabel"></h5>
      </div>
      <div class="modal-body">
        <form action="" id="fsosmedmodel" method="post">
          <input type="hidden" id="sosmed_id" name="id">
          <div class="form-group">
            <label for="name">Nama</label>
            <input type="text" class="form-control" id="sosmed_name" name="name" placeholder="Nama" required />
          </div>
          <div class="form-group">
            <label for="icon">Icon</label>
            <input type="text" class="form-control" id="sosmed_icon" name="icon" placeholder="Ex: fa fa-facebook" required />
            <small>Referensi: <a href="http://fontawesome.com/v5.15/icons?d=free">http://fontawesome.com/v5.15/icons?d=free</a></small>
          </div>
          <div class="form-group">
            <label for="link">Alamat</label>
            <input type="text" class="form-control" id="sosmed_link" name="link" placeholder="Ex: http://facebook.com/iseplutpinur7" required />
          </div>
          <div class="form-group">
            <label for="status">Status</label>
            <select name="status" id="sosmed_status" class="form-control">
              <option value="1">Aktif</option>
              <option value="0">Tidak Aktif</option>
            </select>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary btn-ef btn-ef-3 btn-ef-3c" type="submit" form="fsosmedmodel"><i class="fa fa-save"></i> Simpan</button>
        <button class="btn btn-success btn-ef btn-ef-3 btn-ef-3c" data-dismiss="modal"><i class="fa fa-arrow-left"></i> Kembali</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modal_sosmed_delete" tabindex="-1" role="dialog" aria-labelledby="modal_sosmed_deleteLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header outline-info">
        <h5 class="modal-title text-center" id="modal_sosmed_deleteLabel"></h5>
      </div>
      <div class="modal-body">
        <form action="" id="fhapussosmedmodel" method="post">
          <input type="hidden" id="sosmed_delete_id" name="id">
          <p>Apakah anda yakin akan menghapus data ini.?</p>
        </form>
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary btn-ef btn-ef-3 btn-ef-3c" type="submit" form="fhapussosmedmodel"><i class="fa fa-trash"></i> Hapus</button>
        <button class="btn btn-success btn-ef btn-ef-3 btn-ef-3c" data-dismiss="modal"><i class="fa fa-arrow-left"></i> Kembali</button>
      </div>
    </div>
  </div>
</div>