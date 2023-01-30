<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Keterangan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <b> Pengertian Apriori : </b><br>
                        <div class="alert alert-warning" role="alert">
                            <p>
                                Data Mining : Definisi dan cara kerja Algoritma Apriori untuk pencarian association rule
                                <br>
                                Algoritma apriori adalah suatu metode untuk mencari pola hubungan antar satu atau lebih item dalam suatu dataset. Algoritma apriori banyak digunakan pada data transaksi atau biasa disebut market basket, misalnya sebuah swalayan memiliki market basket, dengan adanya algoritma apriori, pemilik swalayan dapat mengetahui pola pembelian seorang konsumen, jika seorang konsumen membeli item A , B, punya kemungkinan 50% dia akan membeli item C, pola ini sangat signifikan dengan adanya data transaksi selama ini.
                            </p>
                            <div class="alert alert-light" role="alert">
                                Konsep Apriori :
                                Itemset adalah sekumpulan item item dalam sebuah keranjang (Support)
                            </div>
                            K-itemset adalah itemset yang berisi K item, misalnya beras,telur,minyak adalah 3-itemset (Dinotasikan sebagai K-itemset)
                            Frequent support adalah k-itemset yang dimiliki oleh support dimana frequent k-itemset yang dimiliki diatas minimum support atau memenuhi minimum support (dinotasikan sebagai Fi).
                            Candidat itemset adalah frequent itemset yang dikombinasikan dari k-itemset sebelumnya (dinotasikan sebagi Ci).
                        </div>
                    </li>
                    <li class="list-group-item">
                        <b>
                            Cara Kerja apriori: </b><br>
                        <div class="alert alert-warning" role="alert">
                            <p>
                                Tentukan minimum support<br>
                                Iterasi 1 : hitung item-item dari support(transaksi yang memuat seluruh item) dengan men-scan database untuk 1-itemset, setelah 1-itemset didapatkan, dari 1-itemset apakah diatas minimum support, apabila telah memenuhi minimum support, 1-itemset tersebut akan menjadi pola frequent tinggi, <br>
                                Iterasi 2 : untuk mendapatkan 2-itemset, harus dilakukan kombinasi dari k-itemset sebelumnya, kemudian scan database lagi untuk hitung item-item yang memuat support. itemset yang memenuhi minimum support akan dipilih sebagai pola frequent tinggi dari kandidat <br>
                                Tetapkan nilai k-itemset dari support yang telah memenuhi minimum support dari k-itemset <br>
                                lakukan proses untuk iterasi selanjutnya hingga tidak ada lagi k-itemset yang memenuhi minimum support.
                            </p>
                        </div>
                    </li>
                    
                    </li>Beberapa istilah yang digunakan dalam algoritma apriori antara lain: </b><br>
                        <div class="alert alert-warning" role="alert">
                            <p>
                            a. Support (dukungan): probabilitas pelanggan membeli beberapa produk secara bersamaan dari seluruh transaksi. Support untuk aturan ³; !<¥ adalah probabilitas atribut atau kumpulan atribut X dan Y yang terjadi bersamaan. <br>
                            b. Confidence (tingkat kepercayaan) yaitu probabilitas kejadian beberapa produk dibeli bersamaan dimana salah satu produk sudah pasti dibeli. Contoh: jika ada n transaksi dimana X dibeli, dan ada m transaksi dimana X dan Y dibeli bersamaan, maka confidence dari aturan if X thenY adalah m/n. <br>
                            c. Minimum support yaitu parameter yang digunakan sebagai batasan frekuensi kejadian atau support count yang harus dipenuhi suatu kelompok data untuk dapat dijadikan aturan. <br>
                            d. Minimum confidence yaitu parameter yang mendefinisikan minimum level dari confidence yang harus dipenuhi oleh aturan yang berkualitas. <br>
                            e. Itemset yaitu kelompok produk. <br>
                            </p>
                        </div>
                    </li>

                    </li>Nilai Minimum </b><br>
                        <div class="alert alert-warning" role="alert">
                            <p>
                            Menurut Larose, kita bebas menentukan nilai minimum support dan minimum confidence sesuai kebutuhan. Sebagai contoh, bila ingin menemukan data-data yang memiliki hubungan asosiasi yang kuat, minimum support dan minimum confidence bisa diberi nilai yang tinggi.
                            Sebaliknya, bila ingin melihat banyaknya variasi data tanpa terlalu mempedulikan kuat atau tidaknya hubungan asosiasi antara datanya, nilai minimum-nya dapat diisi rendah.
                            </p>
                        </div>
                    </li>
                   


                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>