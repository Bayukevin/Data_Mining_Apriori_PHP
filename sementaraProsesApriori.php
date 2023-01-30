<?php
//session_start();
if (!isset($_SESSION['apriori_id'])) {
    header("location:index.php?menu=forbidden");
}

include_once "database.php";
include_once "fungsi.php";
include_once "mining.php";
include_once "display_mining.php";
?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-fw fa-sync"></i> Data Proses</h1>
</div>

<?php
//object database class
$db_object = new database();

$pesan_error = $pesan_success = "";
if (isset($_GET['pesan_error'])) {
    $pesan_error = $_GET['pesan_error'];
}
if (isset($_GET['pesan_success'])) {
    $pesan_success = $_GET['pesan_success'];
}

if (isset($_POST['submit'])) {
    $can_process = true;
    if (empty($_POST['min_support']) || empty($_POST['min_confidence'])) {
        $can_process = false;
?>
        <script>
            location.replace("?menu=proses_apriori&pesan_error=Min Support dan Min Confidence harus diisi");
        </script>
    <?php
    }
    if (!is_numeric($_POST['min_support']) || !is_numeric($_POST['min_confidence'])) {
        $can_process = false;
    ?>
        <script>
            location.replace("?menu=proses_apriori&pesan_error=Min Support dan Min Confidence harus diisi angka");
        </script>
    <?php
    }
    //  01/09/2016 - 30/09/2016

    if ($can_process) {
        $tgl = explode(" - ", $_POST['range_tanggal']);
        $start = format_date($tgl[0]);
        $end = format_date($tgl[1]);

        if (isset($_POST['id_process'])) {
            $id_process = $_POST['id_process'];
            //delete hitungan untuk id_process
            reset_hitungan($db_object, $id_process);

            //update log process
            $field = array(
                "start_date" => $start,
                "end_date" => $end,
                "min_support" => $_POST['min_support'],
                "min_confidence" => $_POST['min_confidence']
            );
            $where = array(
                "id" => $id_process
            );
            $query = $db_object->update_record("process_log", $field, $where);
        } else {
            //insert log process
            $field_value = array(
                "start_date" => $start,
                "end_date" => $end,
                "min_support" => $_POST['min_support'],
                "min_confidence" => $_POST['min_confidence']
            );
            $query = $db_object->insert_record("process_log", $field_value);
            $id_process = $db_object->db_insert_id();
        }
        //show form for update
    ?>

    <?php

        echo "<div class='card shadow mb-4'>
        <div class='card-header py-3'>
            <h6 class='m-0 font-weight-bold text-danger'><i class='fa fa-table'></i> Data Proses</h6>
        </div>
        <div class='card-body'>";
        echo "
		<table class='table table-bordered'>
		<tr>
		<th>
		Min Support Absolut</th><td>" . $_POST['min_support'];
        echo "</td></tr>";
        $sql = "SELECT COUNT(*) FROM transaksi 
        WHERE transaction_date BETWEEN '$start' AND '$end' ";
        $res = $db_object->db_query($sql);
        $num = $db_object->db_fetch_array($res);
        $minSupportRelatif = ($_POST['min_support'] / $num[0]) * 100;
        echo "<tr>
		<th>
		Min Support Relatif</th><td>" . $minSupportRelatif;
        echo "</td></tr>";
        echo "<tr>
		<th>Min Confidence</th><td>" . $_POST['min_confidence'];
        echo "</td></tr>";
        echo "<tr>
		<th>Start Date</th><td>" . $_POST['range_tanggal'];
        echo "</td></tr></table></div></div>";

        $result = mining_process(
            $db_object,
            $_POST['min_support'],
            $_POST['min_confidence'],
            $start,
            $end,
            $id_process
        );
        if ($result) {
            display_success("Proses mining selesai");
        } else {
            display_error("Gagal mendapatkan aturan asosiasi");
        }

        display_process_hasil_mining($db_object, $id_process);
    }
} else {
    $where = "ga gal";
    if (isset($_POST['range_tanggal'])) {
        $tgl = explode(" - ", $_POST['range_tanggal']);
        $start = format_date($tgl[0]);
        $end = format_date($tgl[1]);

        $where = " WHERE transaction_date "
            . " BETWEEN '$start' AND '$end'";
    }
    $sql = "SELECT
        *
        FROM
         transaksi " . $where;

    $query = $db_object->db_query($sql);
    $jumlah = $db_object->db_num_rows($query);
    ?>



    <form method="post" action="">
        <div class="card shadow mb-4">
            <!-- /.card-header -->
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-danger"><i class="fa fa-table"></i> Langkah 1: Cari Data Transaksi</h6>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-10">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id=""><i class="fa fa-calendar"></i></span>
                            </div>
                            <input type="text" class="form-control pull-right" name="range_tanggal" id="id-date-range-picker-1" required placeholder="Date range" value="<?php echo $_POST['range_tanggal']; ?>" />
                        </div>
                    </div>

                    <div class="col-2">
                        <button name="search_display" type="submit" class="btn btn-success w-100"><i class="fa fa-search"></i> Cari Data</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow mb-4">
            <!-- /.card-header -->
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-danger"><i class="fa fa-table"></i> Langkah 2: Input Nilai Support & Confidence</h6>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-5">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="">Min Support</span>
                            </div>
                            <input name="min_support" autocomplete="off" type="text" class="form-control" />
                        </div>
                    </div>
                    <div class="col-5">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="">Min Confidence</span>
                            </div>
                            <input name="min_confidence" autocomplete="off" type="text" class="form-control" />
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="form-group">
                            <button name="submit" type="submit" class="btn btn-success w-100"><i class="fa fa-sync"></i> Proses Data</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>



    <?php
    if (!empty($pesan_error)) {
        display_error($pesan_error);
    }
    if (!empty($pesan_success)) {
        display_success($pesan_success);
    }

    if ($jumlah == 0) {
    } else {
    ?>

        <div class="card shadow mb-4">
            <!-- /.card-header -->
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-danger"><i class="fa fa-table"></i> Data Transaksi</h6>
            </div>

            <div class="card-body">

                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead class="bg-danger text-white">
                        <tr align="center">
                            <th width="5%">No</th>
                            <th>Tanggal</th>
                            <th>Produk</th>
                        </tr>
                    </thead>
                    <?php
                    $no = 1;
                    while ($row = $db_object->db_fetch_array($query)) {
                        echo "<tr>";
                        echo "<td class='text-center'>" . $no . "</td>";
                        echo "<td class='text-center'>" . $row['transaction_date'] . "</td>";
                        echo "<td>" . $row['produk'] . "</td>";
                        echo "</tr>";
                        $no++;
                    }
                    ?>
                </table>
            </div>
        </div>
<?php
    }
}
?>