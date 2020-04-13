import {
    GetData,
    renderChart,
    GetDataSingle
} from "./getdata.js";

$(document).ready(function () {
    let js = [];
    let DatasLogger = [];
    let chartEh1, chartEh2, chartBv, chartedl1, chartedl2;
    let GetLogger = new GetData();

    let me = $("#pagination"),
        url = me.attr("url"),
        log = me.attr("urllog"),
        auth = me.attr("auth");

    // let a = GetLogger.GetDataLoggers({
    //     nojs: "JS10",
    //     limit: 4,
    //     url: log
    // });

    // console.log(a);

    $("#pagination").pagination({
        dataSource: function (done) {
            $.ajax({
                type: "GET",
                url: url,
                success: function (response) {
                    done(response.data);
                }
            });
        },
        pageSize: 15,
        className: "paginationjs-theme-blue",
        ajax: {
            beforeSend: function () {
                container.prev().html("Loading data");
            }
        },
        callback: function (response, pagination) {
            $(".container-chart").html("");
            DatasLogger = [];
            response.forEach(function (data, index) {
                let temp = GetLogger.GetDataLoggers({
                    nojs: data.nojs,
                    limit: 36,
                    url: log,
                    single: false,
                    multi: true,
                    auth: auth
                });
                DatasLogger.push({
                    update: false,
                    nojs: data.nojs,
                    data: temp
                });
                $(
                    ".container-chart"
                ).append(`<div class="container-item" id="container-item-${data.nojs}">
                                                <div class="js row" id="js-${data.nojs}">
                                                    <div class="col border">
                                                        ${data.nojs} ${data.site}
                                                        <span class="float-right mr-3">
                                                            ${data.lc}
                                                        </span>
                                                    </div>
                                                    <div class="">
                                                        <span class="float-right mr-3" id="bv-${data.nojs}">
                                                        </span>

                                                        <span class="float-right mr-4" id="pms-${data.nojs}">
                                                        </span>
                                                    </div>
                                                </div>

                                                <div class="eh">
                                                    <canvas id="eh1-${data.nojs}"></canvas>
                                                </div>

                                                <div class="eh">
                                                    <canvas id="eh2-${data.nojs}"></canvas>
                                                </div>

                                                <div class="bv">
                                                    <canvas id="batt_volt1-${data.nojs}"></canvas>
                                                </div>

                                                <div class="edl">
                                                    <canvas id="edl1-${data.nojs}"></canvas>
                                                </div>

                                                <div class="edl">
                                                    <canvas id="edl2-${data.nojs}"></canvas>
                                                </div>
                                        </div>`);
                SetidChart(DatasLogger[index]);
            });
        }
    });

    function SetidChart(data) {
        let pms = 0,
            batt_volt1 = 0;
        let temp = data.data[0];
        chartEh1 = document.getElementById(`eh1-${data.nojs}`).getContext("2d");
        chartEh2 = document.getElementById(`eh2-${data.nojs}`).getContext("2d");
        chartBv = document
            .getElementById(`batt_volt1-${data.nojs}`)
            .getContext("2d");
        chartedl1 = document.getElementById(`edl1-${data.nojs}`).getContext("2d");
        chartedl2 = document.getElementById(`edl2-${data.nojs}`).getContext("2d");

        renderChart({
            data: temp.eh1,
            label: temp.label,
            color: temp.color_eh1,
            chart: chartEh1,
            min: 0,
            max: 55
        });
        renderChart({
            data: temp.eh2,
            label: temp.label,
            color: temp.color_eh2,
            chart: chartEh2,
            min: 0,
            max: 55
        });
        renderChart({
            data: temp.batt_volt1,
            label: temp.label,
            color: temp.color_batt_volt1,
            chart: chartBv,
            min: 0,
            max: 30
        });
        renderChart({
            data: temp.edl1,
            label: temp.label,
            color: temp.color_edl1,
            chart: chartedl1,
            min: -40,
            max: 0
        });
        renderChart({
            data: temp.edl2,
            label: temp.label,
            color: temp.color_edl2,
            chart: chartedl2,
            min: -40,
            max: 0
        });

        for (let i = temp.pms.length; i > 0; i--) {
            const e = temp.pms[i];
            if (e != null) {
                pms = e;
                break;
            }
        }
        for (let i = temp.bv.length; i > 0; i--) {
            const e = temp.bv[i];
            if (e != null) {
                batt_volt1 = e;
                break;
            }
        }
        $(`#bv-${data.nojs}`).text(batt_volt1.toFixed(1));
        if (batt_volt1.toFixed(1) >= 54.6) {
            $(`#bv-${data.nojs}`).removeClass("bg-warning");
            $(`#bv-${data.nojs}`).addClass("bg-success");
        } else if (batt_volt1.toFixed(1) <= 52.0) {
            $(`#bv-${data.nojs}`).removeClass("bg-success");
            $(`#bv-${data.nojs}`).addClass("bg-warning");
        }

        $(`#pms-${data.nojs}`).text(pms);
        if (pms <= 15) {
            // $(`#pms-${data.nojs}`).css("background-color", "yellow");
            $(`#pms-${data.nojs}`).addClass("bg-warning");
        } else {
            $(`#pms-${data.nojs}`).removeClass("bg-warning");

        }
    }

    setInterval(function () {
        for (let i = 0; i < DatasLogger.length; i++) {
            const data = DatasLogger[i];
            DatasLogger[i] = GetDataSingle(data, log, auth);
            if (DatasLogger[i].update) {
                SetidChart(DatasLogger[i], i);
                DatasLogger[i].update = false;
            }
        }
    }, 1000 * 50);
});
