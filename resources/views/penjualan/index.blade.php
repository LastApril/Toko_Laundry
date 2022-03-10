@extends('layouts.master')

@section('title')
Daftar Penjualan
@endsection

@push('css')
<style>
    .table-transaksi tbody td:last-child {
        display: none;
    }
    .table-transaksi thead th:last-child {
        display: none;
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
                    <h5 class="card-title">
                        <a href="{{ route('penjualan.create') }}" class="btn btn-success btn-sm btn-flat">
                            <i class="fa fa-plus-circle"> Transaksi Baru</i>
                        </a>
                    </h5>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table class="table table-bordered table-hover table-penjualan">
                        <thead>
                            <th width="7%">No</th>
                            <th>Tanggal</th>
                            <th>Nama</th>
                            <th>Total Item</th>
                            <th>Total Harga</th>
                            <th>Bayar</th>
                            <th>Kembalian</th>
                            <th width="15%"><i class="fa fa-cog"></i></th>
                        </thead>
                    </table>
                </div>
                <!-- ./card-body -->

            </div>
            <!-- /.card -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row (main row) -->
</div><!-- /.container-fluid -->
@includeIf('penjualan.form')
@endsection

@push('page-script')
<script>
    let table, table1;
    $(function () {
        table = $('.table-penjualan').DataTable({
            processing: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('penjualan.data') }}',
            },
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'created_at'},
                {data: 'nama'},
                {data: 'total_item'},
                {data: 'total_harga'},
                {data: 'bayar'},
                {data: 'kembalian'},
                {data: 'aksi', searchable: false, sortable: false},
            ]
        });
        table1 = $('.table-transaksi').DataTable({
            processing: true,
            autoWidth: false,
            bSort: false,
            dom: 'rt',
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'nama_produk'},
                {data: 'harga'},
                {data: 'jumlah'},
                {data: 'sub_total'},
                {data: 'id_penjualan'},
            ]
        });
    });

    function ubah() {
        window.location.href = `{{ url('/penjualan/edit') }}/${$('.id').text()}`;
    }    

    function detail(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Detail Penjualan');
        table1.ajax.url(url);
        table1.ajax.reload();
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
</script>
@endpush
