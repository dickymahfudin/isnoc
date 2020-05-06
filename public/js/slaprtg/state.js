$(document).ready(function () {
    $.datetimepicker.setDateFormatter("moment");
    let url = $("#datatable").attr("url");
    let auth = $("#auth").attr("auth");
    let status, status_raw, sdatetime, edatetime;
    let tempSite = [];
    let state = [];

    let id = 34951;
    let username = "Power APT";
    let password = "APT12345";
    let sdate, edate;
    $.ajax({
        type: "GET",
        url: "../js/slaprtg/data.json",
        dataType: "json",
        success: function (response) {
            tempSite = response;
            response.forEach(data => {
                $(".selectpicker").append(
                    `<option value="${data.site}">${data.site} - ${data.lc}</option>`
                );
            });
            $(".selectpicker").selectpicker("refresh");
        },
        error: function (xhr) {
            console.log(xhr);
        }
    });

    $("#start").datetimepicker({
        timepicker: true,
        datetimepicker: true,
        format: "YYYY-MM-DD HH:mm",
        step: 5,
        yearStart: 2019,
        yearEnd: 2025,
        // theme: 'dark',
        onShow: function (ct) {
            this.setOptions({
                maxDate: $("#end").val() ? $("#end").val() : false
            });
        }
    });

    $("#toggleStart").on("click", function () {
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
        onShow: function (ct) {
            this.setOptions({
                minDate: $("#start").val() ? $("#start").val() : false
            });
        }
    });

    $("#toggleEnd").on("click", function () {
        $("#end").datetimepicker("toggle");
    });

    $("#btnstart").click(function (e) {
        e.preventDefault();
        let start = $(".start").val();
        let end = $(".end").val();
        let tempPicker = $(".selectpicker").selectpicker("val");
        let temp = tempSite.find(e => e.site == tempPicker);
        if (start < end && start != "") {
            if (temp) {
                sdate = start.replace(":", "-").replace(" ", "-");
                edate = end.replace(":", "-").replace(" ", "-");
                console.log(sdate);
                console.log(edate);

                $("#btnstart").addClass("disabled");
                $("#btnstart").text("");
                $("#btnstart").append(
                    '<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span> Loading'
                );
                state = [];
                getState({
                    data: temp,
                    sdate: sdate,
                    edate: edate,
                    start: start,
                    end: end
                });
            } else {
                swal({
                    type: "error",
                    title: "Oops...",
                    text: "Select Site"
                });
            }
        } else {
            swal({
                type: "error",
                title: "Oops...",
                text: "End date has to be after Start date"
            });
        }
    });

    function getState(data) {
        let site = data.data.site;
        let startDate = data.start;
        let endDate = data.end;
        $.ajax({
            url: url,
            type: "GET",
            beforeSend: function (xhr) {
                xhr.setRequestHeader("Authorization", `Bearer ${auth}`);
            },
            data: {
                id: data.data.id_lvdvsat,
                sdate: data.sdate,
                edate: data.edate,
                username: username,
                password: password
            },
            dataType: "json",
            async: false,
            success: function (response) {
                console.log(response);
                let log = response.log.histdata;
                let length = response.state.statehistory.length;
                response.state.statehistory.forEach((data, index) => {
                    let datetime;
                    if (index == 0) {
                        datetime = data.datetime.split("-");
                        let temp = new Date(startDate);
                        let time = formatAMPM(temp);
                        let newSdate = `${temp.getMonth()+1}/${temp.getDate()}/${temp.getFullYear()} ${time}`;

                        sdatetime = newSdate;
                        edatetime = log[0].datetime;
                        status_raw = data.status_raw;
                        status = (status_raw) == 1 ? "Up" : (status_raw) == 2 ? "Unknown" : "Down";
                    } else if (index == 1) {
                        if (length == 2) {
                            datetime = data.datetime.split("-");
                            let temp = new Date(endDate);
                            let time = formatAMPM(temp);
                            let newSdate = `${temp.getMonth()+1}/${temp.getDate()}/${temp.getFullYear()} ${time}`;

                            sdatetime = log[0].datetime;
                            edatetime = newSdate;
                            status_raw = data.status_raw;
                            status = (status_raw) == 1 ? "Up" : (status_raw) == 2 ? "Unknown" : "Down";
                        } else {
                            datetime = data.datetime.split("-");
                            let temp = new Date(edate);
                            let time = formatAMPM(temp);
                            let newSdate = `${temp.getMonth() +1}/${temp.getDate()}/${temp.getFullYear()} ${time}`;

                            sdatetime = log[0].datetime;
                            edatetime = datetime[1];
                            status_raw = data.status_raw;
                            status = (status_raw) == 1 ? "Up" : (status_raw) == 2 ? "Unknown" : "Down";
                        }
                    } else if ((index + 1) == length) {
                        datetime = data.datetime.split("-");
                        let temp = new Date(endDate);
                        let time = formatAMPM(temp);
                        let newSdate = `${temp.getMonth()+1}/${temp.getDate()}/${temp.getFullYear()} ${time}`;
                        sdatetime = datetime[0];
                        edatetime = newSdate;
                        status_raw = data.status_raw;
                        status = (status_raw) == 1 ? "Up" : (status_raw) == 2 ? "Unknown" : "Down";
                    } else {
                        datetime = data.datetime.split("-");
                        sdatetime = datetime[0];
                        edatetime = datetime[1];
                        status_raw = data.status_raw;
                        status = (status_raw) == 1 ? "Up" : (status_raw) == 2 ? "Unknown" : "Down";
                    }
                    state.push({
                        no: index + 1,
                        site: site,
                        start: sdatetime,
                        end: edatetime,
                        status_raw: status_raw,
                        status: status
                    });
                });
                pushDataTable(state);
            }
        });
    }

    function pushDataTable(data) {
        $("#btnstart").html("");
        $("#btnstart").removeClass("disabled");
        $("#btnstart").text("Start");

        $("#datatable").removeClass("d-none");

        $("#datatable").html(`
                    <table class="table table-striped table-bordered dt-responsive nowrap" style="width:100%" id="tablesla">
                        <thead>
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Site</th>
                                <th scope="col">Start Datetime</th>
                                <th scope="col">End Datetime</th>
                                <th scope="col">Status Raw</th>
                                <th scope="col">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>`);

        $("#tablesla").DataTable({
            responsive: true,
            processing: true,
            retrieve: true,
            dom: "Bfrtip",
            lengthChange: false,
            processing: true,
            lengthMenu: [
                [10, 25, 50, -1],
                ["10 rows", "25 rows", "50 rows", "Show all"]
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
            data: data,

            columns: [{
                    data: "no"
                },
                {
                    data: "site"
                },
                {
                    data: "start"
                },
                {
                    data: "end"
                },
                {
                    data: "status_raw"
                },
                {
                    data: "status"
                }
            ]
        });
    }

    function formatAMPM(date) {
        let hours = date.getHours();
        let minutes = date.getMinutes();
        let seconds = date.getSeconds();
        let ampm = hours >= 12 ? "PM" : "AM";
        hours = hours % 12;
        hours = hours ? hours : 12; // the hour '0' should be '12'
        minutes = minutes < 10 ? "0" + minutes : minutes;
        seconds = seconds < 10 ? "0" + seconds : seconds;
        let strTime = `${hours}:${minutes}:${seconds} ${ampm}`;
        return strTime;
    }
});
