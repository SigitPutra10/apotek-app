<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Bukti Pembelian</title>
</head>

<body>
    <div id="back-wrap">
        <a href="{{ route('order.index') }}" class="btn-back">Kembali</a>
    </div>
    <div id="receipt">
    <a href="{{ route('order.download-pdf', $order['id']) }}" class="btn-print">Cetak (.pdf)</a>
        <center id="top">
            <div class="info">
                <h2>Apotek Jaya Abadi</h2>
            </div>
        </center>
        <div id="mid">
            <div class="info">
                <p>
                    Alamat : Jl Lumba-Lumba</br>
                    Email : apotekjayaabadi@gmail.com</br>
                    Phone : 000-111-2222</br>
                </p>
            </div>
        </div>
        <div id="bot">
            <div id="table">
                <table>
                    <tr class="tabletitle">
                        <td class="item">
                            <h2>Obat</h2>
                        </td>
                        <td class="item">
                            <h2>Total</h2>
                        </td>
                        <td class="Rate">
                            <h2>Harga</h2>
                        </td>
                    </tr>
                    @foreach ($order['medicines'] as $medicine)
                    <tr class="service">
                        <td class="tableitem">
                            <p class="itemtext">{{ $medicine['name_medicine'] }}</p>
                        </td>
                        <td class="tableitem">
                            <p class="itemtext">{{ $medicine['qty'] }}</p>
                        </td>
                        <td class="tableitem">
                            <p class="itemtext">Rp. {{ number_format($medicine['price'],0,',','.') }}</p>
                        </td>
                    </tr>
                    @endforeach
                    <tr class="tabletitle">
                        <td></td>
                        <td class="Rate">
                            <h2>PPN (10%)</h2>
                        </td>
                        @php
                            $ppn = $order['total_price'] * 0.1;
                        @endphp
                        <td class="payment">
                            <h2>Rp. {{ number_format($ppn,0,',','.') }}</h2>
                        </td>
                    </tr>
                    <tr class="tabletitle">
                        <td></td>
                        <td class="Rate">
                            <h2>Total Harga</h2>
                        </td>
                        <td class="payment">
                            @php
                            $totalBayar = $order['total_price'] + $ppn;
                            @endphp
                            <h2>Rp. {{ number_format($totalBayar,0,',','.') }}</h2>
                        </td>
                    </tr>
                </table>
            </div>
            <div id="legalcopy">
                <p class="legal"><strong>Terima kasih atas pembelian Anda!</strong>  Lorem ipsum dolor, sit amet consectetur adipisicing elit. Maiores natus et numquam ducimus dolorum tenetur.
                </p>
            </div>
        </div>
    </div>
</body>
</html>
