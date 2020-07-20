<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="{{asset('js/noc/Chartjs.js')}}"></script>

    <style>
    table {
    font-family: arial, sans-serif;
    border-collapse: collapse;
    width: 200px;
    }

    td, th {
    border: 1px solid #dddddd;
    text-align: left;
    padding: 8px;
    }

    tr:nth-child(even) {
    background-color: #dddddd;
    }
    </style>
</head>
<body>
    <p>Dear Pak Maurice</p>
    <p>Berikut Data Service Call 1 Minggu</p>

    <table>
        <tr>
            <th>Time</th>
            <th>Value</th>
        </tr>
        @foreach ($data as $item)
            <tr>
                <td>{{$item["time_local"]}}</td>
                <td>{{$item["sum"]/100}}</td>
            </tr>
        @endforeach
    </table>
    <h3 style="margin-bottom: 50px; margin-top: 30px;" >Terimakasih</h3>
    <div>Best Regards</div>
    <b>NOC SUNDAYA</b>

    <div style="margin-top: 30px;">PT.Sundaya Indonesia</div>
    <div>Kawasan Industri Sentul, Jl. Lintang Raya E9 Sentul, Bogor, Jawa Barat, 16810 Indonesia</div>
    <div>Telp : 021–8769161/62/63, Fax  : 021–87900485</div>
    <a href="www.sundaya.com">www.sundaya.com</a>
</body>
</html>
