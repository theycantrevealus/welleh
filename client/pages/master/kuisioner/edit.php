<div class="container-fluid page__heading-container">
  <div class="page__heading d-flex align-items-center">
    <div class="flex">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
          <li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
          <li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/master/kuisioner">Kuisioner</a></li>
          <li class="breadcrumb-item active" aria-current="page">Edit</li>
        </ol>
      </nav>
      <h4><span id="nama-departemen"></span>Edit Kuisioner</h4>
    </div>

  </div>
</div>


<div class="container-fluid page__container">
  <div class="row card-group-row">
    <div class="col-lg-12 col-md-12 card-group-row__col">
      <div class="card card-group-row__card card-body">
        <div class="row">
          <div class="col-4">
            <div class="form-group">
              <label for="txt_nama">Domain</label>
              <select class="form-control" id="txt_domain"></select>
            </div>
          </div>
          <div class="col-4">
            <div class="form-group">
              <label for="txt_nama">Aspek</label>
              <select class="form-control" id="txt_aspek"></select>
            </div>
          </div>
          <div class="col-4">
            <div class="form-group">
              <label for="txt_nama">Indikator</label>
              <select class="form-control" id="txt_indikator"></select>
            </div>
          </div>
          <div class="col-12">
            <div class="form-group">
              <label for="txt_nama">Pertanyaan</label>
              <textarea id="txt_pertanyaan" style="max-width: 100%;" class="form-control"></textarea>
            </div>
          </div>
          <?php
          for ($a = 0; $a <= 5; $a++) {
          ?>
            <div class="col-12">
              <div class="form-group">
                <label for="txt_level_<?php echo $a; ?>">Level <?php echo $a; ?></label>
                <textarea id="txt_level_<?php echo $a; ?>" class="form-control"></textarea>
              </div>
            </div>
          <?php
          }
          ?>
          <div class="col-12">
            <div class="form-group">
              <label for="txt_penjelasan">Penjelasan</label>
              <textarea class="form-control" id="txt_penjelasan"></textarea>
            </div>
          </div>
          <div class="col-12">
            <a href="<?php echo __HOSTNAME__; ?>/master/kuisioner" class="btn btn-danger">
              <i class="fa fa-ban"></i> Kembali
            </a>
            <button class="btn btn-info pull-right" id="btnSubmit">
              <i class="fa fa-save"></i> Update
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>