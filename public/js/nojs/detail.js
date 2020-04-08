$(document).ready(function () {
    $.datetimepicker.setDateFormatter('moment');
    let url = $('.selectpicker').attr('url'),
        logger = $('.selectpicker').attr('urllog');
    let js = [];
    $.ajax({
        type: "GET",
        url: url,
        dataType: "json",
        success: function (response) {
            js = response.data;
            response.data.forEach(data => {
                $('.selectpicker').append(`<option value="${data.nojs}">${data.nojs} - ${data.site}</option>`);
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
        $('#detail').addClass('d-none');
        let start = $('.start').val();
        let end = $('.end').val();
        let nojs = $('.selectpicker').selectpicker('val');
        let temp = js.find(e => e.nojs == nojs);
        if ((start < end) && start != '' && nojs) {
            // sdate = (start.replace(':', '-')).replace(' ', '-');
            // edate = (end.replace(':', '-')).replace(' ', '-');
            console.log(start);
            console.log(end);


            $('#btnstart').addClass('disabled');
            $('#btnstart').text('');
            $('#btnstart').append('<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span> Loading');
            $('#tables').html('');

            getTable({
                url: logger,
                nojs: nojs,
                sdate: start,
                edate: end,
                site: temp.site,
                mitra: temp.mitra
            });
        } else {
            swal({
                type: 'error',
                title: 'Oops...',
                text: 'End date has to be after Start date'
            });
        }
    });

    function getTable(data) {
        $('#btnstart').html('');
        $('#btnstart').removeClass('disabled');
        $('#btnstart').text('Start');
        $('#detail').removeClass('d-none');
        // $('#tableLog').ajax.raload();
        $('#datatable').html(`
                    <table class="table table-striped table-bordered dt-responsive nowrap" style="width:100%" id="tableLog" url="{{route('nojs.table')}}">
                        <thead>
                            <tr>
                            <th scope="col">time_local</th>
                            <th scope="col">Eh1</th>
                            <th scope="col">Eh2</th>
                            <th scope="col">Vsat_Curr</th>
                            <th scope="col">Bts_Curr</th>
                            <th scope="col">Load3</th>
                            <th scope="col">Batt_volt1</th>
                            <th scope="col">Batt_volt2</th>
                            <th scope="col">Edl1</th>
                            <th scope="col">Edl2</th>
                            <th scope="col">Pms_state</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
        `);
        $('#tableLog').DataTable({
            rowReorder: {
                selector: 'td:nth-child(2)'
            },
            responsive: true,
            procesing: true,
            // serverSide: true,
            dom: 'Bfrtip',
            lengthChange: false,
            lengthMenu: [
                [10, 25, 50, -1],
                ['10 rows', '25 rows', '50 rows', 'Show all']
            ],
            buttons: {
                dom: {
                    button: {
                        tag: 'button',
                        className: 'btn-group'
                    }
                },
                buttons: [{
                        extend: 'pageLength',
                        className: 'btn btn-sm btn-secondary mr-2',
                        titleAttr: 'Sort',
                    },
                    {
                        extend: 'excel',
                        className: 'btn btn-sm btn-success mr-2',
                        titleAttr: 'Excel export.',
                        text: 'Excel',
                        filename: 'excel-export',
                        extension: '.xlsx'
                    }, {
                        extend: 'copy',
                        className: 'btn btn-sm btn-primary mr-2',
                        titleAttr: 'Copy table data.',
                        text: 'Copy'
                    }, {
                        extend: 'pdf',
                        className: 'btn btn-sm btn-warning mr-2',
                        titleAttr: 'Pdf export.',
                        text: 'Pdf',
                        filename: 'pdf-export',
                    },
                ]
            },
            drawCallback: function () {
                $('.pagination').addClass("d-flex justify-content-center");
            },
            ajax: {
                "type": "GET",
                "url": data.url,
                "beforeSend": function (xhr) {
                    xhr.setRequestHeader(
                        "Authorization",
                        "Bearer Afidha1pHYJIEOSB08TSrPQ9v2dTFFcPHx1bCFc7lZEQD2BXPBtbNoYEcGHMhKVhnk9MwwwJTOLSK4vR"
                    );
                },
                "data": {
                    nojs: data.nojs,
                    sdate: data.sdate,
                    edate: data.edate
                },
                "dataType": "json",
                "dataSrc": function (json) {
                    return json;
                }
            },
            columns: [{
                    "data": "time_local"
                },
                {
                    "data": "eh1"
                },
                {
                    "data": "eh2"
                },
                {
                    "data": "vsat_curr"
                },
                {
                    "data": "bts_curr"
                },
                {
                    "data": "load3"
                },
                {
                    "data": "batt_volt1"
                },
                {
                    "data": "batt_volt2"
                },
                {
                    "data": "edl1"
                },
                {
                    "data": "edl2"
                },
                {
                    "data": "pms_state"
                },
            ]
        });

        $('#nojs').text(data.nojs);
        $('#site').text(data.site);
        $('#mitra').text(data.mitra);
    }

});
