import { dataSlaNoc } from "../export/getDataNoc.js";
import { dataTables } from "../export/dataTables.js";

$(document).ready(function() {
    $.datetimepicker.setDateFormatter("moment");
    let url = $(".selectpicker").attr("url"),
        logger = $(".selectpicker").attr("urllog");
    let js = [];
    let datasla = new dataSlaNoc();
    let dataTable = new dataTables();
    let auth = $("#auth").attr("auth");

    js = datasla.getDataJs({
        url: url
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
        $("#detail").addClass("d-none");
        let start = $(".start").val();
        let end = $(".end").val();
        let nojs = $(".selectpicker").selectpicker("val");
        let temp = js.find(e => e.nojs == nojs);

        if (start < end && start != "" && nojs) {
            $("#btnstart").addClass("disabled");
            $("#btnstart").text("");
            $("#btnstart").append(
                '<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span> Loading'
            );
            $("#datatable").html("");
            let sla;
            $.ajax({
                type: "GET",
                url: logger,
                async: false,
                data: {
                    nojs: temp.nojs,
                    detail: true,
                    sdate: start,
                    edate: end
                },
                beforeSend: function(xhr) {
                    xhr.setRequestHeader("Authorization", `Bearer ${auth}`);
                },
                dataType: "json",
                success: function(response) {
                    setTable(response.daily);
                }
            });
        } else {
            swal({
                type: "error",
                title: "Oops...",
                text: "End date has to be after Start date"
            });
        }
    });

    function setTable(data) {
        $("#btnstart").html("");
        $("#btnstart").removeClass("disabled");
        $("#btnstart").text("Start");
        $("#detail").removeClass("d-none");
        // $('#tableLog').ajax.raload();
        $("#datatable").html(`
                    <table class="table table-striped table-bordered dt-responsive nowrap" style="width:100%" id="tableLog" url="{{route('nojs.table')}}">
                        <thead>
                            <tr>
                                <th scope="col">DATE</th>
                                <th scope="col">UP TIME</th>
                                <th scope="col">NOJS</th>
                                <th scope="col">BATTERY VOLTAGE 1</th>
                                <th scope="col">BATTERY VOLTAGE 2</th>
                                <th scope="col">VSAT CURRENT</th>
                                <th scope="col">BTS CURRENT</th>
                                <th scope="col">LOAD3</th>
                                <th scope="col">EH1</th>
                                <th scope="col">EH2</th>
                                <th scope="col">EDL1</th>
                                <th scope="col">EDL2</th>
                                <th scope="col">duration</th>
                            </tr>
                        </thead>
                    </table>
        `);
        dataTable.tables({
            id: "#tableLog",
            data: data,
            columns: [
                {
                    data: "time_local"
                },
                {
                    data: "up_time"
                },
                {
                    data: "nojs"
                },
                {
                    data: "batt_volt1"
                },
                {
                    data: "batt_volt2"
                },
                {
                    data: "vsat_curr"
                },
                {
                    data: "bts_curr"
                },
                {
                    data: "load3"
                },
                {
                    data: "eh1"
                },
                {
                    data: "eh2"
                },
                {
                    data: "edl1"
                },
                {
                    data: "edl2"
                },
                {
                    data: "duration"
                }
            ]
        });
    }
});
