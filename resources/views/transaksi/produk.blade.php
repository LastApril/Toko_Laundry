<div class="modal fade" id="modal-produk" tabindex="-1" role="dialog" aria-labelledby="modal-produk"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title"></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <table class="table table-bordered table-hover table-produk">
                <thead>
                    <th width="5%">No</th>
                    <th>Nama</th>
                    <th>Harga</th>
                    <th><i class="fa fa-cog"></i></th>
                </thead>
                <tbody>
                    @foreach ($produk as $key => $item)
                        <tr>
                            <td width="5%">{{ $key+1 }}</td>
                            <td>{{ $item->nama_produk }}</td>
                            <td>{{ $item->harga }}</td>
                            <td>
                                <a href="#" class="btn btn-primary btn-xs btn-flat"
                                    onclick="pilihProduk({{ $item->id_produk }})">
                                    <i class="fa fa-check-circle">
                                        Pilih
                                    </i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    </div>
</div>