import {
    dataSlaPrtg
} from "../export/getDataPrtg.js";
import {
    dataTables
} from "../export/dataTables.js";

$(document).ready(function () {
    let status = "serviceopen";
    let auth = $("#auth").attr("auth"),
        url = $("#url").attr("url"),
        urlsla = $("#url").attr("sla"),
        urlnoc = $("#url").attr("urlnoc");
    console.log(urlnoc);
    let totalJs;
    let dataPrtg = new dataSlaPrtg();
    let dataTable = new dataTables();
    let sla = [];
    $.ajax({
        type: "GET",
        url: urlnoc,
        dataType: "json",
        success: function (response) {
            totalJs = (response.recordsTotal);

        }
    });

    $(".btn").click(function (e) {
        $(".active").removeClass("active");
        $(this).addClass("active");

        $(".show").removeClass("show");
        $(this.collapse).addClass("show");
        let me = $(this),
            dism = me.attr("dism"),
            id = me.attr("id");
        status = id;
        url = dism;
        if (id == "serviceopen") {
            activeTable.ajax.reload();
        } else if (id == "serviceclose") {
            logTable.ajax.reload();
        }
    });

    let activeTable = dataTable.tables({
        id: "#activeTable",
        ajax: {
            type: "GET",
            url: url,
            beforeSend: function (xhr) {
                xhr.setRequestHeader("Authorization", `Bearer ${auth}`);
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
                let date =
                    today.getFullYear() +
                    "-" +
                    (today.getMonth() + 1) +
                    "-" +
                    today.getDate();
                let time =
                    today.getHours() +
                    ":" +
                    today.getMinutes() +
                    ":" +
                    today.getSeconds();
                let dateTime = date + " " + time;

                if (today.getMinutes() % 30 == 0) sla = [];
                let error = 0;
                json.forEach(data => {
                    time_open = new Date(data.open_time);
                    databaru = new Date(dateTime);
                    diff = databaru - time_open;

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
                        (tampil = day + " day ") :
                        hh != 0 ?
                        (tampil = hh + " Hours ") :
                        (tampil = mm + " Minutes ");

                    let temp = sla.find(e => e.nojs == data.nojs);
                    let slaDay, slaMonth;

                    if (temp == undefined) {
                        slaDay = dataPrtg.slaRealtime({
                            url: urlsla,
                            id: data.id_lvdvsat,
                            status: "day"
                        });
                        slaMonth = dataPrtg.slaRealtime({
                            url: urlsla,
                            id: data.id_lvdvsat,
                            status: "month"
                        });
                        sla.push({
                            nojs: data.nojs,
                            slaDay: slaDay,
                            slaMonth: slaMonth
                        });
                        temp = sla.find(e => e.nojs == data.nojs);
                    }
                    error = error + parseInt(data.error);
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
                        slaDay: parseFloat((temp.slaDay.uptimepercent).toFixed(1)),
                        slaMonth: parseFloat((temp.slaMonth.uptimepercent).toFixed(1)),
                        button: `<a href="servicecalls/${data.service_id}/edit" class="modal-show edit" title="${data.nojs} - ${data.site}"><i class="fa fa-edit" ></i></a>`
                    });
                });
                error = error / totalJs;
                $("#total").removeClass("d-none");
                $("#total").html(`Error ${error}`);
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
        id: "#logTable",
        ajax: {
            type: "GET",
            url: url,
            beforeSend: function (xhr) {
                xhr.setRequestHeader("Authorization", `Bearer ${auth}`);
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
                        (time_to_closed = day + " day " + hh + " Hours ") :
                        hh != 0 ?
                        (time_to_closed = hh + " Hours " + mm + " Minutes ") :
                        (time_to_closed = mm + " Minutes " + ss + " Seconds");

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
                data: "service_id"
            },
            {
                data: "nojs"
            },
            {
                data: "site"
            },
            {
                data: "open_time"
            },
            {
                data: "closed_time"
            },
            {
                data: "time_to_close"
            },
            {
                data: "lc"
            },
            {
                data: "error"
            },
            {
                data: "status"
            }
        ]
    });

    $("body").on("click", ".modal-show", function (e) {
        e.preventDefault();

        let me = $(this),
            url = me.attr("href"),
            title = me.attr("title");
        $("#modal-title").text(title);
        $("#modal-btn-save")
            .removeClass("d-none")
            .text("Update");
        $.ajax({
            url: url,
            beforeSend: function (xhr) {
                xhr.setRequestHeader("Authorization", `Bearer ${auth}`);
            },
            dataType: "html",
            success: function (response) {
                $("#modal-body").html(response);
            }
        });

        $("#modal").modal("show");
    });

    $("#modal-btn-save").click(function (e) {
        e.preventDefault();

        let form = $("#modal-body form"),
            url = form.attr("action"),
            method = $("input[name=_method]").val();
        form.find(".invalid-feedback").remove();
        form.find(".form-control").removeClass("is-invalid");
        $.ajax({
            url: url,
            method: method,
            beforeSend: function (xhr) {
                xhr.setRequestHeader("Authorization", `Bearer ${auth}`);
            },
            data: form.serialize(),
            success: function (response) {
                form.trigger("reset");
                $("#modal").modal("hide");
                activeTable.ajax.reload();
                $("#activedTab").addClass("show");
                status = "serviceopen";
            },
            error: function (xhr) {
                var res = xhr.responseJSON;
                status = "serviceopen";
            }
        });
    });

    $("#modal-footer .btn-secondary").addClass("d-none");

    setInterval(function () {
        if (status == "serviceopen") {
            activeTable.ajax.reload();
        }
    }, 1000 * 50);
});
