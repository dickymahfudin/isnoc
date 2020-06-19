import { dataSlaNoc } from "../export/getDataNoc.js";
import { dataTables } from "../export/dataTables.js";
import { chartRender } from "../export/renderChart.js";

$(document).ready(function() {
    $.datetimepicker.setDateFormatter("moment");
    let url = $(".selectpicker").attr("url"),
        procesing = $(".selectpicker").attr("urlProcesing");
    let auth = $("#auth").attr("auth");
    let js = [];
    let dataLoggers = [];
    let dataDaily = [];
    let dataChart = [];
    let datasla = new dataSlaNoc();
    let dataTable = new dataTables();
    let render = new chartRender();

    js = datasla.getDataJs({
        url: url
    });

    $(".btn-coll").click(function(e) {
        $(".active").removeClass("active");
        $(this).addClass("active");

        $(".show").removeClass("show");
        $(this.collapse).addClass("show");
    });

    $("#start").datetimepicker({
        timepicker: true,
        datetimepicker: true,
        format: "YYYY-MM-DD HH:mm",
        step: 5,
        yearStart: 2019,
        yearEnd: 2025,
        // theme: 'dark',
        onShow: function(ct) {
            this.setOptions({
                maxDate: $("#end").val() ? $("#end").val() : false
            });
        }
    });
    $("#toggleStart").on("click", function() {
        $("#start").datetimepicker("toggle");
    });

    $("#end").datetimepicker({
        timepicker: true,
        datetimepicker: true,
        format: "YYYY-MM-DD HH:mm",
        step: 5,
        yearStart: 2019,
        yearEnd: 2025,
        // theme: 'dark',
        onShow: function(ct) {
            this.setOptions({
                minDate: $("#start").val() ? $("#start").val() : false
            });
        }
    });
    $("#toggleEnd").on("click", function() {
        $("#end").datetimepicker("toggle");
    });

    $("#btnstart").click(function(e) {
        e.preventDefault();
        let start = $(".start").val();
        let end = $(".end").val();
        let nojs = $(".selectpicker").selectpicker("val");
        let temp = js.find(e => e.nojs == nojs);
        if (start < end && start != "" && nojs) {
            // console.log(start);
            // console.log(end);
            // console.log(temp);

            $("#btnstart").addClass("disabled");
            $("#btnstart").text("");
            $("#btnstart").append(
                '<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span> Loading'
            );

            $("#datatable").html("");

            getData({
                url: procesing,
                nojs: nojs,
                sdate: start,
                edate: end
            });

            getTable({
                logger: dataLoggers,
                daily: dataDaily
            });
            $("#collapsebtn").removeClass("d-none");
        } else {
            swal({
                type: "error",
                title: "Oops...",
                text: "End date has to be after Start date"
            });
        }
    });

    function getData(data) {
        $.ajax({
            type: "GET",
            url: data.url,
            beforeSend: function(xhr) {
                xhr.setRequestHeader("Authorization", `Bearer ${auth}`);
            },
            async: false,
            data: {
                nojs: data.nojs,
                sdate: data.sdate,
                edate: data.edate,
                processing: true
            },
            dataType: "json",
            success: function(response) {
                let days = response.days;
                let batt_volt1 = [];
                let color = [];
                let label = [];
                dataLoggers = response.loggers;

                days.forEach(element => {
                    dataDaily.push({
                        date: element.batt_volt1.date,
                        minEh1: element.eh1.min,
                        maxEh1: element.eh1.max,
                        avgEh1: element.eh1.avg.toFixed(1),

                        minEh2: element.eh2.min,
                        maxEh2: element.eh2.max,
                        avgEh2: element.eh2.avg.toFixed(1),

                        minBv: element.batt_volt1.min,
                        maxBv: element.batt_volt1.max,
                        avgBv: element.batt_volt1.avg.toFixed(1),

                        minEdl1: element.edl1.min,
                        maxEdl1: element.edl1.max,
                        avgEdl1: element.edl1.avg.toFixed(1),

                        minEdl2: element.edl2.min,
                        maxEdl2: element.edl2.max,
                        avgEdl2: element.edl2.avg.toFixed(1)
                    });
                });
                dataChart = {
                    data: batt_volt1,
                    label: label
                };
            },
            error: function(x) {
                console.log(x);
            }
        });
    }

    function getTable(data) {
        $("#btnstart").html("");
        $("#btnstart").removeClass("disabled");
        $("#btnstart").text("Start");
        $("#datatable").html(`
                    <table class="table table-striped table-bordered dt-responsive nowrap" style="width:100%" id="tableLog" url="{{route('nojs.table')}}">
                        <thead>
                            <tr>
                            <th scope="col">time_local</th>
                            <th scope="col">Eh1</th>
                            <th scope="col">Eh2</th>
                            <th scope="col">Batt_volt</th>
                            <th scope="col">Edl1</th>
                            <th scope="col">Edl2</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
        `);

        $("#datatableresult").html(`
                    <table class="table table-striped table-bordered dt-responsive nowrap" style="width:100%" id="tableDaily" url="{{route('nojs.table')}}">
                        <thead>
                            <tr>
                            <th scope="col">Date</th>
                            <th scope="col">Min Eh1</th>
                            <th scope="col">Max Eh1</th>
                            <th scope="col">Avg Eh1</th>

                            <th scope="col">Min Eh2</th>
                            <th scope="col">Max Eh2</th>
                            <th scope="col">Avg Eh1</th>

                            <th scope="col">Min Batt_volt</th>
                            <th scope="col">Max Batt_volt</th>
                            <th scope="col">Avg Batt_volt</th>

                            <th scope="col">Min Edl1</th>
                            <th scope="col">Max Edl1</th>
                            <th scope="col">Avg Edl1</th>

                            <th scope="col">Min Edl2</th>
                            <th scope="col">Max Edl2</th>
                            <th scope="col">Avg Edl2</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
        `);

        dataTable.tables({
            id: "#tableLog",
            data: data.logger,
            columns: [
                {
                    data: "time_local"
                },
                {
                    data: "eh1"
                },
                {
                    data: "eh2"
                },
                {
                    data: "batt_volt1"
                },
                {
                    data: "edl1"
                },
                {
                    data: "edl2"
                }
            ]
        });

        dataTable.tables({
            id: "#tableDaily",
            data: data.daily,
            columns: [
                {
                    data: "date"
                },
                {
                    data: "minEh1"
                },
                {
                    data: "maxEh1"
                },
                {
                    data: "avgEh1"
                },
                {
                    data: "minEh2"
                },
                {
                    data: "maxEh2"
                },
                {
                    data: "avgEh2"
                },
                {
                    data: "minBv"
                },
                {
                    data: "maxBv"
                },
                {
                    data: "avgBv"
                },
                {
                    data: "minEdl1"
                },
                {
                    data: "maxEdl1"
                },
                {
                    data: "avgEdl1"
                },
                {
                    data: "minEdl2"
                },
                {
                    data: "maxEdl2"
                },
                {
                    data: "avgEdl2"
                }
            ]
        });
    }

    function chart(data) {
        let chartEh1 = document.getElementById(`chart`).getContext("2d");

        render({
            data: data,
            label: temp.label,
            color: temp.color_eh1,
            chart: chartEh1,
            min: 0,
            max: 55
        });
    }
});
