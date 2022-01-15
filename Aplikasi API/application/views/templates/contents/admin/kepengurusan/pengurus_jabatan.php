<div class="row">
  <div class="col-lg-6">
    <div class="card card-primary card-outline">
      <div class="card-header">
        <h3 class="card-title"><?= $jabatan['with_parrent'] ?></h3>
      </div>
      <div class="card-body">
        <div class="form-group">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <label for="pengurus">Pengurus</label>
            <button type="submit" form="fmain" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#tambahModal" id="btn-tambah"><i class="fa fa-plus"></i> Tambah</button>
          </div>
          <form action="" id="fmain">
            <select class="form-control" id="pengurus" name="user_id" style="width: 100%;" required>
            </select>
            <input type="hidden" name="pengurus_jabatan_id" value="<?= $jabatan['id'] ?>">
          </form>
        </div>
        <hr>
        <table id="dt_basic" class="table table-bordered table-striped table-hover">
          <thead>
            <tr>
              <th style="max-width: 50px;">No</th>
              <th style="max-width: 75px;">Angkatan</th>
              <th>Nama</th>
              <th></th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>
</div>

<script>
  const pengurus_jabatan_id = '<?= $jabatan['id'] ?>';
</script>