<div class="container-fluid page__heading-container">
  <div class="page__heading d-flex align-items-center">
    <div class="flex">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
          <li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
          <li class="breadcrumb-item active" aria-current="page">Master Domain</li>
        </ol>
      </nav>
      <h4><span id="nama-departemen"></span>Master Domain</h4>
    </div>

  </div>
</div>


<div class="container-fluid page__container">
  <div class="row">
    <div class="col-lg-12 col-md-12">
      <div class="card">
        <div class="card-header card-header-large bg-white d-flex align-items-center">
          <h5 class="card-header__title flex m-0">Master Domain</h5>
          <button class="btn btn-info" id="btnTambah">
            <i class="fa fa-plus"></i> Tambah
          </button>
        </div>
        <div class="card-body tab-content">
          <div class="tab-pane active show fade">
            <table class="table table-padding largeDataType" id="table-domain">
              <thead class="thead-dark">
                <tr>
                  <th class="wrap_content">No</th>
                  <th>Nama</th>
                  <th class="wrap_content">Aksi</th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>