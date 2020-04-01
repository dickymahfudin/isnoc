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
                limit: data.limit
            },
            async: false,
            dataType: "json",
            success: function (response) {
                log.push(DataProcessing(response));
            }
        });
        return log;
    }

    Chart(data) {
        let chartEh1 = $().getContext('2d');
        let chartEh2 = $().getContext('2d');
        let chartBv = $().getContext('2d');
        let chartedl1 = $().getContext('2d');
        let chartedl2 = $().getContext('2d');

    }

}

function DataProcessing(data) {
    let mxEh = 400;
    let mxEdl = 100;
    let mxBv = 6000;
    let chart = [];
    let time_local, eh1, eh2, battVolt1, edl1, edl2, pms_state, hedl1;
    let colorEh1, colorEh2, colorBattVolt1, colorEdl1, colorEdl2;
    let hedl2, heh1, heh2, hbv;
    const green = 'rgba(22, 145, 13, 1)';
    const blue = 'rgba(52, 67, 203, 1)';
    const red = 'rgba(230, 0, 0, 1)';
    const black = 'rgba(0, 0, 0, 1)';

    data.forEach(data => {
        if (data.eh1 == null) {
            eh1 = 100;
            colorEh1 = black;
        } else {
            heh1 = Math.abs(Math.round(dataMap(data.eh1, 0, mxEh, 0, 50)));
            if (heh1 > 50) heh1 = 50;
            eh1 = heh1;
            colorEh1 = green;
        }

        if (data.eh2 == null) {
            eh2 = 100;
            colorEh2 = black;
        } else {
            heh2 = Math.abs(Math.round(dataMap(data.eh2, 0, mxEh, 0, 50)));
            if (heh2 > 50) heh2 = 50;
            eh2 = heh2;
            colorEh2 = green;
        }

        if (data.battVolt1 == null) {
            battVolt1 = 100;
            colorBattVolt1 = black;
        } else {
            hbv = Math.round(dataMap(data.battVolt1, 45, 55, 0, 20));
            if (hbv > 20) hbv = 20;
            battVolt1 = hbv;
            colorBattVolt1 = blue;
        }

        if (data.edl1 == null) {
            edl1 = 100;
            colorEdl1 = black;
        } else {
            hedl1 = Math.abs(Math.round(dataMap(data.edl1, 0, mxEdl, 0, 30)));
            if (hedl1 > 30) hedl1 = 30;
            edl1 = hedl1;
            colorEdl1 = red;
        }

        if (data.edl2 == null) {
            edl2 = 100;
            colorEdl2 = black;
        } else {
            hedl2 = Math.abs(Math.round(dataMap(data.edl2, 0, mxEdl, 0, 30)));
            if (hedl2 > 30) hedl2 = 30;
            edl2 = hedl2;
            colorEdl2 = red;
        }
        chart.push({
            time_local: data.time_local,
            nojs: data.nojs,
            eh1: eh1,
            eh2: eh2,
            batt_volt1: battVolt1,
            edl1: edl1,
            edl2: edl2,
            pms_state: data.pms_state,
            color_eh1: colorEh1,
            color_eh2: colorEh2,
            color_batt_volt1: colorBattVolt1,
            color_edl1: colorEdl1,
            color_edl2: colorEdl2
        });
    });
    return chart;
}

function dataMap(value, fromLow, fromHigh, toLow, toHigh) {
    let fromSpan = fromHigh - fromLow;
    let toSpan = toHigh - toLow;

    let valueScaled = (value - fromLow) / fromSpan;

    return toLow + valueScaled * toSpan;
}
export {
    GetData
};
