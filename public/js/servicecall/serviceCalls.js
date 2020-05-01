$(document).ready(function () {

    var status;
    let auth = $("#auth").attr("auth"),
        url = $("#url").attr("url");
    $('.btn').click(function (e) {
        $(".active").removeClass("active");
        $(this).addClass("active");

        $(".show").removeClass("show");
        $(this.collapse).addClass("show");
        var me = $(this),
            dism = me.attr('dism'),
            id = me.attr('id');
        status = id;
        url = dism;
        if (id == 'serviceopen') {
            activeTable.ajax.reload();
        } else if (id == 'serviceclose') {
            logTable.ajax.reload();
        }
    });

    let activeTable = $('#activeTable').DataTable({
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
            "url": url,
            "beforeSend": function (xhr) {
                xhr.setRequestHeader(
                    "Authorization",
                    `Bearer ${auth}`
                );
            },
            "data": {
                status: "OPEN"
            },
            "dataType": "json",
            "dataSrc": function (json) {
                // console.log(json);
                var return_data = [];
                var time_open;
                var databaru;
                var tampil;
                var site;
                var today = new Date();
                var date = today.getFullYear() + '-' + (today.getMonth() + 1) + '-' + today.getDate();
                var time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
                var dateTime = date + ' ' + time;
                // console.log(dateTime);
                for (var i = 0; i < json.length; i++) {

                    time_open = new Date(json[i].open_time);
                    databaru = new Date(dateTime);
                    diff = (databaru - time_open);
                    // console.log(diff);

                    var msec = diff;

                    var day = Math.floor(msec / 1000 / 60 / 60 / 24);
                    msec -= day * 1000 * 60 * 60 * 24;
                    var hh = Math.floor(msec / 1000 / 60 / 60);
                    msec -= hh * 1000 * 60 * 60;
                    var mm = Math.floor(msec / 1000 / 60);
                    msec -= mm * 1000 * 60;
                    var ss = Math.floor(msec / 1000);
                    msec -= ss * 1000;

                    (day != 0) ? tampil = (day + " day " + hh + " Hours "): (hh != 0) ? tampil = (hh + " Hours " + mm + " Minutes ") : tampil = (mm + " Minutes " + ss + " Seconds");

                    return_data.push({
                        'service_id': json[i].service_id,
                        'nojs': json[i].nojs,
                        'site': json[i].site,
                        'open_time': tampil,
                        'lc': json[i].lc,
                        'error': json[i].error,
                        'status': json[i].status,
                    })
                }
                return return_data;
            }

        },
        columns: [{
                "data": "service_id"
            },
            {
                "data": "nojs"
            },
            {
                "data": "site"
            },
            {
                "data": "open_time"
            },
            {
                "data": "lc"
            },
            {
                "data": "error"
            },
            {
                "data": "status"
            }
        ]
    });

    let logTable = $('#logTable').DataTable({
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
            "url": url,
            "beforeSend": function (xhr) {
                xhr.setRequestHeader(
                    "Authorization",
                    `Bearer ${auth}`
                );
            },
            "data": {
                status: "CLOSED"
            },
            "dataType": "json",
            dataSrc: function (json) {
                var return_data = [];
                var time_to_closed;
                var time_open;
                var time_close;
                var site;
                var hasil;
                var tampil;
                for (var i = 0; i < json.length; i++) {

                    time_open = new Date(json[i].open_time);
                    time_close = new Date(json[i].closed_time);
                    diff = time_close - time_open;

                    var msec = diff;
                    var day = Math.floor(msec / 1000 / 60 / 60 / 24);
                    msec -= day * 1000 * 60 * 60 * 24;
                    var hh = Math.floor(msec / 1000 / 60 / 60);
                    msec -= hh * 1000 * 60 * 60;
                    var mm = Math.floor(msec / 1000 / 60);
                    msec -= mm * 1000 * 60;
                    var ss = Math.floor(msec / 1000);
                    msec -= ss * 1000;

                    day != 0 ?
                        (time_to_closed = day + ' day ' + hh + ' Hours ') :
                        hh != 0 ?
                        (time_to_closed = hh + ' Hours ' + mm + ' Minutes ') :
                        (time_to_closed = mm + ' Minutes ' + ss + ' Seconds');

                    // (day != 0) ? time_to_closed = (day + " day " + hh + " Hours " + mm + " Minutes " + ss + " Seconds"): (hh != 0) ? time_to_closed = (hh + " Hours " + mm + " Minutes " + ss + " Seconds") : time_to_closed = (mm + " Minutes " + ss + " Seconds");

                    return_data.push({
                        service_id: json[i].service_id,
                        nojs: json[i].nojs,
                        site: json[i].site,
                        open_time: json[i].open_time,
                        lc: json[i].lc,
                        closed_time: json[i].closed_time,
                        time_to_close: time_to_closed,
                        error: json[i].error,
                        status: json[i].status
                    });
                }
                return return_data;
            }
        },
        columns: [{
                data: 'service_id'
            },
            {
                data: 'nojs'
            },
            {
                data: 'site'
            },
            {
                data: 'open_time'
            },
            {
                data: 'closed_time'
            },
            {
                data: 'time_to_close'
            },
            {
                data: 'lc'
            },
            {
                data: 'error'
            },
            {
                data: 'status'
            }
        ]
    });

    setInterval(function () {
        if (status == 'serviceopen') {
            activeTable.ajax.reload();
        } else if (status == 'serviceclose') {
            logTable.ajax.reload();
        }
    }, 1000 * 50);
});
