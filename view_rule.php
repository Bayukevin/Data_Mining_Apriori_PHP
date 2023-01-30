<?php
//session_start();
// if (!isset($_SESSION['apriori_toko_id'])) {
//     header("location:index.php?menu=forbidden");
// }

include_once "database.php";
include_once "fungsi.php";
include_once "mining.php";
include_once "display_mining.php";
?>


<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-fw fa-coins"></i> Data Hasil</h1>
	
	<a href="?menu=hasil" class="btn btn-secondary"><i class="fa fa-arrow-circle-left"></i> Kembali</a>
</div>


<?php
//object database class
$db_object = new database();

$pesan_error = $pesan_success = "";
if(isset($_GET['pesan_error'])){
    $pesan_error = $_GET['pesan_error'];
}
if(isset($_GET['pesan_success'])){
    $pesan_success = $_GET['pesan_success'];
}

if (isset($_POST['submit'])) {
    $can_process = true;
    if (empty($_POST['min_support']) || empty($_POST['min_confidence'])) {
        $can_process = false;
        ?>
        <script> location.replace("?menu=view_rule&pesan_error=Min Support dan Min Confidence harus diisi");</script>
        <?php
    }
    if(!is_numeric($_POST['min_support']) || !is_numeric($_POST['min_confidence'])){
        $can_process = false;
        ?>
        <script> location.replace("?menu=view_rule&pesan_error=Min Support dan Min Confidence harus diisi angka");</script>
        <?php
    }

    if($can_process){
        $id_process = $_POST['id_process'];

        $tgl = explode(" - ", $_POST['range_tanggal']);
        $start = format_date($tgl[0]);
        $end = format_date($tgl[1]);

        echo "Min Support Absolut: " . $_POST['min_support'];
        echo "<br>";
        $sql = "SELECT COUNT(*) FROM transaksi 
        WHERE transaction_date BETWEEN '$start' AND '$end' ";
        $res = $db_object->db_query($sql);
        $num = $db_object->db_fetch_array($res);
        $minSupportRelatif = ($_POST['min_support']/$num[0]) * 100;
        echo "Min Support Relatif: " . $minSupportRelatif;
        echo "<br>";
        echo "Min Confidence: " . $_POST['min_confidence'];
        echo "<br>";
        echo "Start Date: " . $_POST['range_tanggal'];
        echo "<br>";

        //delete hitungan untuk id_process
        reset_hitungan($db_object, $id_process);

        //update log process
        $field = array(
                        "start_date"=>$start,
                        "end_date"=>$end,
                        "min_support"=>$_POST['min_support'],
                        "min_confidence"=>$_POST['min_confidence']
                    );
        $where = array(
                        "id"=>$id_process
                    );
        $query = $db_object->update_record("process_log", $field, $where);

        $result = mining_process($db_object, $_POST['min_support'], $_POST['min_confidence'],
                $start, $end, $id_process);
        if ($result) {
            display_success("Proses mining selesai");
        } else {
            display_error("Gagal mendapatkan aturan asosiasi");
        }

        display_process_hasil_mining($db_object, $id_process);
    }        
} 
else{
    $id_process = 0;
    if(isset($_GET['id_process'])){
        $id_process = $_GET['id_process'];
    }
    $sql = "SELECT
            conf.*, log.start_date, log.end_date
            FROM
             confidence conf, process_log log
            WHERE conf.id_process = '$id_process' "
            . " AND conf.id_process = log.id "
            . " AND conf.from_itemset=3 "
            ;//. " ORDER BY conf.lolos DESC";
    //        echo $sql;
    $query=$db_object->db_query($sql);
    $jumlah=$db_object->db_num_rows($query);


    $sql1 = "SELECT
            conf.*, log.start_date, log.end_date
            FROM
             confidence conf, process_log log
            WHERE conf.id_process = '$id_process' "
            . " AND conf.id_process = log.id "
            . " AND conf.from_itemset=2 "
            ;//. " ORDER BY conf.lolos DESC";
    //        echo $sql;
    $query1=$db_object->db_query($sql1);
    $jumlah1=$db_object->db_num_rows($query1);

    $sql_log = "SELECT * FROM process_log
    WHERE id = ".$id_process;
    $res_log = $db_object->db_query($sql_log);
    $row_log = $db_object->db_fetch_array($res_log);
	$data_confidence = array();
            ?>
            <div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-danger"><i class="fa fa-table"></i> Confidence Dari Itemset 3</h6>
    </div>

    <div class="card-body">
    
    <table class="table table-bordered" width="100%" cellspacing="0">
            <thead class="bg-danger text-white">
                <tr align="center">
                <th>No</th>
                <th>X => Y</th>
                <th>Support X U Y</th>
                <th>Support X </th>
                <th>Confidence</th>
                <th>Keterangan</th>
                </tr>
				</thead>
                <?php
                    $conn = mysqli_connect('localhost', 'root', '', 'dm_apriori');
                    $data = mysqli_query($conn, $sql);
                    $no=1;
                    while($row= mysqli_fetch_array($data)){
                        
                            echo "<tr>";
                            echo "<td class='text-center'>".$no."</td>";
                            echo "<td>".$row['kombinasi1']." => ".$row['kombinasi2']."</td>";
                            echo "<td class='text-center'>".price_format($row['support_xUy'])."</td>";
                            echo "<td class='text-center'>".price_format($row['support_x'])."</td>";
                            echo "<td class='text-center'>".price_format($row['confidence'])."</td>";
                            $keterangan = ($row['confidence'] <= $row['min_confidence'])?"<span class='badge badge-danger'>Tidak Lolos</span>":"<span class='badge badge-success'>Lolos</span>";
                            echo "<td class='text-center'>".$keterangan."</td>";
                        echo "</tr>";
                        $no++;
                        if($row['lolos']==1){
                        $data_confidence[] = $row;
                        }
                    }
                    ?>
            </table></div></div>
            
            <div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-danger"><i class="fa fa-table"></i> Confidence Dari Itemset 2</h6>
    </div>

    <div class="card-body">
    
    <table class="table table-bordered" width="100%" cellspacing="0">
            <thead class="bg-danger text-white">
                <tr align="center">
                <th>No</th>
                <th>X => Y</th>
                <th>Support X U Y</th>
                <th>Support X </th>
                <th>Confidence</th>
                <th>Keterangan</th>
                </tr></thead>
                <?php
                    $conn = mysqli_connect('localhost', 'root', '', 'dm_apriori');
                    $data = mysqli_query($conn, $sql1);
                    $no=1;
                    while($row= mysqli_fetch_array($data)){
                            echo "<tr>";
                            echo "<td class='text-center'>".$no."</td>";
                            echo "<td>".$row['kombinasi1']." => ".$row['kombinasi2']."</td>";
                            echo "<td class='text-center'>".price_format($row['support_xUy'])."</td>";
                            echo "<td class='text-center'>".price_format($row['support_x'])."</td>";
                            echo "<td class='text-center'>".price_format($row['confidence'])."</td>";
                            $keterangan = ($row['confidence'] <= $row['min_confidence'])?"<span class='badge badge-danger'>Tidak Lolos</span>":"<span class='badge badge-success'>Lolos</span>";
                            echo "<td class='text-center'>".$keterangan."</td>";
                        echo "</tr>";
                        $no++;
                        //if($row['confidence']>=$row['min_cofidence']){
                        if($row['lolos']==1){
                        $data_confidence[] = $row;
                        }
                    }
                    ?>
            </table></div></div>
			
			
			
            
			
			<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-danger"><i class="fa fa-table"></i> Rule Asosiasi</h6>
    </div>

    <div class="card-body">
    
    <table class="table table-bordered" width="100%" cellspacing="0">
            <thead class="bg-danger text-white">
                <tr align="center">
                <th>No</th>
                <th>X => Y</th>
                <th>Confidence</th>
                <th>Nilai Uji lift</th>
                <th>Korelasi rule</th>
                <!--<th></th>-->
                </tr></thead>
                <?php
                    $no=1;
                    //while($row=$db_object->db_fetch_array($query)){
                    foreach($data_confidence as $key => $val){
                        echo "<tr>";
                        echo "<td class='text-center'>" . $no . "</td>";
                        echo "<td>" . $val['kombinasi1']." => ".$val['kombinasi2'] . "</td>";
                        echo "<td class='text-center'>" . price_format($val['confidence']) . "</td>";
                        echo "<td class='text-center'>" . price_format($val['nilai_uji_lift']) . "</td>";
                        echo "<td class='text-center'>" . ($val['korelasi_rule']) . "</td>";
                        echo "</tr>";
                        $no++;
                    }
                    ?>
            </table></div></div>
			
			
			<div class="card shadow mb-4">
    
	<div class="card-header py-3 d-sm-flex align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-danger"><i class="fa fa-table"></i> Hasil Analisa</h6>
		
		<a href="export/CLP.php?id_process=<?php echo $id_process; ?>" class="btn btn-success" target="blank">
                <i class="fa fa-print"></i>
                Print
        </a>
    </div>

    <div class="card-body">
    
    <table class="table table-bordered" width="100%" cellspacing="0">
	<thead class="bg-danger text-white">
                <tr align="center">
                <th>No</th>
                <th>Keterangan</th>
				</tr></thead>
                <?php
                $no=1;
                //while($row=$db_object->db_fetch_array($query)){
                foreach($data_confidence as $key => $val){
                    if($val['lolos']==1){
                        echo "<tr>";
                        echo "<td width='5%' class='text-center'>".$no."</td>";
						echo "<td>Jika konsumen membeli <b>".$val['kombinasi1']
                                ."</b>, maka konsumen juga akan membeli <b>".$val['kombinasi2']."</b></td>";
                        echo "</tr>";
                    }
                    $no++;
                }
                ?>
            </table></div></div>
            
            <?php
            //query itemset 1
            $sql1 = "SELECT
                    *
                    FROM
                     itemset1 
                    WHERE id_process = '$id_process' "
                    . " ORDER BY lolos DESC";
            $query1=$db_object->db_query($sql1);
            $jumlah1=$db_object->db_num_rows($query1);
            $itemset1 = $jumlahItemset1 = $supportItemset1 = array();
            ?>
            
			
			<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-danger"><i class="fa fa-table"></i> Perhitungan Itemset 1</h6>
    </div>

    <div class="card-body">
    
    <table class="table table-bordered" width="100%" cellspacing="0">
            <thead class="bg-danger text-white">
                <tr align="center">
                <th>No</th>
                <th>Item 1</th>
                <th>Jumlah</th>
                <th>Support</th>
                <th>Keterangan</th>
                </tr></thead>
                <?php
                $conn = mysqli_connect('localhost', 'root', '', 'dm_apriori');
                $data = mysqli_query($conn, $sql2);
                $no=1;
                    while($row= mysqli_fetch_array($data)){
                            echo "<tr>";
                            echo "<td class='text-center'>".$no."</td>";
                            echo "<td>".$row1['atribut']."</td>";
                            echo "<td class='text-center'>".$row1['jumlah']."</td>";
                            echo "<td class='text-center'>".price_format($row1['support'])."</td>";
                            echo "<td class='text-center'>".($row1['lolos']==1?"<span class='badge badge-success'>Lolos</span>":"<span class='badge badge-danger'>Tidak Lolos</span>")."</td>";
                        echo "</tr>";
                        $no++;
                        if($row1['lolos']==1){
                            $itemset1[] = $row1['atribut'];//item yg lolos itemset1
                            $jumlahItemset1[] = $row1['jumlah'];
                            $supportItemset1[] = price_format($row1['support']);
                        }
                    }
                    ?>
            </table></div></div>
			
			
            <?php      
            //display itemset yg lolos
            echo "
			<div class='card shadow mb-4'>
    <div class='card-header py-3'>
        <h6 class='m-0 font-weight-bold text-danger'><i class='fa fa-table'></i> Itemset 1 Yang Lolos</h6>
    </div>

    <div class='card-body'>
    
    <table class='table table-bordered' width='100%' cellspacing='0'>
            <thead class='bg-danger text-white'>
                <tr align='center'>
                        <th>No</th>
                        <th>Item</th>
                        <th>Jumlah</th>
                        <th>Suppport</th>
                    </tr></thead>";
            $no=1;
            foreach ($itemset1 as $key => $value) {
                echo "<tr>";
                echo "<td class='text-center'>" . $no . "</td>";
                echo "<td>" . $value . "</td>";
                echo "<td class='text-center'>" . $jumlahItemset1[$key] . "</td>";
                echo "<td class='text-center'>" . $supportItemset1[$key] . "</td>";
                echo "</tr>";
                $no++;
            }
            echo "</table></div></div>";
            ?>
            
            
            <?php
            //query itemset 2
            $sql2 = "SELECT
                    *
                    FROM
                     itemset2 
                    WHERE id_process = '$id_process' "
                    . " ORDER BY lolos DESC";
            $query2=$db_object->db_query($sql2);
            $jumlah2=$db_object->db_num_rows($query2);
            $itemset2_var1 = $itemset2_var2 = $jumlahItemset2 = $supportItemset2 = array();
            ?>
            <div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-danger"><i class="fa fa-table"></i> Perhitungan Itemset 2</h6>
    </div>

    <div class="card-body">
    
    <table class="table table-bordered" width="100%" cellspacing="0">
            <thead class="bg-danger text-white">
                <tr align="center">
                <th>No</th>
                <th>Item 1</th>
                <th>Item 2</th>
                <th>Jumlah</th>
                <th>Support</th>
                <th>Keterangan</th>
                </tr></thead>
                <?php
                $conn = mysqli_connect('localhost', 'root', '', 'dm_apriori');
                $data = mysqli_query($conn, $sql3);
                $no=1;
                while($row= mysqli_fetch_array($data)){
                        echo "<tr>";
                        echo "<td class='text-center'>".$no."</td>";
                        echo "<td>".$row2['atribut1']."</td>";
                        echo "<td>".$row2['atribut2']."</td>";
                        echo "<td class='text-center'>".$row2['jumlah']."</td>";
                        echo "<td class='text-center'>".price_format($row2['support'])."</td>";
                        echo "<td class='text-center'>".($row2['lolos']==1?"<span class='badge badge-success'>Lolos</span>":"<span class='badge badge-danger'>Tidak Lolos</span>")."</td>";
                    echo "</tr>";
                    $no++;
                    if($row2['lolos']==1){
                        $itemset2_var1[] = $row2['atribut1'];
                        $itemset2_var2[] = $row2['atribut2'];
                        $jumlahItemset2[] = $row2['jumlah'];
                        $supportItemset2[] = price_format($row2['support']);
                    }
                }
                ?>
            </table></div></div>
            
            <?php
            //display itemset yg lolos
            echo "<div class='card shadow mb-4'>
    <div class='card-header py-3'>
        <h6 class='m-0 font-weight-bold text-danger'><i class='fa fa-table'></i> Itemset 2 Yang Lolos</h6>
    </div>

    <div class='card-body'>
    
    <table class='table table-bordered' width='100%' cellspacing='0'>
            <thead class='bg-danger text-white'>
                <tr align='center'>
                        <th>No</th>
                        <th>Item 1</th>
                        <th>Item 2</th>
                        <th>Jumlah</th>
                        <th>Suppport</th>
                    </tr></thead>";
            $no=1;
            foreach ($itemset2_var1 as $key => $value) {
                echo "<tr>";
                echo "<td class='text-center'>" . $no . "</td>";
                echo "<td>" . $value . "</td>";
                echo "<td>" . $itemset2_var2[$key] . "</td>";
                echo "<td class='text-center'>" . $jumlahItemset2[$key] . "</td>";
                echo "<td class='text-center'>" . $supportItemset2[$key] . "</td>";
                echo "</tr>";
                $no++;
            }
            echo "</table></div></div>";
            ?>
            
           <?php
            //query itemset 3
            $sql3 = "SELECT
                    *
                    FROM
                     itemset3 
                    WHERE id_process = '$id_process' "
                    . " ORDER BY lolos DESC";
            $query3=$db_object->db_query($sql3);
            $jumlah3=$db_object->db_num_rows($query3);
            $itemset3_var1 = $itemset3_var2 = $itemset3_var3 = $jumlahItemset3 = $supportItemset3 = array();
            ?>
            
			<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-danger"><i class="fa fa-table"></i> Perhitungan Itemset 3</h6>
    </div>

    <div class="card-body">
    
    <table class="table table-bordered" width="100%" cellspacing="0">
            <thead class="bg-danger text-white">
                <tr align="center">
                <th>No</th>
                <th>Item 1</th>
                <th>Item 2</th>
                <th>Item 3</th>
                <th>Jumlah</th>
                <th>Support</th>
                <th>Keterangan</th>
                </tr></thead>
                <?php
                $conn = mysqli_connect('localhost', 'root', '', 'dm_apriori');
                $data = mysqli_query($conn, $sql_que);
                $no=1;
                    while($row= mysqli_fetch_array($data)){
                            echo "<tr>";
                            echo "<td class='text-center'>".$no."</td>";
                            echo "<td>".$row3['atribut1']."</td>";
                            echo "<td>".$row3['atribut2']."</td>";
                            echo "<td>".$row3['atribut3']."</td>";
                            echo "<td class='text-center'>".$row3['jumlah']."</td>";
                            echo "<td class='text-center'>".price_format($row3['support'])."</td>";
                            echo "<td class='text-center'>".($row3['lolos']==1?"<span class='badge badge-success'>Lolos</span>":"<span class='badge badge-danger'>Tidak Lolos</span>")."</td>";
                        echo "</tr>";
                        $no++;
                        if($row3['lolos']==1){
                            $itemset3_var1[] = $row3['atribut1'];
                            $itemset3_var2[] = $row3['atribut2'];
                            $itemset3_var3[] = $row3['atribut3'];
                            $jumlahItemset3[] = $row3['jumlah'];
                            $supportItemset3[] = $row3['support'];
                        }
                    }
                    ?>
            </table></div></div>
            
            <?php
            //display itemset yg lolos
            echo "<div class='card shadow mb-4'>
    <div class='card-header py-3'>
        <h6 class='m-0 font-weight-bold text-danger'><i class='fa fa-table'></i> Itemset 3 Yang Lolos</h6>
    </div>

    <div class='card-body'>
    
    <table class='table table-bordered' width='100%' cellspacing='0'>
            <thead class='bg-danger text-white'>
                <tr align='center'>
                        <th>No</th>
                        <th>Item 1</th>
                        <th>Item 2</th>
                        <th>Item 3</th>
                        <th>Jumlah</th>
                        <th>Suppport</th>
                    </tr></thead>";
            $no=1;
            foreach ($itemset3_var1 as $key => $value) {
                echo "<tr>";
                echo "<td class='text-center'>" . $no . "</td>";
                echo "<td>" . $value . "</td>";
                echo "<td>" . $itemset3_var2[$key] . "</td>";
                echo "<td>" . $itemset3_var3[$key] . "</td>";
                echo "<td class='text-center'>" . $jumlahItemset3[$key] . "</td>";
                echo "<td class='text-center'>" . $supportItemset3[$key] . "</td>";
                echo "</tr>";
                $no++;
            }
            echo "</table></div></div>";
            ?>
            
            
            <?php
            //}
            ?>
        
<?php
}
?>