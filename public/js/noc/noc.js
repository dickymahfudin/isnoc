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
        pageSize: 15,
        className: 'paginationjs-theme-blue',
        ajax: {
            beforeSend: function () {
                container.prev().html('Loading data');
            }
        },
        callback: function (response, pagination) {
            $('.container-chart').html('');
            js = [];
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
                                                <div class="js" id="js-${data.nojs}">${data.nojs}</div>

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

                                                <div class="edl test">
                                                    <canvas id="edl2-${data.nojs}"></canvas>
                                                </div>
                                        </div>`);
                SetidChart(DatasLogger, index);
            });
        }
    });

    function SetidChart(data, index) {
        // console.log(data);
        const temp = data[index];
        temp.data.forEach(data => {

            let chartEh1 = document.getElementById(`eh1-${data.nojs[0]}`).getContext('2d');
            let chartEh2 = document.getElementById(`eh2-${data.nojs[0]}`).getContext('2d');
            let chartBv = document.getElementById(`batt_volt1-${data.nojs[0]}`).getContext('2d');
            let chartedl1 = document.getElementById(`edl1-${data.nojs[0]}`).getContext('2d');
            let chartedl2 = document.getElementById(`edl2-${data.nojs[0]}`).getContext('2d');

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
                min: 0,
                max: 40
            });
            renderChart({
                data: data.edl2,
                label: data.label,
                color: data.color_edl2,
                chart: chartedl2,
                min: 0,
                max: 40
            });
        });

    }

    function renderChart(data) {
        console.log(data);

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

    // function chart(js) {
    //     $.ajax({
    //         type: 'GET',
    //         url: log,
    //         data: {
    //             js: js,
    //             limit: '37'
    //         },
    //         dataType: 'json',
    //         success: function (response) {
    //             let hedl1;
    //             let hedl2;
    //             let heh1;
    //             let heh2;
    //             let hbv;
    //             $.each(response, function (index, item) {
    //                 if (item.eh1 == null) {
    //                     eh1.push(100);
    //                 } else {
    //                     heh1 = Math.abs(Math.round(dataMap(item.eh1, 0, mxEh, 0, 50)));
    //                     if (heh1 > 50) heh1 = 50;
    //                     eh1.push(heh1);
    //                 }

    //                 if (item.eh2 == null) {
    //                     eh2.push(100);
    //                 } else {
    //                     heh2 = Math.abs(Math.round(dataMap(item.eh2, 0, mxEh, 0, 50)));
    //                     if (heh2 > 50) heh2 = 50;
    //                     eh2.push(heh2);
    //                 }

    //                 if (item.batt_volt1 == null) {
    //                     batt_volt1.push(100);
    //                 } else {
    //                     hbv = Math.round(dataMap(item.batt_volt1, 45, 55, 0, 20));
    //                     if (hbv > 20) hbv = 20;
    //                     batt_volt1.push(hbv);
    //                 }

    //                 if (item.edl1 == null) {
    //                     edl1.push(100);
    //                 } else {
    //                     hedl1 = Math.abs(Math.round(dataMap(item.edl1, 0, mxEdl, 0, 30)));
    //                     if (hedl1 > 30) hedl1 = 30;
    //                     edl1.push(hedl1);
    //                 }

    //                 if (item.edl2 == null) {
    //                     edl2.push(100);
    //                 } else {
    //                     hedl2 = Math.abs(Math.round(dataMap(item.edl2, 0, mxEdl, 0, 30)));
    //                     if (hedl2 > 30) hedl2 = 30;
    //                     edl2.push(hedl2);
    //                 }
    //             });
    //             if (response.length <= 36) {
    //                 for (let i = 0; i < 36 - response.length; i++) {
    //                     edl1.push(100);
    //                     edl2.push(100);
    //                     eh1.push(100);
    //                     eh2.push(100);
    //                     batt_volt1.push(100);
    //                 }
    //             }
    //             edl1.reverse();
    //             edl2.reverse();
    //             batt_volt1.reverse();
    //             eh1.reverse();
    //             eh2.reverse();
    //             for (let i = 0; i < 36; i++) {
    //                 $('.chart-eh1-' + js).append('<div class = "item itemEh-' + eh1[i] + '"></div></div></div>');
    //                 $('.chart-eh2-' + js).append('<div class = "item itemEh-' + eh2[i] + '"></div></div></div>');
    //                 $('.chart-battVolt-' + js).append(
    //                     '<div class = "item itemBv-' + batt_volt1[i] + '"></div></div></div>'
    //                 );
    //                 $('.chart-edl1-' + js).append('<div class = "item itemEdl-' + edl1[i] + '"></div></div></div>');
    //                 $('.chart-edl2-' + js).append('<div class = "item itemEdl-' + edl2[i] + '"></div></div></div>');
    //             }
    //             eh1 = [];
    //             eh2 = [];
    //             batt_volt1 = [];
    //             edl1 = [];
    //             edl2 = [];
    //         }
    //     });
    // }

    // function dataMap(value, fromLow, fromHigh, toLow, toHigh) {
    //     fromSpan = fromHigh - fromLow;
    //     toSpan = toHigh - toLow;

    //     valueScaled = (value - fromLow) / fromSpan;

    //     return toLow + valueScaled * toSpan;
    // }
    // setInterval(function () {
    //     console.log(DatasLogger);
    //     //     for (let i = 0; i < DatasLogger.length; i++) {
    //     //         const data = DatasLogger[i];
    //     //         console.log(data);
    //     //     }
    // }, 1000 * 4);
});
