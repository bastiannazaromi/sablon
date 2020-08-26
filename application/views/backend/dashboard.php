<section class="content">

  <div class="row">
    <div class="col-lg-4 col-12">
      <!-- small box -->
      <div class="small-box bg-info">
        <div class="inner" id="head-suhu">
          <h3 id="suhu"></h3>

          <p>Celcius</p>
        </div>
        <div class="icon">
          <i class="fas fa-user fa-2x"></i>
        </div>
        <a href="<?= base_url('Dashboard/rekap') ; ?>" class="small-box-footer">Detail <i class="fas fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-4 col-6">
      <!-- small box -->
      <div class="small-box bg-success">
        <div class="inner">
          <h3 id="total"></h3>

          <p>Total Pemakaian</p>
        </div>
        <div class="icon">
          <i class="fas fa-book-open fa-2x"></i>
        </div>
        <a href="<?= base_url('Dashboard/pemakaian') ; ?>" class="small-box-footer">Detail <i class="fas fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-4 col-6">
      <!-- small box -->
      <div class="small-box bg-warning">
        <div class="inner">
          <h3 id="hari_ini"></h3>

          <p>Pemakaian Hari Ini</p>
        </div>
        <div class="icon">
          <i class="fas fa-book-open fa-2x"></i>
        </div>
        <a href="<?= base_url('Dashboard/pemakaian') ; ?>" class="small-box-footer">Detail <i class="fas fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <!-- ./col -->
  </div>

</section>

<script>

  function tampil(){
    $.ajax({
        url: "<?= base_url('Dashboard/dashboard_realtime')?>",
        dataType: 'json',
        success:function(result){
          
          $('#suhu').text(result["suhu"][0]["suhu"]);

          $('#suhu').append('<sup style="font-size: 20px">o</sup>');

          $('#total').text(result["total"] + " x");
          $('#hari_ini').text(result["hari_ini"] + " x");
          
          setTimeout(tampil, 2000); 
        }
    });
  }
  
  document.addEventListener('DOMContentLoaded',function(){
    tampil();
  });   

</script>