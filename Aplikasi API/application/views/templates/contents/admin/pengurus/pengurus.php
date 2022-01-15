<div class="card card-primary card-outline">
  <div class="card-header">
    <div class="d-flex justify-content-between w-100">
      <h3 class="card-title">List Pengurus</h3>
      <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#tambahModal" id="btn-tambah"><i class="fa fa-plus"></i> Tambah</button>
    </div>
  </div>
  <div class="card-body">
    <table id="dt_basic" class="table table-bordered table-striped table-hover">
      <thead>
        <tr>
          <th>No</th>
          <th>Nama</th>
          <th>Email</th>
          <th>NPP</th>
          <th>Angkatan</th>
          <th>Status</th>
          <th>Aksi</th>
        </tr>
      </thead>
    </table>
  </div>
</div>

<!-- view foto -->
<div class="modal fade" id="tambahModal" tabindex="-1" role="dialog" aria-labelledby="tambahModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header outline-info">
        <h5 class="modal-title text-center" id="tambahModalTitle"></h5>
      </div>
      <div class="modal-body">
        <form action="" id="fmain" method="post">
          <input type="hidden" id="id" name="id">
          <div class="form-group">
            <label for="npp">Nomro Pokok Pengurus</label>
            <input type="text" class="form-control" id="npp" name="npp" placeholder="Nomro Pokok Pengurus" required />
          </div>
          <div class="form-group">
            <label for="angkatan">Angkatan</label>
            <input type="number" class="form-control" id="angkatan" name="angkatan" placeholder="Angkatan" required />
          </div>
          <div class="form-group">
            <label for="nama">Nama</label>
            <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama" required />
          </div>
          <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Email" required />
          </div>
          <div class="form-group">
            <label for="password">Password</label>
            <input type="text" class="form-control" id="password" name="password" value="123456" placeholder="Password" />
          </div>
          <div class="form-group">
            <label for="status">Status</label>
            <select class="form-control" id="status" name="status" required>
              <option value="">--Pilih Status--</option>
              <option value="1">Aktif</option>
              <option value="0">Tidak Aktif</option>
            </select>
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