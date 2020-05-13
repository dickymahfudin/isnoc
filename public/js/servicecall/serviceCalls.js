import {
    dataSlaPrtg
} from '../export/getDataPrtg.js'
import {
    dataTables
} from '../export/dataTables.js'

$(document).ready(function () {
    let status;
    let auth = $("#auth").attr("auth"),
        url = $("#url").attr("url"),
        urlsla = $("#url").attr('sla');
    let dataPrtg = new dataSlaPrtg;
    let dataTable = new dataTables;

    $('.btn').click(function (e) {
        $(".active").removeClass("active");
        $(this).addClass("active");

        $(".show").removeClass("show");
        $(this.collapse).addClass("show");
        let me = $(this),
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
    let activeTable = dataTable.tables({
        id: '#activeTable',
        ajax: {
            type: "GET",
            url: url,
            beforeSend: function (xhr) {
                xhr.setRequestHeader(
                    "Authorization",
                    `Bearer ${auth}`
                );
            },
            data: {
                status: "OPEN"
            },
            dataType: "json",
            dataSrc: function (json) {
                let return_data = [];
                let time_open;
                let databaru;
                let tampil, diff;
                let today = new Date();
                let date = today.getFullYear() + '-' + (today.getMonth() + 1) + '-' + today.getDate();
                let time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
                let dateTime = date + ' ' + time;


                json.forEach(data => {
                    time_open = new Date(data.open_time);
                    databaru = new Date(dateTime);
                    diff = (databaru - time_open);

                    let msec = diff;

                    let day = Math.floor(msec / 1000 / 60 / 60 / 24);
                    msec -= day * 1000 * 60 * 60 * 24;
                    let hh = Math.floor(msec / 1000 / 60 / 60);
                    msec -= hh * 1000 * 60 * 60;
                    let mm = Math.floor(msec / 1000 / 60);
                    msec -= mm * 1000 * 60;
                    let ss = Math.floor(msec / 1000);
                    msec -= ss * 1000;

                    (day != 0) ? tampil = (day + " day " + hh + " Hours "): (hh != 0) ? tampil = (hh + " Hours " + mm + " Minutes ") : tampil = (mm + " Minutes " + ss + " Seconds");

                    let slaDay = dataPrtg.slaRealtimeVsat({
                        url: urlsla,
                        id_lvdvsat: data.id_lvdvsat,
                        status: "day"
                    });
                    let slaMonth = dataPrtg.slaRealtimeVsat({
                        url: urlsla,
                        id_lvdvsat: data.id_lvdvsat,
                        status: "month"
                    })

                    return_data.push({
                        service_id: data.service_id,
                        nojs: data.nojs,
                        site: data.site,
                        open_time: tampil,
                        lc: data.lc,
                        mitra: data.mitra,
                        error: data.error,
                        pms: data.pms_state,
                        status: data.status,
                        slaDay: slaDay.vsat,
                        slaMonth: slaMonth.vsat,
                        button: `<a href="servicecalls/${data.service_id}/edit" class="modal-show edit" title="${data.nojs} - ${data.site}"><i class="fa fa-edit" ></i></a>`
                    })
                });
                return return_data;
            }
        },
        columns: [{
                data: "service_id"
            },
            {
                data: "nojs"
            },
            {
                data: "site"
            },
            {
                data: "pms"
            },
            {
                data: "open_time"
            },
            {
                data: "lc"
            },
            {
                data: "mitra"
            },
            {
                data: "error"
            },
            {
                data: "status"
            },
            {
                data: "slaDay"
            },
            {
                data: "slaMonth"
            },
            {
                data: "button"
            }
        ]

    });

    let logTable = dataTable.tables({
        id: '#logTable',
        ajax: {
            type: "GET",
            url: url,
            beforeSend: function (xhr) {
                xhr.setRequestHeader(
                    "Authorization",
                    `Bearer ${auth}`
                );
            },
            data: {
                status: "CLOSED"
            },
            dataType: "json",
            dataSrc: function (json) {
                let return_data = [];
                let time_to_closed;
                let time_open;
                let time_close, diff;
                for (let i = 0; i < json.length; i++) {

                    time_open = new Date(json[i].open_time);
                    time_close = new Date(json[i].closed_time);
                    diff = time_close - time_open;

                    let msec = diff;
                    let day = Math.floor(msec / 1000 / 60 / 60 / 24);
                    msec -= day * 1000 * 60 * 60 * 24;
                    let hh = Math.floor(msec / 1000 / 60 / 60);
                    msec -= hh * 1000 * 60 * 60;
                    let mm = Math.floor(msec / 1000 / 60);
                    msec -= mm * 1000 * 60;
                    let ss = Math.floor(msec / 1000);
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

    $('body').on('click', '.modal-show', function (e) {
        e.preventDefault();

        let me = $(this),
            url = me.attr('href'),
            title = me.attr('title');
        $('#modal-title').text(title);
        $('#modal-btn-save').removeClass('d-none').text('Update');
        $.ajax({
            url: url,
            beforeSend: function (xhr) {
                xhr.setRequestHeader(
                    "Authorization",
                    `Bearer ${auth}`
                );
            },
            dataType: 'html',
            success: function (response) {
                $('#modal-body').html(response);
            }
        });

        $('#modal').modal('show');
    });

    $('#modal-btn-save').click(function (e) {
        e.preventDefault();

        let form = $('#modal-body form'),
            url = form.attr('action'),

            method = $('input[name=_method]').val();
        console.log(url);
        console.log(method);
        form.find('.invalid-feedback').remove();
        form.find('.form-control').removeClass('is-invalid');
        $.ajax({
            url: url,
            method: method,
            beforeSend: function (xhr) {
                xhr.setRequestHeader(
                    "Authorization",
                    `Bearer ${auth}`
                );
            },
            data: form.serialize(),
            success: function (response) {
                form.trigger('reset');
                $('#modal').modal('hide');
                activeTable.ajax.reload();
                $('#activedTab').addClass("show");

            },
            error: function (xhr) {
                var res = xhr.responseJSON;
            }
        });
    });

    $('#modal-footer .btn-secondary').addClass('d-none');

    setInterval(function () {
        if (status == 'serviceopen') {
            activeTable.ajax.reload();
        }
    }, 1000 * 50);
});
