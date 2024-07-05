<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF;
use App\Exports\OrderExport;
use Excel;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $searchDate = $request->input('search');

        $orders = Order::whereDate('created_at', $searchDate)->simplePaginate(3);
        return view('order.kasir.index', compact('orders'));
    }

    public function cari(Request $request)
    {
        $cariDate = $request->input('cari');

        $orders = Order::whereDate('created_at', $cariDate)->simplePaginate(3);
        return view('order.admin.index', compact('orders'));
    }

    public function index()
    {
        // with : mengambil function relasi PK ke FK ke PK dari model 
        // isi di petik digunakan dengan nama function di modelnya
        $orders = Order::with('user')->simplePaginate(5);
        // dd($order)
        return view('order.kasir.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $medicines = Medicine::all();
        return view('order.kasir.create',compact('medicines'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name_customer' => 'required',
            'medicines' => 'required',
        ]);

        // hasilnya berbentuk : "itemnya" => "jumlah yang sama"
        // menentukan qty
        $medicines = array_count_values($request->medicines);
        // penampung detail berbentuk array2 assoc dari obat2 yang dipilih
        $dataMedicines = [];
        foreach ($medicines as $key => $value) {
            $medicine = Medicine::where('id', $key)->first();
            $arrayAssoc = [
                "id" => $key,
                "name_medicine" => $medicine['name'],
                "price" => $medicine['price'],
                "qty" =>  $value,
                // (int) => memastikan dan mengubah tipe data menjadi integer
                "price_after_qty" => (int)$value * (int)$medicine['price'],
            ];
            // format assoc dimasukkan ke array penampung sebelumnya
            array_push($dataMedicines, $arrayAssoc);
        }
        $totalPrice = 0;
        foreach ($dataMedicines as $formatArray) {
            // dia bakal menjumlahkan total price sebelumnya dijumlahkan ditambah data harga dari price_after_qty
            $totalPrice += (int)$formatArray['price_after_qty'];

        }

        $prosesTambahData = Order::create([
            'name_customer' => $request->name_customer,
            'medicines' => $dataMedicines,
            'total_price' => $totalPrice,
            // user_id menyimpan data id dari orang yang login (kasir penanggung jawab)
            'user_id' => Auth::user()->id,
        ]);
        return redirect()->route('order.struk', $prosesTambahData['id']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function strukPembelian($id)
    {
        $order = Order::where('id', $id)->first();

        return view('order.kasir.struk', compact('order'));
    }

    public function downloadPDF($id)
    {
        // get data yang akan ditampilkan di pdf
        // data yg dikirim ke pdf wajib bertipe array
        $order = Order::where('id', $id)->first()->toArray();

        // lokasi dan nama blade yang akan di download ke pdf serta data yg akan ditampilkan 
        view()->share('order',$order);

        $pdf = PDF::loadview('order.kasir.download', $order);
        // ketika di download nama filenya apa
        return $pdf->download('Bukti Pembelian.pdf');
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        //
    }

    public function data()
    {
        $orders = Order::with('user')->simplePaginate(5);
        return view('order.admin.index', compact('orders'));
    }

    public function downloadExcel()
    {
        $file_name = 'Data Seluruh Pembelian.xlsx';
        // panggil logic exports nya
        return Excel::download(new OrderExport, $file_name);
    }
}