import {
    GetData
} from './getdata.js';

$(document).ready(function () {
    let js = [];
    let DatasLogger = [];
    let GetLogger = new GetData();

    let me = $('#pagination'),
        url = me.attr('dism'),
        log = me.attr('dismlog');

    // let a = GetLogger.GetDataLoggers({
    //     nojs: "JS10",
    //     limit: 4,
    //     url: "http://127.0.0.1:8000/api/logger"
    // });

    // console.log(a);

    $('#pagination').pagination({
        dataSource: function (done) {
            $.ajax({
                type: 'GET',
                url: url,
                success: function (response) {
                    done(response);
                }
            });
        },
        pageSize: 3,
        className: 'paginationjs-theme-blue',
        ajax: {
            beforeSend: function () {
                container.prev().html('Loading data');
            }
        },
        callback: function (response, pagination) {
            $('.container-chart').html('');
            DatasLogger = [];
            response.forEach(function (data, index) {
                let temp = GetLogger.GetDataLoggers({
                    nojs: data.nojs,
                    limit: 37,
                    url: "http://127.0.0.1:8000/api/logger"
                })
                DatasLogger.push({
                    data: temp
                });
                $('.container-chart').append(`<div class="container-item" id="container-item-${data.nojs}">
                                                <div class="js row" id="js-${data.nojs}">
                                                    <div class="col border text-left text-xl">
                                                        ${data.nojs} ${data.site}
                                                    </div>
                                                    <div class="col border">
                                                        ${data.lc}
                                                        <span class="ml-5" id="pms-${data.nojs}">
                                                        </span>

                                                        <span class="float-right" id="bv-${data.nojs}">
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
                SetidChart(DatasLogger, index, data.nojs);
            });
        }
    });

    function SetidChart(data, index, nojs) {
        let pms_state = 0,
            batt_volt1 = 0;

        const temp = data[index];
        temp.data.forEach(function (data, index) {
            let chartEh1 = document.getElementById(`eh1-${nojs}`).getContext('2d');
            let chartEh2 = document.getElementById(`eh2-${nojs}`).getContext('2d');
            let chartBv = document.getElementById(`batt_volt1-${nojs}`).getContext('2d');
            let chartedl1 = document.getElementById(`edl1-${nojs}`).getContext('2d');
            let chartedl2 = document.getElementById(`edl2-${nojs}`).getContext('2d');

            renderChart({
                data: data.eh1,
                label: data.label,
                color: data.color_eh1,
                chart: chartEh1,
                min: 0,
                max: 60
            });
            renderChart({
                data: data.eh2,
                label: data.label,
                color: data.color_eh2,
                chart: chartEh2,
                min: 0,
                max: 60
            });
            renderChart({
                data: data.batt_volt1,
                label: data.label,
                color: data.color_batt_volt1,
                chart: chartBv,
                min: 0,
                max: 30
            });
            renderChart({
                data: data.edl1,
                label: data.label,
                color: data.color_edl1,
                chart: chartedl1,
                min: -40,
                max: 0
            });
            renderChart({
                data: data.edl2,
                label: data.label,
                color: data.color_edl2,
                chart: chartedl2,
                min: -40,
                max: 0
            });

            for (let i = data.pms_state.length; i > 0; i--) {
                const e = data.pms_state[i];
                if (e != null) {
                    pms_state = e;
                    break;
                }
            }

            for (let i = data.bv.length; i > 0; i--) {
                const e = data.bv[i];
                if (e != null) {
                    batt_volt1 = e;
                    break;
                }
            }


        });
        $(`#bv-${nojs}`).text(batt_volt1.toFixed(1));
        if (batt_volt1.toFixed(1) >= 54.6) {
            $(`#bv-${nojs}`).css("background-color", "green");
        } else if (batt_volt1.toFixed(1) <= 52.0) {
            $(`#bv-${nojs}`).css("background-color", "yellow");
        }

        $(`#pms-${nojs}`).text(pms_state);
        if (pms_state <= 15) {
            $(`#pms-${nojs}`).css("background-color", "yellow");
        }
    }

    function renderChart(data) {
        let chart = new Chart(data.chart, {
            type: 'bar',
            data: {
                labels: data.label,
                datasets: [{
                    barPercentage: 1.0,
                    data: data.data,
                    backgroundColor: data.color,
                }]
            },
            options: {
                legend: {
                    display: false
                },
                tooltips: {
                    mode: 'false',
                },
                maintainAspectRatio: false,
                responsive: true,
                scales: {
                    yAxes: [{
                        gridLines: {
                            display: true
                        },
                        ticks: {
                            min: data.min,
                            max: data.max,
                            display: false,
                            beginAtZero: true
                        }
                    }],
                    xAxes: [{
                        barPercentage: 1,
                        categorySpacing: 3,
                        gridLines: {
                            display: false
                        },
                        ticks: {
                            display: false
                        }
                    }]
                }
            }
        });
        return chart;
    }

    // setInterval(function () {
    //     console.log(DatasLogger);
    //     //     for (let i = 0; i < DatasLogger.length; i++) {
    //     //         const data = DatasLogger[i];
    //     //         console.log(data);
    //     //     }
    // }, 1000 * 4);

    // setInterval(function () {
    //     refreshData(setchart(test1, data1, 'rgba(22, 145, 13, 1)'), 36, data1.createSingle());
    //     refreshData(setchart(test2, data2, 'rgba(22, 145, 13, 1)'), 36, data2.createSingle());
    //     refreshData(setchart(test3, data3, 'rgba(52, 67, 203, 1)'), 36, data2.createSingle());
    //     refreshData(setchart(test4, data4, 'rgba(230, 0, 0, 1)'), 36, data2.createSingle());
    //     refreshData(setchart(test5, data5, 'rgba(230, 0, 0, 1)'), 36, data2.createSingle());
    // }, 1000);
});
