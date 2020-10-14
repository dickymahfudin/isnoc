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


<p>Dear All</p>
<p>Laporan Noc, Data SLA bulanan ({{$start}} - {{$end}})</p>

@if (count($data) !== 0)
    <p>Berikut daftar site dengan SLA dibawah 95% :</p>
    <table>
            <tr>
                <th>  No  </th>
                <th>  Nojs  </th>
                <th>  Site  </th>
                <th>  LC  </th>
                <th>  SLA  </th>
            </tr>
        @foreach ($data as $key => $item)
            <tr>
                <td>{{$key+1}}</td>
                <td>{{$item["detail"]["nojs"]}}</td>
                <td>{{$item["detail"]["site"]}}</td>
                <td>({{$item["detail"]["lc"]}})</td>
                <td>{{$item["detail"]["sla"]}}%</td>
            </tr>
        @endforeach
    </table>

    <div style="margin-top: 30px;">
        <p>Berikut daftar KPI :</p>
        <table style="width: 500px; height: 104px;">
            <tr style="height: 30px;">
                <th colspan="2"></th>
                <th>Days</th>
                <th>Down Time</th>
            </tr>
            <tr style="height: 29px;">
                <td>KPI 1</td>
                <td>Energy Down Time</td>
                <td>{{$kpi["kpi1"]["days"]}}</td>
                <td>{{$kpi["kpi1"]["avg"]}}%</td>
            </tr>
            <tr style="height: 29px;">
                <td>KPI 2</td>
                <td>Data Down Time</td>
                <td>{{$kpi["kpi2"]["days"]}}</td>
                <td>{{$kpi["kpi2"]["avg"]}}%</td>
            </tr>
        </table>
    </div>

    <div style="margin-top: 30px;">
        <table style="width: 500px; height: 104px;">
            <tr style="height: 30px;">
                <th colspan="2"></th>
                <th>Site Qty</th>
                <th>SLA AVG</th>
            </tr>
            <tr style="height: 29px;">
                <td>KPI 3</td>
                <td>RED (SLA < 91)</td>
                <td>{{$kpi["kpi3"]["qty"]}}</td>
                <td>{{$kpi["kpi3"]["avg"]}}%</td>
            </tr>
            <tr style="height: 29px;">
                <td>KPI 4</td>
                <td>ORANGE (SLA <= 95 && SLA >= 91)</td>
                <td>{{$kpi["kpi4"]["qty"]}}</td>
                <td>{{$kpi["kpi4"]["avg"]}}%</td>
            </tr>
            <tr style="height: 29px;">
                <td>KPI 5</td>
                <td>GREEN (SLA > 95)</td>
                <td>{{$kpi["kpi5"]["qty"]}}</td>
                <td>{{$kpi["kpi5"]["avg"]}}%</td>
            </tr>
        </table>
    </div>

    <p>Kami akan selalu menjaga agar performance SLA di atas 95%</p>
@else
    <p>Semua Site SLA diatas 95%</p>
@endif

<p style="margin-bottom: 30px;"></p>
<b>Nb: *File Terlampir</b>
<h3 style="margin-bottom: 50px; margin-top: 30px;" >Terimakasih</h3>
<div>Best Regards</div>
<b>NOC SUNDAYA</b>

<div style="margin-top: 30px;">PT.Sundaya Indonesia</div>
<div>Kawasan Industri Sentul, Jl. Lintang Raya E9 Sentul, Bogor, Jawa Barat, 16810 Indonesia</div>
<div>Telp : 021–8769161/62/63, Fax  : 021–87900485</div>
<a href="www.sundaya.com">www.sundaya.com</a>
