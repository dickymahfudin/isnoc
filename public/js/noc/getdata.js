class GetData {
    GetDataLoggers(data) {
        var log = [];
        $.ajax({
            type: "GET",
            url: data.url,
            beforeSend: function (xhr) {
                xhr.setRequestHeader(
                    "Authorization",
                    "Bearer Afidha1pHYJIEOSB08TSrPQ9v2dTFFcPHx1bCFc7lZEQD2BXPBtbNoYEcGHMhKVhnk9MwwwJTOLSK4vR"
                );
            },
            data: {
                nojs: data.nojs,
                limit: data.limit,
                calculate: true
            },
            async: false,
            dataType: "json",
            success: function (response) {
                console.log(response);
                log.push(DataProcessing(response));
            }
        });
        return log;
    }

}

function DataProcessing(data) {
    let mxEh = 400;
    let mxEdl = 100;
    let edl1 = [],
        time_local = [],
        edl2 = [],
        eh1 = [],
        eh2 = [],
        batt_volt1 = [],
        bv = [],
        pms_state = [],
        colorEh1 = [],
        colorEh2 = [],
        colorBattVolt1 = [],
        colorEdl1 = [],
        colorEdl2 = [],
        nojs = [],
        labels = [];

    let hedl1, hedl2, heh1, heh2, hbv;
    const green = 'rgba(22, 145, 13, 1)';
    const blue = 'rgba(52, 67, 203, 1)';
    const red = 'rgba(230, 0, 0, 1)';
    const black = 'rgba(0, 0, 0, 1)';

    data.forEach(function (data, index) {
        if (data.eh1 == null) {
            eh1.push(100);
            colorEh1.push(black);
        } else {
            heh1 = Math.abs(Math.round(dataMap(data.eh1, 0, mxEh, 0, 50)));
            if (heh1 > 50) heh1 = 50;
            eh1.push(heh1);
            colorEh1.push(green);
        }

        if (data.eh2 == null) {
            eh2.push(100);
            colorEh2.push(black);
        } else {
            heh2 = Math.abs(Math.round(dataMap(data.eh2, 0, mxEh, 0, 50)));
            if (heh2 > 50) heh2 = 50;
            eh2.push(heh2);
            colorEh2.push(green);
        }

        if (data.batt_volt1 == null) {
            batt_volt1.push(100);
            colorBattVolt1.push(black);
        } else {
            hbv = Math.round(dataMap(data.batt_volt1, 45, 55, 0, 20));
            if (hbv > 20) hbv = 20;
            batt_volt1.push(hbv);
            colorBattVolt1.push(blue);
        }

        if (data.edl1 == null) {
            edl1.push(100 * -1);
            colorEdl1.push(black);
        } else {
            hedl1 = Math.abs(Math.round(dataMap(data.edl1, 0, mxEdl, 0, 30)));
            if (hedl1 > 30) hedl1 = 30;
            edl1.push(hedl1 * -1);
            colorEdl1.push(red);
        }

        if (data.edl2 == null) {
            edl2.push(100 * -1);
            colorEdl2.push(black);
        } else {
            hedl2 = Math.abs(Math.round(dataMap(data.edl2, 0, mxEdl, 0, 30)));
            if (hedl2 > 30) hedl2 = 30;
            edl2.push(hedl2 * -1);
            colorEdl2.push(red);
        }
        time_local.push(data.time_local);
        pms_state.push(data.pms);
        nojs.push(data.nojs);
        bv.push(data.batt_volt1);
        labels.push(index);
    });
    if (data.length <= 36) {
        for (let i = 0; i < 36 - data.length; i++) {
            time_local.push(100);
            eh1.push(100);
            eh2.push(100);
            batt_volt1.push(100);
            edl1.push(100 * -1);
            edl2.push(100 * -1);
            labels.push(i);
            colorEh1.push(black);
            colorEh2.push(black);
            colorBattVolt1.push(black);
            colorEdl1.push(black);
            colorEdl2.push(black);

        }
    }
    let chart = {
        time_local: time_local.reverse(),
        nojs: nojs.reverse(),
        eh1: eh1.reverse(),
        eh2: eh2.reverse(),
        batt_volt1: batt_volt1.reverse(),
        edl1: edl1.reverse(),
        edl2: edl2.reverse(),
        pms_state: pms_state.reverse(),
        bv: bv.reverse(),
        color_eh1: colorEh1.reverse(),
        color_eh2: colorEh2.reverse(),
        color_batt_volt1: colorBattVolt1.reverse(),
        color_edl1: colorEdl1.reverse(),
        color_edl2: colorEdl2.reverse(),
        label: labels
    };
    return chart;
}

function dataMap(value, fromLow, fromHigh, toLow, toHigh) {
    let fromSpan = fromHigh - fromLow;
    let toSpan = toHigh - toLow;

    let valueScaled = (value - fromLow) / fromSpan;

    return toLow + valueScaled * toSpan;
}

function setchart(param, data, color, min, max) {
    let chart = new Chart(param, {
        type: 'bar',
        data: {
            labels: data.label,
            datasets: [{
                barPercentage: 1.0,
                data: data.data,
                backgroundColor: color,
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
                        display: false
                    },
                    ticks: {
                        min: min,
                        max: max,
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

export {
    GetData
};
