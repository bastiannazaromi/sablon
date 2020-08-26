<section class="content">

    <div class="row">
        <div class="col-xl-6 mb-4">
            <div class="card text-dark bg-light mb-3">
                <div class="card-header bg-success text-center text-white">
                    Kontrol Alat
                </div>
                <div class="card-body">
                    <p class="card-text text-center">Tekan tombol dibawah ini untuk menjalankan alat</p>
                </div>
                <div class="card-footer bg-light text-center">
                    <label class="card-text text-dark">Tombol &nbsp</label>
                    <button type="button" class="btn btn-sm btn-warning mb-2" data-toggle="modal" data-target="#modalEdit"><i class="fas fa-edit"></i> Kontrol</button>
                </div>
            </div>
        </div>
    </div>

</section>

<div class="modal fade" id="modalEdit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="<?= base_url('Dashboard/kontrolAlat'); ?>" method="post">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Kontrol Alat</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Waktu (miilisecond)</label>
						<input type="text" class="form-control" id="delay" name="delay" required autocomplete="off">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" name="add" class="btn btn-primary">Kontrol</button>
                </div>
            </div>
        </form>
    </div>
</div>