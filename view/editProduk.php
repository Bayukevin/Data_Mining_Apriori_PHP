<?php
$conn = mysqli_connect('localhost', 'root', '', 'dm_apriori');
if ($_POST['idx']) {
    $id = $_POST['idx'];
    $data = mysqli_query($conn, "SELECT * FROM `tbl_produk` WHERE id_produk='$id' ");
    $row = mysqli_fetch_array($data);
    echo '<input type="hidden" name="id_produk" value="' . $id . '">';
    echo '<div class="form-group">
        <label for="">Produk</label>
        <input type="text" name="produk" value="' . $row['produk'] . '" id="" class="form-control" required />
    </div>
    <div class="form-group">
        <label for="">Harga</label>
        <input type="number" name="harga" value="' . $row['harga'] . '" class="form-control">
    </div>
    <div class="form-group">
        <label for="">Jenis</label>
        <select name="jenis" id="" class="form-control">
        <option value="' . $row['jenis'] . '">' . $row['jenis'] . '</option>';
    $jenis = ['satuan', 'kiloan', 'bungkus'];
    foreach ($jenis as $j) {
        echo '<option value="' . $j . '">' . $j . '</option>';
    }
    echo '</select>
    </div>';
    // echo '<img src="file/' . $row['foto'] . '" width="180px" height="180px" alt="">';
}
// <div class="form-group">
    //     <label for="">File</label>
    //     <input type="file" name="file" class="form-control" >
    // </div>
