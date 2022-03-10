<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use Illuminate\Http\Request;

class PenjualanController extends Controller
{
    public function index()
    {
        return view('penjualan.index');
    }

    public function create()
    {
        $penjualan = new Penjualan();
        $penjualan->nama = '';
        $penjualan->total_item = 0;
        $penjualan->total_harga = 0;
        $penjualan->bayar = 0;
        $penjualan->kembalian = 0;
        $penjualan->save();

        session(['id_penjualan' => $penjualan->id_penjualan]);
        return redirect()->route('transaksi.index');
    }

    public function data()
    {
        $penjualan = Penjualan::orderBy('id_penjualan', 'desc')->get();
        return datatables()
            ->of($penjualan)
            ->addIndexColumn()
            ->addColumn('created_at', function ($penjualan) {
                return tanggal_indonesia($penjualan->created_at, false);
            })
            ->addColumn('bayar', function ($penjualan) {
                $bayar = format_uang($penjualan->bayar);
                return 'Rp. ' . $bayar;
            })
            ->addColumn('kembalian', function ($penjualan) {
                $kembalian = format_uang($penjualan->kembalian);
                return 'Rp. ' . $kembalian;
            })
            ->addColumn('total_harga', function ($penjualan) {
                $total_harga = format_uang($penjualan->total_harga);
                return 'Rp. ' . $total_harga;
            })
            ->addColumn('aksi', function ($penjualan) {
                return '
                <button onclick="detail(`' . route('penjualan.show', $penjualan->id_penjualan) . '`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-eye"></i> Lihat</button>
                <button onclick="deleteData(`' . route('penjualan.destroy', $penjualan->id_penjualan) . '`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i> Hapus</button>
                ';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function show($id)
    {
        $detail = PenjualanDetail::with('produk')->where('id_penjualan', $id)->get();

        return datatables()
            ->of($detail)
            ->addIndexColumn()
            ->addColumn('harga', function ($detail) {
                return 'Rp. '. format_uang($detail->harga);
            })
            ->addColumn('nama_produk', function ($detail) {
                return $detail->produk['nama_produk'];
            })
            ->addColumn('jumlah', function ($detail) {
                return format_uang($detail->jumlah);
            })
            ->addColumn('sub_total', function ($detail) {
                return 'Rp. ' . format_uang($detail->sub_total);
            })
            ->addColumn('id_penjualan', function ($detail) {
                return '<div class="id">'.$detail->id_penjualan.'</div>';
            })
            ->rawColumns(['id_penjualan'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $penjualan = Penjualan::findOrFail($request->id_penjualan);
        $penjualan->nama = $request->nama;
        $penjualan->total_item = $request->total_item;
        $penjualan->total_harga = $request->total_harga;
        $penjualan->bayar = $request->bayar;
        $penjualan->kembalian = $request->kembalian;
        $penjualan->update();

        return redirect()->route('penjualan.index');
    }

    public function edit($id)
    {
        session(['id_penjualan' => $id]);
        return redirect()->route('transaksi.index');
    }

    public function destroy($id)
    {
        $penjualan = Penjualan::find($id);
        $penjualan->delete();
        $detail = PenjualanDetail::where('id_penjualan', $id)->get();
        $detail->delete();
        return response()->json(null, 204);
    }
}
