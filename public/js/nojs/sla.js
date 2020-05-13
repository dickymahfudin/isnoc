import {
    dataSlaNoc,
} from '../export/getDataNoc.js'
import {
    dataTables
} from '../export/dataTables.js'

$(document).ready(function () {
    $.datetimepicker.setDateFormatter('moment');
    let url = $('.selectpicker').attr('url'),
        logger = $('.selectpicker').attr('urllog');
    let js = [];
    let sla = [];
    let datasla = new dataSlaNoc;
    let dataTable = new dataTables;

    js = datasla.getDataJs({
        url: url
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
        $('#detail').addClass('d-none');
        let start = $('.start').val();
        let end = $('.end').val();
        let nojs = $('.selectpicker').selectpicker('val');
        let temp = [];
        for (let i = 0; i < nojs.length; i++) {
            const data = nojs[i];
            temp.push(js.find(e => e.nojs == data));
        }

        if ((start < end) && start != '' && nojs) {
            // sdate = (start.replace(':', '-')).replace(' ', '-');
            // edate = (end.replace(':', '-')).replace(' ', '-');
            console.log(start);
            console.log(end);

            $('#btnstart').addClass('disabled');
            $('#btnstart').text('');
            $('#btnstart').append('<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span> Loading');
            $('#datatable').html('');
            sla = [];
            temp.forEach(data => {
                let tempsla = datasla.getSlaNoc({
                    url: logger,
                    nojs: data.nojs,
                    sdate: start,
                    edate: end,
                })
                sla.push({
                    nojs: data.nojs,
                    site: data.site,
                    lc: data.lc,
                    mitra: data.mitra,
                    sla: tempsla
                })
            });
            setTable(sla)
        } else {
            swal({
                type: 'error',
                title: 'Oops...',
                text: 'End date has to be after Start date'
            });
        }
    });

    function setTable(data) {
        $('#btnstart').html('');
        $('#btnstart').removeClass('disabled');
        $('#btnstart').text('Start');
        $('#detail').removeClass('d-none');
        // $('#tableLog').ajax.raload();
        $('#datatable').html(`
                    <table class="table table-striped table-bordered dt-responsive nowrap" style="width:100%" id="tableLog" url="{{route('nojs.table')}}">
                        <thead>
                            <tr>
                            <th scope="col">Nojs</th>
                            <th scope="col">Site</th>
                            <th scope="col">LC</th>
                            <th scope="col">Mitra</th>
                            <th scope="col">SLA</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
        `);

        dataTable.tables({
            id: '#tableLog',
            data: data,
            columns: [{
                    "data": "nojs"
                },
                {
                    "data": "site"
                },
                {
                    "data": "lc"
                },
                {
                    "data": "mitra"
                },
                {
                    "data": "sla"
                }
            ]
        });

    }

});
