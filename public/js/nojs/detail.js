import { dataSlaNoc } from "../export/getDataNoc.js";
import { dataTables } from "../export/dataTables.js";

$(document).ready(function() {
    $.datetimepicker.setDateFormatter("moment");
    let url = $(".selectpicker").attr("url"),
        logger = $(".selectpicker").attr("urllog");
    let auth = $("#auth").attr("auth");
    let js = [];
    let datasla = new dataSlaNoc();
    let dataTable = new dataTables();

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
            // sdate = (start.replace(':', '-')).replace(' ', '-');
            // edate = (end.replace(':', '-')).replace(' ', '-');
            console.log(start);
            console.log(end);

            $("#btnstart").addClass("disabled");
            $("#btnstart").text("");
            $("#btnstart").append(
                '<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span> Loading'
            );
            $("#tables").html("");

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
                type: "error",
                title: "Oops...",
                text: "End date has to be after Start date"
            });
        }
    });

    function getTable(data) {
        let error = 0;
        let sla = 0;
        $("#btnstart").html("");
        $("#btnstart").removeClass("disabled");
        $("#btnstart").text("Start");
        $("#detail").removeClass("d-none");
        // $('#tableLog').ajax.raload();
        $("#datatable").html(`
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

        dataTable.tables({
            id: "#tableLog",
            ajax: {
                type: "GET",
                url: data.url,
                beforeSend: function(xhr) {
                    xhr.setRequestHeader("Authorization", `Bearer ${auth}`);
                },
                data: {
                    nojs: data.nojs,
                    sdate: data.sdate,
                    edate: data.edate,
                    detail: true
                },
                dataType: "json",
                dataSrc: function(json) {
                    let array = [];
                    let time_local,
                        eh1,
                        eh2,
                        vsat_curr,
                        bts_curr,
                        load3,
                        batt_volt1,
                        batt_volt2,
                        edl1,
                        edl2,
                        pms_state;
                    json.logger.forEach(data => {
                        data.time_local == null
                            ? (time_local = "error")
                            : (time_local = data.time_local);
                        data.eh1 == null ? (eh1 = "error") : (eh1 = data.eh1);
                        data.eh2 == null ? (eh2 = "error") : (eh2 = data.eh2);
                        data.vsat_curr == null
                            ? (vsat_curr = "error")
                            : (vsat_curr = data.vsat_curr);
                        data.bts_curr == null
                            ? (bts_curr = "error")
                            : (bts_curr = data.bts_curr);
                        data.load3 == null
                            ? (load3 = "error")
                            : (load3 = data.load3);
                        data.batt_volt1 == null
                            ? (batt_volt1 = "error")
                            : (batt_volt1 = data.batt_volt1);
                        data.batt_volt2 == null
                            ? (batt_volt2 = "error")
                            : (batt_volt2 = data.batt_volt2);
                        data.edl1 == null
                            ? (edl1 = "error")
                            : (edl1 = data.edl1);
                        data.edl2 == null
                            ? (edl2 = "error")
                            : (edl2 = data.edl2);
                        data.pms_state == null
                            ? (pms_state = "error")
                            : (pms_state = data.pms_state);

                        if (data.eh1 != null) error++;
                        array.push({
                            time_local: data.time_local,
                            eh1: eh1,
                            eh2: eh2,
                            vsat_curr: vsat_curr,
                            bts_curr: bts_curr,
                            load3: load3,
                            batt_volt1: batt_volt1,
                            batt_volt2: batt_volt2,
                            edl1: edl1,
                            edl2: edl2,
                            pms_state: pms_state
                        });
                    });
                    console.log(error);
                    sla = (error / json.logger.length) * 100;
                    $("#nojs").text(data.nojs);
                    $("#site").text(data.site);
                    $("#mitra").text(data.mitra);
                    $("#sla").text(`${sla.toFixed(2)}%`);
                    return array;
                }
            },
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
                    data: "vsat_curr"
                },
                {
                    data: "bts_curr"
                },
                {
                    data: "load3"
                },
                {
                    data: "batt_volt1"
                },
                {
                    data: "batt_volt2"
                },
                {
                    data: "edl1"
                },
                {
                    data: "edl2"
                },
                {
                    data: "pms_state"
                }
            ]
        });
    }
});
