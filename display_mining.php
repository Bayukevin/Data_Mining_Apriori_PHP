<script src="js/jquery.min.js" type="text/javascript"></script>
<script src="js/highcharts.js"></script>
<?php

function display_process_hasil_mining($db_object, $id_process)
{

    $sql1 = "SELECT * FROM confidence "
        . " WHERE id_process = " . $id_process
        . " AND from_itemset=3 "; //. " ORDER BY lolos DESC";
    $query1 = $db_object->db_query($sql1);
?>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-danger"><i class="fa fa-table"></i> Confidence dari itemset 3</h6>
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
                $no = 1;
                $data_confidence = array();
                while ($row = $db_object->db_fetch_array($query1)) {
                    echo "<tr>";
                    echo "<td class='text-center'>" . $no . "</td>";
                    echo "<td>" . $row['kombinasi1'] . " => " . $row['kombinasi2'] . "</td>";
                    echo "<td class='text-center'>" . price_format($row['support_xUy']) . "</td>";
                    echo "<td class='text-center'>" . price_format($row['support_x']) . "</td>";
                    echo "<td class='text-center'>" . price_format($row['confidence']) . "</td>";
                    $keterangan = ($row['confidence'] <= $row['min_confidence']) ? "<span class='badge badge-danger'>Tidak Lolos</span>" : "<span class='badge badge-success'>Lolos</span>";
                    echo "<td class='text-center'>" . $keterangan . "</td>";
                    echo "</tr>";
                    $no++;
                    if ($row['lolos'] == 1) {
                        $data_confidence[] = $row;
                    }
                }
                ?>
            </table>
        </div>
    </div>


    <?php
    $sql1 = "SELECT * FROM confidence "
        . " WHERE id_process = " . $id_process
        . " AND from_itemset=2 "; //. " ORDER BY lolos DESC";
    $query1 = $db_object->db_query($sql1);
    ?>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-danger"><i class="fa fa-table"></i> Confidence dari itemset 2</h6>
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
                $no = 1;
                //$data_confidence = array();
                while ($row = $db_object->db_fetch_array($query1)) {
                    echo "<tr>";
                    echo "<td class='text-center'>" . $no . "</td>";
                    echo "<td>" . $row['kombinasi1'] . " => " . $row['kombinasi2'] . "</td>";
                    echo "<td class='text-center'>" . price_format($row['support_xUy']) . "</td>";
                    echo "<td class='text-center'>" . price_format($row['support_x']) . "</td>";
                    echo "<td class='text-center'>" . price_format($row['confidence']) . "</td>";
                    $keterangan = ($row['confidence'] <= $row['min_confidence']) ? "<span class='badge badge-danger'>Tidak Lolos</span>" : "<span class='badge badge-success'>Lolos</span>";
                    echo "<td class='text-center'>" . $keterangan . "</td>";
                    echo "</tr>";
                    $no++;
                    if ($row['lolos'] == 1) {
                        $data_confidence[] = $row;
                    }
                }
                ?>
            </table>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-danger"><i class="fa fa-table"></i> Rule Asosiasi yang terbentuk</h6>
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
                        <!-- <th></th> -->
                    </tr>
                </thead>
                <?php

                $no = 1;
                //while ($row1 = $db_object->db_fetch_array($query1)) {
                foreach ($data_confidence as $key => $val) {
                    //            $kom1 = explode(" , ", $row1['kombinasi1']);
                    //            $jika = implode(" Dan ", $kom1);
                    //            $kom2 = explode(" , ", $row1['kombinasi2']);
                    //            $maka = implode(" Dan ", $kom2);
                    echo "<tr>";
                    echo "<td class='text-center'>" . $no . "</td>";
                    echo "<td>" . $val['kombinasi1'] . " => " . $val['kombinasi2'] . "</td>";
                    echo "<td class='text-center'>" . price_format($val['confidence']) . "</td>";
                    echo "<td class='text-center'>" . price_format($val['nilai_uji_lift']) . "</td>";
                    echo "<td class='text-center'>" . ($val['korelasi_rule']) . "</td>";
                    //echo "<td>" . ($val['lolos'] == 1 ? "Lolos" : "Tidak Lolos") . "</td>";
                    echo "</tr>";
                    $no++;
                }
                ?>
            </table>
        </div>
    </div>
    <!-- grafik -->

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-danger"><i class="fa fa-chart"></i> Grafik Rule Asosiasi yang terbentuk</h6>
        </div>

        <div class="card-body">
            <div id="rules" style="min-width: 400px;height: 400px; margin: 0 auto"></div>
        </div>
    </div>

    <script>
        //2)script untuk membuat grafik, perhatikan setiap komentar agar paham
        $(function() {
            var chart;
            $(document).ready(function() {
                chart = new Highcharts.Chart({
                    chart: {
                        renderTo: 'rules', //letakan grafik di div id container
                        //Type grafik, anda bisa ganti menjadi area,bar,column dan bar
                        type: 'line',
                        marginRight: 130,
                        marginBottom: 25
                    },
                    title: {
                        text: 'Grafik Rule Asosiasi yang terbentuk',
                        x: -20 //center
                    },
                    subtitle: {
                        text: 'Grafik Multi Line',
                        x: -20
                    },
                    xAxis: { //X axis menampilkan data bulan 
                        categories: [<?php
                                        foreach ($data_confidence as $key => $val) {
                                            echo '"' . $val['kombinasi1'] . '' . "=>" . '' . $val['kombinasi2'] . '",';
                                        }
                                        ?>]
                    },
                    yAxis: {
                        title: { //label yAxis
                            text: 'Total penjualan'
                        },
                        plotLines: [{
                            value: 0,
                            width: 1,
                            color: '#808080' //warna dari grafik line
                        }]
                    },
                    tooltip: {
                        //fungsi tooltip, ini opsional, kegunaan dari fungsi ini 
                        //akan menampikan data di titik tertentu di grafik saat mouseover
                        formatter: function() {
                            return '<b>' + this.series.name + '</b><br/>' +
                                this.x + ': ' + this.y;
                        }
                    },
                    legend: {
                        layout: 'vertical',
                        align: 'right',
                        verticalAlign: 'top',
                        x: -10,
                        y: 100,
                        borderWidth: 0
                    },
                    //series adalah data yang akan dibuatkan grafiknya,

                    series: [{
                            name: 'Confidence',

                            data: [<?php
                                    foreach ($data_confidence as $key => $val) {
                                        echo price_format($val['confidence']) . ', ';
                                    }
                                    ?>]
                        },
                        {
                            name: 'Nilai Uji lift',

                            data: [<?php
                                    foreach ($data_confidence as $key => $val) {
                                        echo price_format($val['nilai_uji_lift']) . ', ';
                                    }
                                    ?>]
                        }
                    ]
                });
            });

        });
    </script>


<?php
}
?>