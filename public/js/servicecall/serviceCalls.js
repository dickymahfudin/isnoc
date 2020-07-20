import { dataSlaPrtg } from "../export/getDataPrtg.js";
import { dataTables } from "../export/dataTables.js";

$(document).ready(function() {
    let status = "serviceopen";
    let auth = $("#auth").attr("auth"),
        url = $("#url").attr("url"),
        urlsla = $("#url").attr("sla"),
        urlnoc = $("#url").attr("urlnoc");
    let totalJs;
    let dataPrtg = new dataSlaPrtg();
    let dataTable = new dataTables();
    let sla = [];

    $.datetimepicker.setDateFormatter("moment");

    $("#start").datetimepicker({
        timepicker: true,
        datetimepicker: true,
        format: "YYYY-MM-DD",
        yearStart: 2019,
        yearEnd: 2025,
        // theme: 'dark',
        onShow: function(ct) {
            this.setOptions({
                maxDate: $("#end").val() ? $("#end").val() : false
            });
            $("#radio input:radio:checked").prop("checked", false);
        }
    });
    $("#toggleStart").on("click", function() {
        $("#start").datetimepicker("toggle");
    });

    $("#end").datetimepicker({
        timepicker: true,
        datetimepicker: true,
        format: "YYYY-MM-DD",
        yearStart: 2019,
        yearEnd: 2025,
        // theme: 'dark',
        onShow: function(ct) {
            this.setOptions({
                minDate: $("#start").val() ? $("#start").val() : false
            });
            $("#radio input:radio:checked").prop("checked", false);
        }
    });
    $("#toggleEnd").on("click", function() {
        $("#end").datetimepicker("toggle");
    });

    $("#radio").click(function(e) {
        $(".start").val("");
        $(".end").val("");
    });

    $("#btnstart").click(function(e) {
        e.preventDefault();
        let data;
        let radio = $("#radio input:radio:checked").val();
        let start = $(".start").val();
        let end = $(".end").val();
        if (radio) {
            data = {
                param: radio
            };
        } else if (start && end) {
            data = {
                start: start,
                end: end
            };
        } else {
            swal({
                type: "error",
                title: "Oops...",
                text: "End date has to be after Start date"
            });
        }
        getDataKPI(data);
    });

    $.ajax({
        type: "GET",
        url: urlnoc,
        dataType: "json",
        success: function(response) {
            totalJs = response.recordsTotal;
        }
    });

    $(".btn").click(function(e) {
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
            beforeSend: function(xhr) {
                xhr.setRequestHeader("Authorization", `Bearer ${auth}`);
            },
            data: {
                status: "OPEN"
            },
            dataType: "json",
            dataSrc: function(json) {
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

                    day != 0
                        ? (tampil = day + " day ")
                        : hh != 0
                        ? (tampil = hh + " Hours ")
                        : (tampil = mm + " Minutes ");

                    let temp = sla.find(e => e.nojs == data.nojs);

                    if (temp == undefined) {
                        let tempSla = dataPrtg.slaLocal(data.nojs);

                        sla.push({
                            nojs: data.nojs,
                            slaDay: tempSla.daily,
                            slaMonth: tempSla.monthly
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
                        slaDay: 0,
                        slaMonth: 0,
                        slaDay: temp.slaDay,
                        slaMonth: temp.slaMonth,
                        button: `<a href="servicecalls/${data.service_id}/edit" class="modal-show edit" title="${data.nojs} - ${data.site}"><i class="fa fa-edit" ></i></a>`
                    });
                });
                error = error / 100;
                $("#total").removeClass("d-none");
                $("#total").html(`Error ${error}`);
                return return_data;
            }
        },
        columns: [
            {
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

    function getDataKPI(data) {
        $.ajax({
            type: "GET",
            url: "/api/servicecount",
            beforeSend: function(xhr) {
                xhr.setRequestHeader("Authorization", `Bearer ${auth}`);
            },
            data: data,
            dataType: "json",
            success: function(response) {
                let data = [];
                let label = [];
                let temp = response.sum;
                temp.forEach(e => {
                    data.push(e.sum);
                    label.push(e.time_local);
                });
                $(".chart-service").html(`
                        <canvas id="chart" height="100"></canvas>

                        <div class="mt-5">
                            <table id="tablechart" class="table table-striped table-bordered dt-responsive" style="width:100%">
                                <thead>
                                    <tr>
                                        <th scope="col">Time Local</th>
                                        <th scope="col">Value</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>`);
                chart({
                    label: label,
                    data: data,
                    table: temp
                });
            }
        });
    }

    function chart(data) {
        let ctx = document.getElementById("chart").getContext("2d");
        let myChart = new Chart(ctx, {
            type: "line",
            data: {
                labels: data.label,
                datasets: [
                    {
                        label: "Service Call",
                        data: data.data,
                        borderColor: "#3e95cd",
                        backgroundColor: "rgba(255, 99, 132, 0.2)",
                        fill: false
                    }
                ]
            },
            options: {
                scales: {
                    yAxes: [
                        {
                            ticks: {
                                beginAtZero: true
                            }
                        }
                    ]
                }
            }
        });

        let logTable = dataTable.tables({
            id: "#tablechart",
            data: data.table,
            columns: [
                {
                    data: "time_local"
                },
                {
                    data: "sum"
                }
            ]
        });
    }

    $("body").on("click", ".modal-show", function(e) {
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
            beforeSend: function(xhr) {
                xhr.setRequestHeader("Authorization", `Bearer ${auth}`);
            },
            dataType: "html",
            success: function(response) {
                $("#modal-body").html(response);
            }
        });

        $("#modal").modal("show");
    });

    $("#modal-btn-save").click(function(e) {
        e.preventDefault();

        let form = $("#modal-body form"),
            url = form.attr("action"),
            method = $("input[name=_method]").val();
        form.find(".invalid-feedback").remove();
        form.find(".form-control").removeClass("is-invalid");
        $.ajax({
            url: url,
            method: method,
            beforeSend: function(xhr) {
                xhr.setRequestHeader("Authorization", `Bearer ${auth}`);
            },
            data: form.serialize(),
            success: function(response) {
                form.trigger("reset");
                $("#modal").modal("hide");
                activeTable.ajax.reload();
                $("#activedTab").addClass("show");
                status = "serviceopen";
            },
            error: function(xhr) {
                var res = xhr.responseJSON;
                status = "serviceopen";
            }
        });
    });

    $("#modal-footer .btn-secondary").addClass("d-none");

    setInterval(function() {
        if (status == "serviceopen") {
            activeTable.ajax.reload();
        }
    }, 1000 * 50);
});
