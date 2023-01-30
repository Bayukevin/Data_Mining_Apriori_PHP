<div class="mb-4">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-fw fa-home"></i> Halaman Utama</h1>
  </div>

  <!-- Content Row -->
  <div class="alert alert-success">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    Selamat datang <strong><?php echo $_SESSION['apriori_username']; ?></strong>
  </div>
  <div class="row">

  

    <div class="col-xl-4 col-md-6 mb-4">
      <div class="card border-left-secondary shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="h5 mb-0 font-weight-bold text-gray-800"><a href="index.php?menu=hasil" class="text-secondary text-decoration-none">Data Hasil</a></div>
            </div>
            <div class="col-auto">
              <i class="fas fa-coins fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  