<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">

    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
    <title>HighChart</title>
</head>

<body>

    <?php
    include 'koneksi.php';
    $sql = mysqli_query($koneksi, "SELECT MONTHNAME(STR_TO_DATE(`StartMeasurement`,'%Y-%m-%d')) as bulan, count(case OLA_RESP_TIME when 'Meet' then 1 else null end) as total_within_sla, count(case OLA_RESP_TIME when 'Not Meet' then 1 else null end) as total_out_of_sla, count(OLA_RESP_TIME) as trend_ticket, count(case OLA_CEM_TIME_CHECK when 'Meet' then 1 else null end) as ola_cem_meet, count(case OLA_CEM_TIME_CHECK when 'Not Meet' then 1 else null end) as ola_cem_notmeet, count(case OLA_NETWORK_CHECK when 'Meet' then 1 else null end) as ola_no_meet, count(case OLA_NETWORK_CHECK when 'Not Meet' then 1 else null end) as ola_no_notmeet, count(case OLA_CCM_CHECK when 'Meet' then 1 else null end) as ola_ccm_meet, count(case OLA_CCM_CHECK when 'Not Meet' then 1 else null end) as ola_ccm_notmeet from remedy_cc  GROUP BY MONTHNAME(STR_TO_DATE(`StartMeasurement`,'%Y-%m-%d')) ORDER BY MONTH(STR_TO_DATE(`StartMeasurement`,'%Y-%m-%d')) asc");
    ?>


    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <figure class="highcharts-figure">
                    <?php
                    foreach ($sql as $row) :
                        $bulan_sla[] = $row['bulan'];
                        $total_within_sla[] = $row['total_within_sla'];
                        $total_out_of_sla[] = $row['total_out_of_sla'];
                        $trend_ticket[] = $row['trend_ticket'];

                    // echo json_encode($total_within_sla);
                    endforeach;
                    ?>

                    <div class="card p-2">
                        <div id="container"></div>
                    </div>

                </figure>
            </div>
        </div>
    </div>

    <!-- JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>


    <script>
        Highcharts.chart('container', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'Trend Ticket And SLA'
            },
            xAxis: {
                categories: <?= json_encode($bulan_sla); ?>,
                crosshair: true,
                title: {
                    useHTML: true,
                    text: 'Bulan'
                }
            },
            yAxis: {
                title: {
                    useHTML: true,
                    text: 'Jumlah Tiket'
                }
            },
            tooltip: {
                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                    '<td style="padding:0"><b>{point.y:.1f}</b></td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                }
            },
            series: [{
                    name: 'Within SLA',
                    data: [
                        <?php
                        foreach ($sql as $row) {
                            $bulan_sla = $row['bulan'];
                            $total_within_sla = $row['total_within_sla'];
                            echo "['" . $bulan_sla . "'," . $total_within_sla . "], ";
                        }

                        ?>
                    ]

                },

                {
                    name: 'Out Of SLA',
                    data: [
                        <?php
                        foreach ($sql as $row) {
                            $bulan_sla = $row['bulan'];
                            $total_out_of_sla = $row['total_out_of_sla'];
                            echo "['" . $bulan_sla . "'," . $total_out_of_sla . "], ";
                        }

                        ?>
                    ]

                },

            ]
        });
    </script>
</body>

</html>