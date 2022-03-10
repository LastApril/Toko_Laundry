@extends('layouts.master')

@section('title')
Transaksi Penjualan
@endsection

@push('css')
<style>
    .table-index tbody tr:last-child {
        display: none;
    }
    .tampil-bayar {
        font-size: 4em;
        text-align: center;
        height: 100px;
    }
    .tampil-terbilang {
        padding: 10px;
        background: #f0f0f0;
    }
    @media(max-width: 768px) {
        .tampil-bayar {
            font-size: 3em;
            height: 70px;
            padding-top: 5px;
        }
        .tampil-terbilang {
            padding: 10px;
            background: #f0f0f0;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Main row -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <form class="form-produk">
                        @csrf
                        <div class="form-group row">
                            <label for="produk" class="col-lg-1">Produk</label>
                            <div class="col-lg-5">
                                <div class="input-group mb-3">
                                    <input type="hidden" name="id_penjualan" id="id_penjualan" value="{{ $id_penjualan }}">
                                    <input type="text" class="form-control" name="id_produk" id="id_produk">
                                    <div class="input-group-append">
                                        <button onclick="tampilProduk()" class="btn btn-info btn-sm btn-flat" type="button">
                                            <i class="fa fa-arrow-right"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="nama" class="col-lg-1">Nama</label>
                            <div class="col-lg-5">
                                <input type="text" class="form-control" name="nama_pembeli" id="nama_pembeli" value="{{ $penjualan->nama }}">
                            </div>
                        </div>
                    </form>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table class="table table-bordered table-striped table-index">
                        <thead>
                            <th width="7%">No</th>
                            <th>Nama</th>
                            <th>Harga</th>
                            <th width="15%">Jumlah</th>
                            <th>Subtotal</th>
                            <th width="15%"><i class="fa fa-cog"></i></th>
                        </thead>
                    </table>
                    <div class="row">
                        <div class="col-lg-8 mt-2">
                            <div class="tampil-bayar bg-primary"></div>
                            <div class="tampil-terbilang text-dark"></div>
                        </div>
                        <div class="col-lg-4 mt-2">
                            <form action="{{ route('penjualan.store') }}" class="form-pembelian" method="post">
                                @csrf
                                <input type="hidden" name="id_penjualan" value="{{ $id_penjualan }}">
                                <input type="hidden" class="nama" name="nama" id="nama" value="{{ $penjualan->nama }}">
                                <input type="hidden" name="total_item" id="total_item">
                                <input type="hidden" name="total_harga" id="total_harga">

                                <div class="form-group row">
                                    <label for="totalrp" class="col-lg-4 control-label">Total</label>
                                    <div class="col-lg-8">
                                        <input type="text" name="totalrp" id="totalrp" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="bayar" class="col-lg-4 control-label">Bayar</label>
                                    <div class="col-lg-8">
                                        <input type="text" name="bayar" id="bayar" class="form-control" value="{{ $penjualan->bayar }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="kembalian" class="col-lg-4 control-label">Kembalian</label>
                                    <div class="col-lg-8">
                                        <input type="text" name="kembalian" id="kembalian" class="form-control" readonly value="{{ $penjualan->kembalian }}">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- ./card-body -->
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary btn-sm btn-flat float-right btn-simpan">
                        <i class="fa fa-save"></i> Simpan Transaksi
                    </button>
                </div>
            </div>
            <!-- /.card -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row (main row) -->
</div><!-- /.container-fluid -->
@includeIf('transaksi.produk')
@endsection

@push('page-script')
<script>
    let table;
    $(function () {
        table1 = $('.table-produk').DataTable({
            processing: true,
            autoWidth: false,
            columns: []
        });
    })
    $(function () {
        table = $('.table-index').DataTable({
            processing: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('transaksi.data',$id_penjualan) }}',
            },
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'nama_produk'},
                {data: 'harga'},
                {data: 'jumlah'},
                {data: 'sub_total'},
                {data: 'aksi', searchable: false, sortable: false},
            ],
            dom: 'rt',
            bSort: false,
        })
        .on('draw.dt', function (){
            loadForm();
            let kembali = $('#bayar').val() - $('#totalrp').val();
            $('#kembalian').val(kembali);
        });
        $('#bayar').change(function () {
            let kembali = $(this).val() - $('#totalrp').val();
            $('#kembalian').val(kembali);
        })
        $('#nama_pembeli').change(function() {
            $('#nama').val($(this).val());
        });
        $('.btn-simpan').on('click', function () {
            $('.form-pembelian').submit();
        });
        
        $(document).on('input', '.qty', function (){
            let id = $(this).data('id');
            let jumlah = parseInt($(this).val());
            
            if (! jumlah) {
                $(this).val(1);
                alert('Jumlah tidak boleh kosong');
                return;
            }

            if (jumlah < 1) {
                $(this).val(1);
                alert('Jumlah tidak boleh kurang dari 1');
                return;
            }

            if (jumlah > 10000) {
                $(this).val(10000);
                alert('Jumlah tidak boleh lebih dari 10000');
                return;
            }

            $.post(`{{ url('/transaksi') }}/${id}`, {
                '_token': $('[name=csrf-token]').attr('content'),
                '_method': 'put',
                jumlah
            })
            .done(response => {
                table.ajax.reload();
            })
            .fail(errors => {
                alert('tidak dapat menyimpan data');
                return;
            });
        });
    });

    function tampilProduk() {
        $('#modal-produk').modal('show');
        $('#modal-produk .modal-title').text('Pilih Produk');
        
    }

    function pilihProduk(id) {
        $('#id_produk').val(id);
        $('#modal-produk').modal('hide');
        tambahProduk();
    }

    function tambahProduk() {
        $.post('{{ route('transaksi.store') }}', $('.form-produk').serialize())
            .done(response => {
                $('#id_produk').focus();
                table.ajax.reload();
            })
            .fail(errors => {
                alert('tidak dapat menyimpan data');
                return;
            });
    }
    
    function deleteData(url) {
        if (confirm('Yakin ingin menghapus data?')) {
            $.post(url, {
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'delete'
                })
                .done((response) => {
                    table.ajax.reload();
                })
                .fail((errors) => {
                    alert('Tidak dapat menghapus data');
                    return;
                })
        }
    }

    function loadForm() {
        $('#total_harga').val($('.total').text());
        $('#totalrp').val($('.total').text());
        $('#total_item').val($('.total_item').text());

        $.get(`{{ url('transaksi/loadform') }}/${$('.total').text()}`)
            .done(response => {
                $('.tampil-bayar').text(response.tampilbayar);
                $('.tampil-terbilang').text(response.tampilterbilang);
            })
    }
</script>
@endpush
