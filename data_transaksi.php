<?php
//session_start();
if (!isset($_SESSION['apriori_id'])) {
    header("location:index.php?menu=forbidden");
}
$tanggalSekarang = date('Y-m-d');
include_once "database.php";
include_once "fungsi.php";
include_once "import/excel_reader2.php";
$conn = mysqli_connect('localhost', 'root', '', 'dm_apriori');

// table id
function tableId($table, $get, $id)
{
    global $conn;
    return mysqli_query($conn, "SELECT * FROM `$table` WHERE $get='$id' ");
}

if ($_SESSION['apriori_level'] == "1") {
    $button = '              
                </button>
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modaladd">
                <i class="fa fa-plus"></i> Tambah Data
                </button>';
    $deleteAll = '<button name="delete" type="submit" class="btn btn-app btn-danger btn-sm" onClick=\'return confirm("Are you sure?")\'><i class="fas fa-fw fa-trash"></i> Delete All Data</button>';
} else {
    $button = '';
    $deleteAll = '';
}
?>


<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-fw fa-money-bill-wave"></i> Data Transaksi</h1>

    <div class="text-right">
        <?php echo $button; ?>
    </div>
</div>

<?php
$db_object = new database();

$pesan_error = $pesan_success = "";
if (isset($_GET['pesan_error'])) {
    $pesan_error = $_GET['pesan_error'];
}
if (isset($_GET['pesan_success'])) {
    $pesan_success = $_GET['pesan_success'];
}

if (isset($_POST['submit'])) {
    $data = new Spreadsheet_Excel_Reader($_FILES['file_data_transaksi']['tmp_name']);

    $baris = $data->rowcount($sheet_index = 0);
    $column = $data->colcount($sheet_index = 0);

    for ($i = 2; $i <= $baris; $i++) {
        for ($c = 1; $c <= $column; $c++) {
            $value[$c] = $data->val($i, $c);
        }

        $table = "transaksi";
        // $produkIn = get_produk_to_in($temp_produk);
        $temp_date = format_date($value[1]);
        $produkIn = $value[2];

        //mencegah ada jarak spasi
        $produkIn = str_replace(" ,", ",", $produkIn);
        $produkIn = str_replace("  ,", ",", $produkIn);
        $produkIn = str_replace("   ,", ",", $produkIn);
        $produkIn = str_replace("    ,", ",", $produkIn);
        $produkIn = str_replace(", ", ",", $produkIn);
        $produkIn = str_replace(",  ", ",", $produkIn);
        $produkIn = str_replace(",   ", ",", $produkIn);
        $produkIn = str_replace(",    ", ",", $produkIn);
        $sql = "INSERT INTO transaksi (transaction_date, produk) VALUES ";
        $value_in = array();
        $sql .= " ('$temp_date', '$produkIn')";
        $db_object->db_query($sql);
    }
?>
    <script>
        location.replace("?menu=data_transaksi&pesan_success=Data berhasil disimpan");
    </script>
<?php
}

if (isset($_POST['simpan'])) {
    $transaction_date   = $_POST['transaction_date'];
    // cara input data banyak ke table db dengan implode
    $data = implode(",", $_POST['produk']);
    // cacah bulan 
    $jam    = $_POST['jam'];
    $bulan = substr($transaction_date, 5, 2);
    $tahun = substr($transaction_date, 0, 4);
    $conn = mysqli_connect('localhost', 'root', '', 'dm_apriori');

    // insert kedb 
    $inputTranksaksi = mysqli_query($conn, "INSERT INTO transaksi (transaction_date,produk,id_bulan,tahun,jam)
                                                    VALUES('$transaction_date','$data','$bulan','$tahun','$jam') ");
    if ($inputTranksaksi) {
        $pesan = "<div class='alert alert-primary' role='alert'>
                        Berhasil Input Data
                        </div>";
    } else {
        $pesan = "<div class='alert alert-danger' role='alert'>
                        Gagal Input Data!
                      </div>";
    }
}


if (isset($_POST['delete'])) {
    $sql = "TRUNCATE transaksi";
    $db_object->db_query($sql);
?>
    <script>
        location.replace("?menu=data_transaksi&pesan_success=Data transaksi berhasil dihapus");
    </script>
<?php
}

if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $sql = "DELETE FROM transaksi WHERE id='$id'";
    $db_object->db_query($sql);
    if ($sql) {
        echo "<script> location.replace('?menu=data_transaksi&pesan_success=Data transaksi berhasil dihapus'); </script>";
    }
}

