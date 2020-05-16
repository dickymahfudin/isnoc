import {
    dataSlaPrtg
} from "../export/getDataPrtg.js";
import {
    dataTables
} from '../export/dataTables.js'

$(document).ready(function () {
    $.datetimepicker.setDateFormatter('moment');
    let url = $('#datatable').attr('url');
    let auth = $('#auth').attr('auth');
    let vsat, ping, batvolt, vsatcurr, btscurr;
    let tempSite;
    let sla = [];
    let dataPrtg = new dataSlaPrtg();
    let dataTable = new dataTables;

    let sdate, edate;
    $.ajax({
        type: "GET",
        url: "../js/slaprtg/data.json",
        dataType: "json",
        success: function (response) {
            tempSite = response;
            response.forEach(data => {
                $('.selectpicker').append(`<option value="${data.site}">${data.site} - ${data.lc}</option>`);
            });
            $('.selectpicker').selectpicker('refresh');
        }
    });

    $('#start').datetimepicker({
        timepicker: true,
        datetimepicker: true,
        format: 'YYYY-MM-DD HH:mm',
        step: 5,
        yearStart: 2019,
        yearEnd: 2025,
        // theme: 'dark',
        onShow: function (ct) {
            this.setOptions({
                maxDate: $('#end').val() ? $('#end').val() : false,
            });
        }
    });

    $('#toggleStart').on('click', function () {
        $('#start').datetimepicker('toggle');
    })

    $('#end').datetimepicker({
        timepicker: true,
        datetimepicker: true,
        format: 'YYYY-MM-DD HH:mm',
        step: 5,
        yearStart: 2019,
        yearEnd: 2025,
        // theme: 'dark',
        onShow: function (ct) {
            this.setOptions({
                minDate: $('#start').val() ? $('#start').val() : false,
            });
        }
    });

    $('#toggleEnd').on('click', function () {
        $('#end').datetimepicker('toggle');
    })

    $('#btnstart').click(function (e) {
        e.preventDefault();
        let start = $('.start').val();
        let end = $('.end').val();

        let tempPicker = $('.selectpicker').selectpicker('val');
        let temp = [];

        for (let i = 0; i < tempPicker.length; i++) {
            const data = tempPicker[i];
            temp.push(tempSite.find(e => e.site == data));
        }
        if ((start < end) && start != '') {
            if (temp) {
                sdate = (start.replace(':', '-')).replace(' ', '-');
                edate = (end.replace(':', '-')).replace(' ', '-');
                console.log(sdate);
                console.log(edate);

                $('#btnstart').addClass('disabled');
                $('#btnstart').text('');
                $('#btnstart').append('<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span> Loading');
                sla = [];

                temp.forEach((data, index) => {
                    vsat = dataPrtg.getDataPrtg({
                        id: data.id_lvdvsat,
                        url: url,
                        sdate: sdate,
                        edate: edate
                    });
                    ping = dataPrtg.getDataPrtg({
                        id: data.id_ping,
                        url: url,
                        sdate: sdate,
                        edate: edate
                    });
                    batvolt = dataPrtg.getDataPrtg({
                        id: data.id_batvolt,
                        url: url,
                        sdate: sdate,
                        edate: edate
                    });
                    vsatcurr = dataPrtg.getDataPrtg({
                        id: data.id_vsatcurr,
                        url: url,
                        sdate: sdate,
                        edate: edate
                    });
                    btscurr = dataPrtg.getDataPrtg({
                        id: data.id_btscurr,
                        url: url,
                        sdate: sdate,
                        edate: edate
                    });
                    sla.push({
                        no: index + 1,
                        site: data.site,
                        lc: data.lc,
                        sla_lvdvsat: vsat.uptimepercent,
                        up_lvdvsat: vsat.uptime,
                        sla_dlvdvsat: vsat.downtimepercent,
                        down_lvdvisat: vsat.downtime,
                        sla_ping: ping.uptimepercent,
                        avg_batvolt: parseInt(batvolt.average) / 1000,
                        avg_vsatcurr: parseInt(vsatcurr.average) / 1000,
                        avg_btscurr: parseInt(btscurr.average) / 1000,
                    });
                });

                pushDataTable(sla);
            } else {
                swal({
                    type: 'error',
                    title: 'Oops...',
                    text: 'Select Site'
                });
            }
        } else {
            swal({
                type: 'error',
                title: 'Oops...',
                text: 'End date has to be after Start date'
            });
        }
    });

    function pushDataTable(data) {
        $('#btnstart').html('');
        $('#btnstart').removeClass('disabled');
        $('#btnstart').text('Start');

        $('#datatable').removeClass('d-none');

        $('#datatable').html(`
                    <table class="table table-striped table-bordered dt-responsive nowrap" style="width:100%" id="tablesla">
                        <thead>
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Site</th>
                                <th scope="col">LC</th>
                                <th scope="col">Sla_vsat</th>
                                <th scope="col">up_vsat</th>
                                <th scope="col">sla_dvsat</th>
                                <th scope="col">down_vsat</th>
                                <th scope="col">Sla_ping</th>
                                <th scope="col">Avg_batvolt</th>
                                <th scope="col">Avg_vsatcurr</th>
                                <th scope="col">Avg_btscurr</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>`);

        dataTable.tables({
            id: '#tablesla',
            data: data,
            columns: [{
                    "data": "no"
                }, {
                    "data": "site"
                },
                {
                    "data": "lc"
                },
                {
                    "data": "sla_lvdvsat"
                },
                {
                    "data": "up_lvdvsat"
                },
                {
                    "data": "sla_dlvdvsat"
                },
                {
                    "data": "down_lvdvisat"
                },
                {
                    "data": "sla_ping"
                },
                {
                    "data": "avg_batvolt"
                },
                {
                    "data": "avg_vsatcurr"
                },
                {
                    "data": "avg_btscurr"
                },
            ]
        });
    }

});
