import { dataTables } from "../export/dataTables.js";

$(document).ready(function() {
    $.datetimepicker.setDateFormatter("moment");
    let dataTable = new dataTables();
    let dataPoint = ["5minutes", "hourly", "daily", "dev", "kwh"];
    let param = ["inverter", "chint"];
    let project = [
        {
            name: "Sundaya",
            token: "8226b600713a37abf2e018995e2a4add8ddf03e9"
        },
        {
            name: "HK",
            token: "5b8bd780813ffd904273ad25ae1a0d2e049f75a4"
        }
    ];

    dataPoint.forEach(data => {
        $(".selectpicker-data-point").append(
            `<option value="${data}">${data}</option>`
        );
    });

    param.forEach(data => {
        $(".selectpicker-param").append(
            `<option value="${data}">${data}</option>`
        );
    });

    project.forEach(data => {
        $(".selectpicker-project").append(
            `<option value="${data.token}">${data.name}</option>`
        );
    });
    $(".selectpicker-data-point").selectpicker("refresh");
    $(".selectpicker-param").selectpicker("refresh");
    $(".selectpicker-project").selectpicker("refresh");

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
        let dataDataPoint = $(".selectpicker-data-point").selectpicker("val");
        let dataParam = $(".selectpicker-param").selectpicker("val");
        let dataProject = $(".selectpicker-project").selectpicker("val");

        $("#datatable").html(
            `<table class="table table-striped table-bordered dt-responsive nowrap " style="width:100%" id="table"></table>`
        );

        if (
            start < end &&
            start != "" &&
            end != "" &&
            dataDataPoint != "" &&
            dataParam != "" &&
            dataProject != ""
        ) {
            let date_start = start
                .replace("-", "")
                .replace("-", "")
                .replace(":", "")
                .replace(" ", "");
            let date_end = end
                .replace("-", "")
                .replace("-", "")
                .replace(":", "")
                .replace(" ", "");

            console.log(date_start);
            console.log(date_end);

            $("#btnstart").addClass("disabled");
            $("#btnstart").text("");
            $("#btnstart").append(
                '<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span> Loading'
            );
            let hostname = window.location.hostname;

            let temp = getDataApi({
                url: `http://${hostname}:8001/api/${dataParam}/${dataDataPoint}`,
                // url: `http://192.168.2.8:8001/api/chint/hourly`,
                // url: `http://192.168.2.8:8001/api/${dataParam}/${dataDataPoint}`,
                // url: `http://119.18.158.238:8001/api/inverter/hourly`,
                date_start: date_start,
                date_end: date_end,
                token: dataProject
            });

            getTable(temp);
        } else if (
            dataDataPoint == "" ||
            dataParam == "" ||
            dataProject == ""
        ) {
            swal({
                type: "error",
                title: "Oops...",
                text: "Select Parameter"
            });
        } else {
            swal({
                type: "error",
                title: "Oops...",
                text: "End date has to be after Start date"
            });
        }
    });
    function getDataApi(data) {
        let temp;
        $.ajax({
            type: "GET",
            url: "/chint",
            data: {
                url: data.url,
                date_start: data.date_start,
                date_end: data.date_end,
                token: data.token
            },
            async: false,
            success: function(response) {
                if (response) {
                    let datas = response[0];
                    let clm = Object.keys(datas.data[0]);
                    let column = [];
                    let array = [];
                    let no = 0;
                    let tempSerialNumber = 0;

                    column.push(
                        {
                            data: "no",
                            title: "no"
                        },
                        {
                            data: "serial_number",
                            title: "serial_number"
                        }
                    );
                    clm.forEach(data => {
                        column.push({
                            data: data,
                            title: data
                        });
                    });

                    response.forEach(e => {
                        let datas = e.data;
                        let serial_number = e.serial_number;
                        datas.forEach((elm, i) => {
                            let tempDate = elm.time_stamp.split("T");
                            let newDateTime = `${tempDate[0][0]}${tempDate[0][1]}${tempDate[0][2]}${tempDate[0][3]}-${tempDate[0][4]}${tempDate[0][5]}-${tempDate[0][6]}${tempDate[0][7]} ${tempDate[1][0]}${tempDate[1][1]}:${tempDate[1][2]}${tempDate[1][3]}:${tempDate[1][4]}${tempDate[1][5]}`;
                            array.push(elm);
                            serial_number != tempSerialNumber
                                ? (array[no].serial_number = serial_number)
                                : (array[no].serial_number = "");
                            array[no].no = no;
                            array[no].time_stamp = newDateTime;
                            no += 1;
                            tempSerialNumber = serial_number;
                        });
                    });
                    temp = {
                        data: array,
                        columns: column
                    };
                } else {
                    swal({
                        type: "error",
                        title: "Oops...",
                        text: "No Data"
                    });
                }
            }
        });
        return temp;
    }

    function getTable(data) {
        $("#btnstart").html("");
        $("#btnstart").removeClass("disabled");
        $("#btnstart").text("Start");
        dataTable.tables({
            id: "#table",
            data: data.data,
            columns: data.columns
        });
    }
});