$sql = "SELECT
        *
        FROM
         transaksi";
$query = $db_object->db_query($sql);
?>


<div class="modal fade" id="modalupload" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form method="post" enctype="multipart/form-data" action="">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle"><i class="fas fa-fw fa-upload"></i> </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Upload</span>
                        </div>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" name="file_data_transaksi" id="inputGroupFile01" required />
                            <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-dismiss="modal"><i class="fas fa-fw fa-times"></i> Keluar</button>
                    <button name="submit" type="submit" class="btn btn-success"><i class="fas fa-fw fa-upload"></i> Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modaladd" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form method="post" enctype="multipart/form-data" action="">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle"><i class="fas fa-fw fa-plus"></i> Tambah Data Penjualan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="col-12">
                        <label for="" class="font-weight-bold">Tanggal Transaksi Penjualan</label>
                        <div class="form-group">
                            <input type="date" name="transaction_date" value="<?php echo $tanggalSekarang; ?>" class="form-control">
                        </div>
                    </div>
                    <div class="col-12">
                        <label for="">
                            <span class="iconify" data-icon="ci:list-plus"></span>
                            Pilihan Menu
                        </label>
                        <select name="produk[]" id="multiple-optgroups" multiple>
                            <?php
                            $label = ['satuan', 'kiloan','bungkus'];
                            foreach ($label as $l) {
                                echo '<optgroup label="' . $l . '">';
                                $dataOne = tableId('tbl_produk', 'jenis', $l);
                                while ($rowOne = mysqli_fetch_array($dataOne)) {
                                    echo '<option value="' . $rowOne['produk'] . '">' . $rowOne['produk'] . '</option>';
                                }
                                echo '</optgroup>';
                            }
                            ?>
                        </select>
                    </div>


                    <div class="col-12">
                        <label for="">Jam</label>
                        <input type="time" name="jam" value="<?php echo date("h:i:s"); ?>" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-dismiss="modal"><i class="fas fa-fw fa-times"></i> Keluar</button>
                    <button onclick="return confirm('Apakah Anda yakin Menginput data Ini ?')" name="simpan" type="submit" class="btn btn-success"><i class="fas fa-fw fa-save"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>



<div class="card shadow mb-4">
    <!-- /.card-header -->
    <div class="card-header py-3 d-sm-flex align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-danger"><i class="fa fa-table"></i> Daftar Data Penjualan</h6>

        <form method="post" enctype="multipart/form-data" action="">
            <?php echo $deleteAll; ?>
        </form>
    </div>

    <div class="card-body">
        <?php
        echo $pesan;
        if (!empty($pesan_error)) {
            display_error($pesan_error);
        }
        if (!empty($pesan_success)) {
            display_success($pesan_success);
        }
        ?>
        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
            <thead class="bg-info text-white">
                <tr align="center">
                    <th>No</th>
                    <th>Tanggal Transaksi Penjualan</th>
                    <th>Produk</th>
                    <th>Jam</th>
                    <th width="15%">Aksi</th>
                </tr>
            </thead>
            <?php
            $no = 1;
            while ($row = $db_object->db_fetch_array($query)) {
                if ($_SESSION['apriori_level'] == "1") {
                    $edit = ' <a href="?menu=edit_transaksi&id=' . $row['id'] . '" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i></a>';
                    $hapus = '<a href="?menu=data_transaksi&hapus=' . $row['id'] . '" class="btn btn-danger btn-sm" onclick=\'return confirm ("Apakah anda yakin untuk meghapus data ini")\' class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>';
                } else {
                    $edit = '';
                    $hapus = '';
                }
            ?>
                <tr>
                    <td class='text-center'> <?= $no ?></td>
                    <td class='text-center'> <?= format_date2($row['transaction_date']) ?></td>
                    <td> <?= $row['produk'] ?></td>
                    <td> <?= $row['jam'] ?></td>
                    <td class='text-center'>
                        <div class='btn-group' role='group'>
                            <?php echo $edit . "" . $hapus; ?>
                        </div>
                    </td>
                </tr>

            <?php
                $no++;
            }
            ?>
        </table>
    </div>
</div>


<?php
function get_produk_to_in($produk)
{
    $ex = explode(",", $produk);
    //$temp = "";
    for ($i = 0; $i < count($ex); $i++) {

        $jml_key = array_keys($ex, $ex[$i]);
        if (count($jml_key) > 1) {
            unset($ex[$i]);
        }

        //$temp = $ex[$i];
    }
    return implode(",", $ex);
}

?>