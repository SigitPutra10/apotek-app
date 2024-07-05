<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use Faker\Provider\Medical;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $medicines   = Medicine::orderBy('name','ASC')->simplePaginate(5);
        return view('medicine.index', compact('medicines'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('medicine.create');
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
            'name' => 'required|min:3',
            'type' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
        ]);

        Medicine::create([
            'name' => $request->name,
            'type' => $request->type,
            'price' => $request->price,
            'stock' => $request->stock,
        ]);

        return redirect()->back()->with('succes', 'Berhasil menambahkan Data Obat!');
    }
    

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Medicine  $medicine
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $medicine = Medicine::find($id);
        // mengambilkan bentuk json dikirim data yang di ambil dengan response status code 200
        // response status code api:
        // 200 -> secces/ok
        // 400 an -> error kode/validasi input user
        // 410 -> error token csrf
        // 500 an -> error server hosting
        return response()->json($medicine,200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Medicine  $medicine
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $medicine = Medicine::find($id);
        
        return view('medicine.edit', compact('medicine'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Medicine  $medicine
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|min:3',
            'type' => 'required',
            'price' => 'required|numeric',
        ]);
        Medicine::where('id',$id)->update(
        [
            'name'=> $request->name,
            'type'=> $request->type,
            'price'=> $request->price,
        ]);
        return redirect()->route('medicine.data')->with('succes','Berhasil mengubah data obat!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Medicine  $medicine
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Medicine::where('id',$id)->delete();
        return redirect()->back()->with('deleted','Berhasil menghapus data!');
    }
    public function stockData()
    {
        $medicines = Medicine::orderBy('stock', 'ASC')->simplePaginate(5);
        return view('medicine.stock', compact('medicines'));
    }

    public function updateStock(Request $request,$id)
    {
        $request->validate([
            'stock' => 'required|numeric',
        ]);
        $medicineBefore = Medicine::where('id', $id)->first();
        if($request->stock <= $medicineBefore['stock']){
            return response()->json(['message' => 'stock tidak boleh kurang/sama dengan stock sebelumnya! '],400);
        }
        $medicineBefore->update(['stock' => $request->stock]);
        return response()->json('berhasil',200);
        
    }
}
