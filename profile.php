<div class="card shadow mb-4">
    <!-- /.card-header -->
    <div class="card-header py-3 d-sm-flex align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-danger"><i class="fa fa-table"></i> Profile Toko</h6>
        <!-- Button trigger modal -->
    </div>
    <div class="card-body">
        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
            <thead class="bg-info text-white">
                <tr>
                    <th>No</th>
                    <th>Nama Toko</th>
                    <th>No Hp</th>
                    <th>Alamat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $conn = mysqli_connect('localhost', 'root', '', 'dm_apriori');
                $data = mysqli_query($conn, "SELECT * FROM `toko` ");
                $no = 1;
                while ($row = mysqli_fetch_array($data)) {
                    echo '<tr>
                        <td>' . $no . '</td>
                        <td>' . $row['nama'] . '</td>
                        <td>' . $row['noHp'] . '</td>
                        <td>' . $row['alamat'] . '</td>
                        <td><a href="index.php?menu=editProfile&&id=' . $row['id_toko'] . '" class="btn btn-success">Edit</a></td>
                    </tr>';
                    $no++;
                }
                ?>
            </tbody>
        </table>
    </div>
</div>