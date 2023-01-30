<?php
// echo '<!-- slim select -->
//  <script src="https://cdnjs.cloudflare.com/ajax/libs/slim-select/1.27.0/slimselect.min.js"></script>
//  <link href="https://cdnjs.cloudflare.com/ajax/libs/slim-select/1.27.0/slimselect.min.css" rel="stylesheet">';
echo $_GET['p'] . '' . "<br>";
echo '<label for="">Barang</label>
<select name="produk[]" id="multiple-one" multiple>';
$conn = mysqli_connect('localhost', 'root', '', 'dm_apriori');
$data = mysqli_query($conn, "SELECT * FROM `tbl_produk`");
$no = 1;
while ($row = mysqli_fetch_array($data)) {
    echo '<option value="' . $row['produk'] . '">' . $row['produk'] . '</option>';
}
echo '</select>';
