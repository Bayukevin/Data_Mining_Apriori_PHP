<?php 
session_start();
include "Data_Mining\lib\koneksi.php";
include "header.php"; 
include "sidebar.php";

?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-fw fa-medkit"></i> Data Obat</h1>
</div>
 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">

 <!-- Main content -->
    <section class="content">
    	  <div class="row">
<div class="col-md-12">
           <div class="box">
           <div class="box-header">
                      <div class="form-group">
                 <form method="post" action="Data_Mining\tambah_buku.php">  
                <div class="form-group">
                    <button name="tambah" type="submit"  class="btn btn-success">
                        <i class="fa fa-plus"></i> Tambah Data Obat
                    </button>                    
                </div>
                </form>
                      </div>
            </form>
</div>
            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th style="width: 10px;">No.</th>
                  <th style="width: 230px;">Nama Obat</th>
                  <th style="width: 130px;">Jenis</th>
                  <th style="width: 130px;">Golongan</th>
                  <th style="width: 130px;">Kategori</th>
                  <th style="width: 100px;">Aksi</th>
                </tr>
                </thead>
               <tbody>
                <?php
                $noUrut = 0;
              $sql = mysqli_query($koneksi,"SELECT 
              buku.id_buku, buku.judul_buku, kategori.nama_kategori, penulis.nama_penulis, penerbit.nama_penerbit
                 FROM buku
                  JOIN kategori ON buku.id_kategori = kategori.id_kategori
                  JOIN penulis ON buku.id_penulis = penulis.id_penulis
                    JOIN penerbit ON buku.id_penerbit = penerbit.id_penerbit");
              while ($data = mysqli_fetch_array($sql))
              {
              $noUrut++; // increment untuk menampilkan nomor urut
              echo "<td>".$noUrut."</td>"
              ?>
                <td><?php echo $data['judul_buku'];?></td>
                <td><?php echo $data['nama_kategori'];?></td>
                <td><?php echo $data['nama_penulis'];?></td>
                <td><?php echo $data['nama_penerbit'];?></td>
                <td><a href="<?php echo $web_url; ?>edit_buku.php?menu=data_buku&id_buku=<?php echo $data['id_buku'];?>">
                <button class="btn btn-warning">Edit Data</button>
                <a href="<?php echo $web_url; ?>proses_hapus_buku.php?id_buku=<?php echo $data['id_buku'];?>" onClick="return confirm('Anda yakin ingin menghapus data ini?')">
                <button class="btn btn-danger">Hapus</button></a></td>

                </tr>

                <?php } ?>


              </tbody>
              </table>
            </div>
            
            <!-- /.box-body -->
          </div>


          <!-- /.box -->
</div>

    	  </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
 
<?php
include 'footer.php'
?>
