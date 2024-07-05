<?php

namespace App\Exports;

use App\Models\Order;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
// untuk menggunakan func headers
use Maatwebsite\Excel\Concerns\WithHeadings;
// untuk menggunakan func app
use Maatwebsite\Excel\Concerns\WithMapping;
class OrderExport implements FromCollection, WithHeadings, WithMapping
{
    // proses pengambilan data yang akan di export excel
    public function collection()
    {
        return Order::with('user')->get();
    }
    // menentukan nama-nama column di excel nya
    public function headings() : array
    {
        return [
            "Nama Pemebeli", "Pesanan", " Total Harga (+ppn)", "Kasir", "Tanggal"
        ];
    }
    // data dari collection (pengambilan dr db) yang akan dimunculkan ke excel
    public function map($item) : array
    {
        // hasil dari column medicines di db yang tadinya array diubah formatnya jadi :
            // (vitamin c : qty 2 Rp . 10.000)
        $pesanan = "";
        foreach ($item["medicines"] as $medicine) {
            $pesanan .= "(" . $medicine['name_medicine'] . " : qty :" . $medicine['qty'] . ": " . number_format($medicine['price_after_qty'], 0, '.','.') . "),";
            // menghitung total harga ditamabah ppn
            $totalAfterPPN = ($item['total_price'] * 0.1);
            // urutannya harus sama dengan yang di headings
            return [
                $item['name_customer'],
                $pesanan,
                "Rp. " . number_format($totalAfterPPN, 0, '.','.'),
                $item['user']['name'] . "(" . $item['user']['email'] . ")",
                Carbon::parse($item['created_at'])->format("d-m-Y H:i:s")
            ];
        }
    }
}
