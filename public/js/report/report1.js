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
            window.open(
                `/report/download?site=${temp.site}&nojs=${nojs}&sdate=${start}&edate=${end}`
            );
        } else {
            swal({
                type: "error",
                title: "Oops...",
                text: "End date has to be after Start date"
            });
        }
    });
});
