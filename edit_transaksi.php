<?php
//session_start();
if (!isset($_SESSION['apriori_id'])) {
    header("location:index.php?menu=forbidden");
}

include_once "database.php";
include_once "fungsi.php";
include_once "import/excel_reader2.php";

$db_object = new database();

$id = $_GET['id'];
$sql ="SELECT * FROM transaksi WHERE id='$id'";
$query=$db_object->db_query($sql);


if (isset($_POST['update'])) {
  $id = $_POST['id'];
  $transaction_date = $_POST['transaction_date'];
  $produk = $_POST['produk'];
  $sql = "UPDATE transaksi SET transaction_date='$transaction_date',produk='$produk' WHERE id='$id'";
  $db_object->db_query($sql);
  if ($sql) {
    echo "<script> location.replace('?menu=data_transaksi&pesan_success=Data berhasil diupdate'); </script>";
  } else {
    echo "<script> location.replace('?menu=data_transaksi&pesan_success=Data gagal diupdate'); </script>";
  }
}

?>     


<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-fw fa-money-bill-wave"></i> Data Penjualan</h1>
	
	<a href="?menu=data_transaksi" class="btn btn-secondary"><i class="fa fa-arrow-circle-left"></i> Kembali</a>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-danger"><i class="fas fa-fw fa-edit"></i> Edit Data Penjualan</h6>
    </div>


  <?php
  foreach ($query as $data) {
    ?>
	
	<form method="post" enctype="multipart/form-data" action="">
	<div class="card-body">
                    
                <div class="col-12">
                    <label for="" class="font-weight-bold">Tanggal</label>
                    <div class="form-group">
                        <input type="hidden" name="id" class="form-control" value="<?= $data['id'] ?>">
						<input type="date" name="transaction_date" value="<?= $data['transaction_date'] ?>" class="form-control" required="" />
                    </div>
                </div>
				
				<div class="col-12">
                    <label for="" class="font-weight-bold">Barang</label>
                    <div class="form-group">
						<textarea class="form-control" name="produk" rows="3" required=""><?= $data['produk'] ?></textarea>
                    </div>
                </div>
				
                </div>

				<div class="card-footer text-right">
            <button name="update" type="submit" class="btn btn-success"><i class="fa fa-save"></i> Update</button>
            <button type="reset" class="btn btn-info"><i class="fa fa-sync-alt"></i> Reset</button>
        </div>
    </form>
</div>

<?php } ?>


  