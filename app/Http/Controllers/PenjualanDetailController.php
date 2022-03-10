<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use App\Models\Produk;
use Illuminate\Http\Request;

class PenjualanDetailController extends Controller
{
    public function index()
    {
        $id_penjualan = session('id_penjualan');
        $produk = Produk::orderBy('nama_produk')->get();
        if(! $id_penjualan) {
            abort(404);
        }
        $penjualan = Penjualan::find($id_penjualan);
        return view('transaksi.index', compact('id_penjualan', 'produk', 'penjualan'));
    }

    public function create()
    {
        // $penjualan = new Penjualan();
    }

    public function data($id)
    {
        // $detail = PenjualanDetail::join('produk', 'penjualan_detail.id_produk', '=', 'produk.id_produk')
        //             ->select('penjualan_detail.*', 'produk.nama_produk', 'produk.harga')->get();
        $detail = PenjualanDetail::with('produk')->where('id_penjualan', $id)->get();
        $data = array();
        $total = 0;
        $total_item = 0;
        $bayar = 0;
        $kembali = 0;

        foreach ($detail as $item) {
            $row = array();
            $row['nama_produk'] = $item->produk['nama_produk'];
            $row['harga']       = $item->harga;
            $row['jumlah']      = '<input class="form-control qty" type="number" data-id="' . $item->id_penjualan_detail . '" value="' . $item->jumlah . '">';
            $row['sub_total']   = 'Rp. '. format_uang($item->sub_total);
            $row['aksi']        = '<button onclick="deleteData(`' . route('transaksi.destroy', $item->id_penjualan_detail) . '`)" class="btn btn-sm btn-danger btn-flat"><i class="fa fa-trash"></i></button>';
            $data[] = $row;
            
            $total += $item->harga * $item->jumlah;
            $total_item += $item->jumlah;
        }
        $data[] = [
            'nama_produk'   => '
                <div class="total d-none">'.$total. '</div><div class="total_item d-none">' . $total_item . '</div>',
            'harga'         => '',
            'jumlah'        => '',
            'sub_total'     => '',
            'aksi'          => '',
        ];

        return datatables()
            ->of($data)
            ->addIndexColumn()
            ->rawColumns(['aksi', 'jumlah', 'nama_produk'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $produk = Produk::where('id_produk', $request->id_produk)->first();
        if (! $produk) {
            return response()->json('Data gagal disimpan', 400);
        }
        $detail = new PenjualanDetail();
        $detail->id_penjualan = $request->id_penjualan;
        $detail->id_produk = $produk->id_produk;
        $detail->harga = $produk->harga;
        $detail->jumlah = 1;
        $detail->sub_total = $produk->harga;
        $detail->save();

        return response()->json('Data berhasil disimpan', 200);
    }

    public function update(Request $request, $id)
    {
        $detail = PenjualanDetail::find($id);
        $detail->jumlah = $request->jumlah;
        $detail->sub_total = $detail->harga * $request->jumlah;
        $detail->update();
    }

    public function destroy($id)
    {
        $detail = PenjualanDetail::find($id);
        $detail->delete();
        return response()->json(null, 204);
    }

    public function loadForm($total)
    {
        $data = [
            'total'             => $total,
            'tampilbayar'       => 'Rp. '. format_uang($total),
            'tampilterbilang'   => ucwords(terbilang($total).' Rupiah')
        ];
        return response()->json($data);
    }
}
