import { dataTables } from "../export/dataTables.js";

$(document).ready(function() {
    $.datetimepicker.setDateFormatter("moment");
    let dataTable = new dataTables();
    let site = ["weduar", "ohoiwait", "toro", "saritani"];
    let dataTemp = [];

    site.forEach(data => {
        $(".selectpicker").append(`<option value="${data}">${data}</option>`);
    });

    $(".selectpicker").selectpicker("refresh");

    $(".btn-coll").click(function(e) {
        e.preventDefault();
        $(".active").removeClass("active");
        $(this).addClass("active");
        let id = $(this).attr("id");
        // if (id == "loggers") {
        //     $("#data-logger").html(`
        //         <table class="table table-striped table-bordered dt-responsive nowrap" style="width:100%" id="table-logger"></table>;
        //     `);
        //     getTable(dataTemp.logger, "#table-logger");
        // }
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
        let site = $(".selectpicker").selectpicker("val");

        if (start < end && start != "" && end != "" && site != "") {
            dataTemp = [];
            // $("#data-logger").html("");
            $("#collapsebtn").addClass("d-none");

            $("#btnstart").addClass("disabled");
            $("#btnstart").text("");
            $("#btnstart").append(
                '<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span> Loading'
            );
            $("#data-daily").html(`
                <table class="table table-striped table-bordered dt-responsive nowrap" style="width:100%" id="table-daily"></table>
            `);

            dataTemp = getData({ site, start, end });
            window.open(
                `/ajn/download?site=${site}&sdate=${start}&edate=${end}`
            );

            download({ site, start, end });
            $("#collapsebtn").removeClass("d-none");
            getTable(dataTemp.daily, "#table-daily");
        } else {
            swal({
                type: "error",
                title: "Oops...",
                text: "End date has to be after Start date"
            });
        }
    });

    function download(data) {
        $.ajax({
            type: "GET",
            url: "/ajn/download",
            data: {
                site: data.site,
                sdate: data.start,
                edate: data.end
            },
            async: false,
            success: function(response) {
                return response;
            }
        });
    }

    function getData(data) {
        let temp = [];
        $.ajax({
            type: "GET",
            url: "/api/ajn",
            data: {
                site: data.site,
                sdate: data.start,
                edate: data.end
            },
            async: false,
            success: function(response) {
                // console.log(response);
                // const logger = response.loggers;
                const daily = response.daily;
                // let clmLogger = Object.keys(logger[0]);
                let clmDaily = Object.keys(daily[0]);
                let columnLogger = [];
                let columnDaily = [];

                // clmLogger.forEach(data => {
                //     columnLogger.push({
                //         data: data,
                //         title: data
                //     });
                // });
                clmDaily.forEach(data => {
                    columnDaily.push({
                        data: data,
                        title: data
                    });
                });

                temp = {
                    // logger: {
                    //     data: logger,
                    //     columns: columnLogger
                    // },
                    daily: {
                        data: daily,
                        columns: columnDaily
                    }
                };
            }
        });
        return temp;
    }

    function getTable(data, id) {
        $("#btnstart").html("");
        $("#btnstart").removeClass("disabled");
        $("#btnstart").text("Start");
        dataTable.tables({
            id: id,
            data: data.data,
            columns: data.columns
        });
    }
});
