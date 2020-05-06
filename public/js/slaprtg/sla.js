$(document).ready(function () {
    $.datetimepicker.setDateFormatter('moment');
    let url = $('#datatable').attr('url');
    let auth = $('#auth').attr('auth');
    let site, vsat, dvisat, upvisat, downvisat, ping, batvolt, vsatcurr, btscurr;
    let tempSite;
    let sla = [];

    let username = 'Power APT';
    let password = 'APT12345';
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
                    getSla({
                        data: data,
                        sdate: sdate,
                        edate: edate
                    });
                    sla.push({
                        no: index + 1,
                        site: site,
                        lc: data.lc,
                        sla_lvdvsat: vsat,
                        up_lvdvsat: upvisat,
                        sla_dlvdvsat: dvisat,
                        down_lvdvisat: downvisat,
                        sla_ping: ping,
                        avg_batvolt: batvolt,
                        avg_vsatcurr: vsatcurr,
                        avg_btscurr: btscurr,
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

    function getSla(data) {

        $.ajax({
            url: url,
            type: "GET",
            beforeSend: function (xhr) {
                xhr.setRequestHeader(
                    "Authorization",
                    `Bearer ${auth}`
                );
            },
            data: {
                id: data.data.id_lvdvsat,
                sdate: data.sdate,
                edate: data.edate,
                username: username,
                password: password,
            },
            async: false,
            success: function (response) {
                let parser = new DOMParser();
                let xmlDoc = parser.parseFromString(response, "text/xml");
                let cekData = xmlDoc.childNodes[0].childNodes.length;
                if (cekData > 10) {
                    $(xmlDoc).find('uptimepercent').each(function () {
                        const data = $(this);
                        vsat = (data[0].childNodes[1].data);
                    });
                    $(xmlDoc).find('downtimepercent').each(function () {
                        const data = $(this);
                        dvisat = (data[0].childNodes[1].data);
                    });
                    $(xmlDoc).find('uptime').each(function () {
                        const data = $(this);
                        upvisat = (data[0].childNodes[1].data);
                    });
                    $(xmlDoc).find('downtime').each(function () {
                        const data = $(this);
                        downvisat = (data[0].childNodes[1].data);
                    });
                    $(xmlDoc).find('parentdevicename').each(function () {
                        const data = $(this);
                        site = (data[0].childNodes[1].data.split('SNMP')[0]);
                    });
                } else {
                    vsat = "Error";
                }
            }
        });

        $.ajax({
            url: url,
            type: "GET",
            beforeSend: function (xhr) {
                xhr.setRequestHeader(
                    "Authorization",
                    `Bearer ${auth}`
                );
            },
            data: {
                id: data.data.id_ping,
                sdate: data.sdate,
                edate: data.edate,
                username: username,
                password: password,
            },
            async: false,
            success: function (response) {
                let parser = new DOMParser();
                let xmlDoc = parser.parseFromString(response, "text/xml");
                let cekData = xmlDoc.childNodes[0].childNodes.length;
                if (cekData > 10) {
                    $(xmlDoc).find('uptimepercent').each(function () {
                        const data = $(this);
                        ping = (data[0].childNodes[1].data);
                    });
                    $(xmlDoc).find('parentdevicename').each(function () {
                        const data = $(this);
                        site = (data[0].childNodes[1].data.split('-')[0]);
                    });
                } else {
                    ping = "Error";
                }
            }
        });

        $.ajax({
            url: url,
            type: "GET",
            beforeSend: function (xhr) {
                xhr.setRequestHeader(
                    "Authorization",
                    `Bearer ${auth}`
                );
            },
            data: {
                id: data.data.id_batvolt,
                sdate: data.sdate,
                edate: data.edate,
                username: username,
                password: password,
            },
            async: false,
            success: function (response) {
                let parser = new DOMParser();
                let xmlDoc = parser.parseFromString(response, "text/xml");
                let cekData = xmlDoc.childNodes[0].childNodes.length;
                if (cekData > 10) {
                    $(xmlDoc).find('average').each(function () {
                        const data = $(this);
                        batvolt = (parseInt(data[0].childNodes[1].data.split('.')[0])) / 1000;
                    });
                } else {
                    batvolt = "Error";
                }
            }
        });

        $.ajax({
            url: url,
            type: "GET",
            beforeSend: function (xhr) {
                xhr.setRequestHeader(
                    "Authorization",
                    `Bearer ${auth}`
                );
            },
            data: {
                id: data.data.id_vsatcurr,
                sdate: data.sdate,
                edate: data.edate,
                username: username,
                password: password,
            },
            async: false,
            success: function (response) {
                let parser = new DOMParser();
                let xmlDoc = parser.parseFromString(response, "text/xml");
                let cekData = xmlDoc.childNodes[0].childNodes.length;
                if (cekData > 10) {
                    $(xmlDoc).find('average').each(function () {
                        const data = $(this);
                        vsatcurr = (parseInt(data[0].childNodes[1].data.split('.')[0])) / 1000;
                    });
                } else {
                    vsatcurr = "Error";
                }
            }
        });

        $.ajax({
            url: url,
            type: "GET",
            beforeSend: function (xhr) {
                xhr.setRequestHeader(
                    "Authorization",
                    `Bearer ${auth}`
                );
            },
            data: {
                id: data.data.id_btscurr,
                sdate: data.sdate,
                edate: data.edate,
                username: username,
                password: password,
            },
            async: false,
            success: function (response) {
                let parser = new DOMParser();
                let xmlDoc = parser.parseFromString(response, "text/xml");
                let cekData = xmlDoc.childNodes[0].childNodes.length;
                if (cekData > 10) {
                    $(xmlDoc).find('average').each(function () {
                        const data = $(this);
                        btscurr = (parseInt(data[0].childNodes[1].data.split('.')[0])) / 1000;
                    });
                } else {
                    btscurr = "Error";
                }
            }
        });
    }

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

        $('#tablesla').DataTable({
            responsive: true,
            processing: true,
            retrieve: true,
            dom: 'Bfrtip',
            lengthChange: false,
            processing: true,
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
