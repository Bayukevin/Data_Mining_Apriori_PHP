<div class="card shadow mb-4">
    <!-- /.card-header -->
    <div class="card-header py-3 d-sm-flex align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-danger"><i class="fa fa-table"></i> Update Profile Toko</h6>
        <!-- Button trigger modal -->
    </div>
    <?php
    $conn = mysqli_connect('localhost', 'root', '', 'dm_apriori');
    if (isset($_POST['editProfile'])) {
        $nama = $_POST['nama'];
        $noHp = $_POST['noHp'];
        $alamat = $_POST['alamat'];
        $update = mysqli_query($conn, "UPDATE `toko` SET `nama`='$nama',`noHp`='$noHp',`alamat`='$alamat'");
        if ($update) {
            echo "<script>
            alert('Berhasil Mengubah Data!!');document.location.href='index.php?menu=profile';
            </script>";
        } else {
            echo "<script>
            alert('Gagal Mengubah Data!!');document.location.href='index.php?menu=profile';
            </script>";
        }
    }
    $data = mysqli_query($conn, "SELECT * FROM `toko` ");
    $row = mysqli_fetch_array($data);
    ?>
    <form action="" method="POST">
        <div class="card-body">
            <div class="form-group">
                <label for="">Nama Toko</label>
                <input type="text" name="nama" class="form-control" value="<?php echo $row['nama']; ?>">
            </div>
            <div class="form-group">
                <label for="">No Hp</label>
                <input type="number" name="noHp" class="form-control" value="<?php echo $row['noHp']; ?>">
            </div>
            <div class="form-group">
                <label for="">Alamat</label>
                <textarea name="alamat" id="" cols="" rows="" class="form-control"><?php echo $row['alamat']; ?></textarea>
            </div>
            <button onclick="return confirm('Yakin Mengubah Data')" type="submit" name="editProfile" class="btn btn-success">Simpan</button>
        </div>
    </form>
</div>