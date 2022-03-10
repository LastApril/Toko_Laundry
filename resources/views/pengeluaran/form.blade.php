<div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="modal-form"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="" method="post" class="form-horizontal needs-validation" novalidate>
            @csrf
            @method('post')
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <label for="deskripsi" class="col-md-2 col-md-offset-1 control-label">Deskripsi</label>
                    <div class="col-md-10">
                        <textarea name="deskripsi" id="deskripsi" rows="3" class="form-control" placeholder="Deskripsi..."></textarea>
                        {{-- <input type="text" name="deskripsi" id="deskripsi" class="form-control" required autofocus> --}}
                        <div class="invalid-feedback">
                            Isi Deskripsi Pengeluaran
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <style>
                        input[type="number"]::-webkit-outer-spin-button,
                        input[type="number"]::-webkit-inner-spin-button {
                            -webkit-appearance: none;
                            margin: 0;
                        }

                        input[type="number"] {
                            -moz-appearance: textfield;
                        }
                    </style>
                    <label for="total_harga" class="col-md-2 col-md-offset-1 control-label">Total Harga</label>
                    <div class="col-md-10">
                        <input type="number" name="total_harga" id="total_harga" class="form-control" required>
                        <div class="invalid-feedback">
                            Isi Total Harga Pengeluaran
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-sm btn-flat btn-primary">Simpan</button>
                <button type="button" class="btn btn-sm btn-flat btn-secondary" data-dismiss="modal">Batal</button>
            </div>
        </div>
        </form>
    </div>
</div>