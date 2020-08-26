<section class="content">

    <div class="row">
        <div class="grafik col-md-12">
            <div class="card">
                <div class="card-header">
                
                </div>
                <div id="grafik" style="width:100%; height:480px;"></div>
            </div>
        </div>
    </div>

</section>

<script>
    var chart;
    var total=0;
    function tampil(){
            $.ajax({
                url: "<?php echo base_url('Dashboard/get_realtime') ; ?>",
                dataType:'json',
                success:function(result){
                    if (result.length>total){
                        total=result.length;
                        var i;
                        var suhu = [];
                        var waktu = [];

                        for(i=0; i<result.length; i++){
                            suhu[i] = Number(result[i].suhu);
                            waktu[i] = result[i].waktu;
                            chart.series[0].setData(suhu);
                            chart.xAxis[0].setCategories(waktu);
                        }
                        
                    }
                    else if (result.length<=total)
                    {
                      var i;
                        var suhu = [];
                        var waktu = [];

                        for(i=0; i<result.length; i++){
                            suhu[i] = Number(result[i].suhu);
                            waktu[i] = result[i].waktu;
                            chart.series[0].setData(suhu);
                            chart.xAxis[0].setCategories(waktu);
                        }
                        
                    }

                    setTimeout(tampil, 2000); 
                }
            });
    }
    
    document.addEventListener('DOMContentLoaded',function(){
        
        chart=Highcharts.chart('grafik',{
            chart:{
            type: 'line',
            events:{
                    load:tampil
                }
            },
            title:{
                text:'Data Rekap Suhu'
            },

            yAxis: {
                title: {
                    text: 'Nilai Suhu'
                }
            },

            xAxis: {
                
            },
            
            series:[{
                name:"Suhu"
            }]
        });
    });
</script>