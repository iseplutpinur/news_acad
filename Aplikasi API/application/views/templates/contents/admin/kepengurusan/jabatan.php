<div class="card card-primary card-outline">
  <div class="card-header">
    <div class="d-flex justify-content-between w-100">
      <h3 class="card-title">List Bidang: <?= $kepengurusan['nama'] ?></h3>
      <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#tambahModal" id="btn-tambah"><i class="fa fa-plus"></i> Tambah</button>
    </div>
  </div>
  <!-- /.card-header -->
  <div class="card-body">
    <table id="dt_basic" class="table table-bordered table-striped table-hover">
      <thead>
        <tr>
          <th>No</th>
          <th>Urutan</th>
          <th>Bidang</th>
          <th>Sub Bidang</th>
          <th style="min-width: 150px;">Slug</th>
          <th>Detail</th>
          <th>Status</th>
          <th>Aksi</th>
        </tr>
      </thead>
    </table>
  </div>
  <!-- /.card-body -->
</div>

<!-- view foto -->
<div class="modal fade" id="tambahModal" tabindex="-1" role="dialog" aria-labelledby="tambahModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header outline-info">
        <h5 class="modal-title text-center" id="tambahModalTitle"></h5>
      </div>
      <div class="modal-body">
        <form action="" id="fmain" method="post">
          <input type="hidden" id="id" name="id">
          <input type="hidden" id="temp_foto" name="temp_foto">
          <input type="hidden" id="pengurus_periode_id" name="pengurus_periode_id" value="<?= $kepengurusan['id'] ?>">
          <div class="row">
            <div class="col-lg-6">
              <div class="form-group">
                <label for="parrent_id">Utama</label>
                <select class="form-control" id="parrent_id" name="parrent_id">

                </select>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-group">
                <label for="nama">Nama</label>
                <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama" required />
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-group">
                <label for="slug">Slug</label>
                <input type="text" class="form-control" id="slug" name="slug" placeholder="Alamat url untuk akses jabatan" required />
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-group">
                <label for="no_urut">No Urut</label>
                <input type="number" class="form-control" id="no_urut" name="no_urut" placeholder="No Uurut" required />
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-group">
                <label for="slogan">Slogan</label>
                <input type="text" class="form-control" id="slogan" name="slogan" placeholder="Slogan" />
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-group">
                <label for="foto">Icon</label>
                <input type="file" class="form-control-file" id="foto" name="foto" accept="image/png, image/jpeg, image/JPG, image/PNG, image/JPEG">
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-group">
                <label for="status">Status</label>
                <select class="form-control" id="status" name="status" required>
                  <option value="">--Pilih Status--</option>
                  <option value="1">Aktif</option>
                  <option value="0">Tidak Aktif</option>
                </select>
              </div>
            </div>
          </div>

          <div class="form-group">
            <label for="visi">Visi</label>
            <textarea cols="3" rows="4" class="form-control summernote" id="visi" name="visi" placeholder="Visi"></textarea>
          </div>
          <div class="form-group">
            <label for="misi">Misi</label>
            <textarea cols="3" rows="4" class="form-control summernote" id="misi" name="misi" placeholder="Misi"></textarea>
          </div>
          <div class="form-group">
            <label for="keterangan">Keterangan</label>
            <textarea cols="3" rows="4" class="form-control" id="keterangan" name="keterangan" placeholder="Keterangan"></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary btn-ef btn-ef-3 btn-ef-3c" type="submit" form="fmain"><i class="fa fa-save"></i> Simpan</button>
        <button class="btn btn-success btn-ef btn-ef-3 btn-ef-3c" data-dismiss="modal"><i class="fa fa-arrow-left"></i> Kembali</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="gambar_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header outline-info">
        <h5 class="modal-title text-center">Icon</h5>
      </div>
      <div class="modal-body">
        <img src="<?= base_url() ?>\assets\images\student.png" class="img-fluid" alt="" id="img-view">
      </div>
      <div class="modal-footer">
        <button class="btn btn-success btn-ef btn-ef-3 btn-ef-3c" data-dismiss="modal"><i class="fa fa-arrow-left"></i> Kembali</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modal_detail" tabindex="-1" role="dialog" aria-labelledby="modal_detailLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header outline-info">
        <h5 class="modal-title text-center" id="modal_detail_title"></h5>
      </div>
      <div class="modal-body" id="modal_detail_body">
      </div>
      <div class="modal-footer">
        <button class="btn btn-success btn-ef btn-ef-3 btn-ef-3c" data-dismiss="modal"><i class="fa fa-arrow-left"></i> Kembali</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modal_aktifkan" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header outline-info">
        <h5 class="modal-title text-center">Aktifkan Jabatan</h5>
      </div>
      <div class="modal-body">
        <form action="" id="faktifkan">
          <input type="hidden" name="id" id="aktifkan_id">
        </form>
        Apakah anda yakin akan mengaktifkan kepengurusan ini..?
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary btn-ef btn-ef-3 btn-ef-3c" type="submit" form="faktifkan"><i class="fa fa-save"></i> Simpan</button>
        <button class="btn btn-success btn-ef btn-ef-3 btn-ef-3c" data-dismiss="modal"><i class="fa fa-arrow-left"></i> Kembali</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modal_pengurus" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header outline-info">
        <h5 class="modal-title text-center" id="modal_pengurus_title">Pengurus Detail</h5>
      </div>
      <div class="modal-body">
        <table id="modal_pengurus_table" class="table table-bordered table-striped table-hover" style="width: 100%;">
          <thead>
            <tr>
              <th style="max-width: 15px;">No</th>
              <th style="max-width: 20px;">Angkatan</th>
              <th>Nama</th>
            </tr>
          </thead>
          <tbody id="modal_pengurus_table_body">

          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button class="btn btn-success btn-ef btn-ef-3 btn-ef-3c" data-dismiss="modal"><i class="fa fa-arrow-left"></i> Kembali</button>
      </div>
    </div>
  </div>
</div>

<script>
  const pengurus_periode_id = '<?= $kepengurusan['id'] ?>';
  const pengurus_periode = '<?= $kepengurusan['dari'] . '-' . $kepengurusan['sampai'] ?>';
</script>