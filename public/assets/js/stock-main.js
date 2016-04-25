var PLOT_range = "year";
var PLOT_indicators = "ema";
var interval = 1000;
var execuctionEngineAddr = "http://localhost:8080/stockcruncher-ee/data/";

function noIndicatorsPlot() { 
    PLOT_indicators="none"; 
    PLOT_update(); 
}

function maPlot() {
    PLOT_indicators = "ma";
    PLOT_update();
}

function emaPlot() {
    PLOT_indicators = "ema";
    PLOT_update();
}

function rsiPlot() {
    PLOT_indicators = "rsi";
    PLOT_update();
}

function PLOT_update() {
	if(PLOT_range == "week") pastWeekPlot();
	else if(PLOT_range == "month") pastMonthPlot();
	else if(PLOT_range == "6month") past6MonthsPlot();
	else if(PLOT_range == "year") pastYearPlot();
}

function pastWeekPlot() {
    PLOT_range = "week";
    console.debug("pastWeekPlot");
    doAjaxHist($.now() - 7 * 24 * 3600 * 1000, $.now(), 2);
}

function pastMonthPlot() {
    PLOT_range = "month";
    console.debug("pastMonthPlot");
    doAjaxHist($.now() - 30 * 24 * 3600 * 1000, $.now(), 5);
}

function past6MonthsPlot() {
    PLOT_range = "6month";
    console.debug("past6MonthsPlot");
    doAjaxHist($.now() - 6 * 30 * 24 * 3600 * 1000, $.now(), 10);
}

function pastYearPlot() {
    PLOT_range = "year";
    console.debug("pastYearPlot");
    doAjaxHist($.now() - 365 * 24 * 3600 * 1000, $.now(), 30);
}

function doAjaxHist(startDate_, endDate_, maWindow_) {
    $.ajax({
        type: 'GET',
        url: execuctionEngineAddr+'getHistoricalData',
        data: {
            stockid: stockid,
            symbol: symbol,
            startDate: startDate_,
            endDate: endDate_,
            indicator: PLOT_indicators,
            maWindow: maWindow_
        },
        dataType: 'json',
        success: function(data) {
            console.log(data.messagel);
            var json2_data = JSON.stringify(eval("(" + data.messagel + ")"));
            var json2_obj = jQuery.parseJSON(json2_data);
            //drawVisualization(json2_obj.values);
        }
    });
}




function PLOT_title_update() {
    document.getElementById('PLOT_title').innerHTML = "" + PLOT_range + " Range Plot  (Indicator: " + PLOT_indicators + ")";
}

function drawVisualization(datapoints) {
    PLOT_title_update();
    // Populate the data table.
    // var datapoints = [
    //     ['Mon', 20, 28, 38, 45, 30],
    //     ['Tue', 31, 38, 55, 66, 50],
    //     ['Wed', 50, 55, 77, 80, 45],
    //     ['Thu', 77, 77, 66, 50, 65],
    //     ['Fri', 68, 66, 22, 15, 80]
    // // Treat first row as data as well.
    // ];
    var dataTable = google.visualization.arrayToDataTable(datapoints, true);

    // Draw the chart.
    var chart = new google.visualization.ComboChart(document.getElementById('chart_historic_2'));
    chart.draw(dataTable, {
        legend: 'none',
        width: 775,
        height: 350,
        seriesType: 'candlesticks',
        series: {
            0: {
                color: "Black"
            },
            1: {
                type: 'line'
            }
        }
    });
}


function realTimePlot() {
    console.debug("Real Time Data");
    doAjax($.now() - 3 * 24 * 3600 * 1000, $.now());
}

function doAjax(startDate_, endDate_) {
    $.ajax({
        type: 'GET',
        url: execuctionEngineAddr+'getRealTimeData',
        data: {
            stockid: stockid,
            symbol: symbol,
            startDate: startDate_,
            endDate: endDate_
        },
        dataType: 'json',
        success: function(data) {
            //console.log(data.messagel);
            var json_data = JSON.stringify(eval("(" + data.messagel + ")"));
            var json_obj = jQuery.parseJSON(json_data);
            drawNewPlot(json_obj);
        }
    });
}

function drawHistoricChart(datapoints) {
    PLOT_title_update();
    //$('graphcontainer1').html("");
    //$('graphcontainer2').html("<div id='chart_historic' style='width: 900px; height: 500px;'></div>");

    var datah = google.visualization.arrayToDataTable(datapoints, true);
    var options = {
        legend: 'none',
        height: '350',
        weight: '775'
    };

    var charth = new google.visualization.CandlestickChart(document.getElementById('chart_historic'));

    console.debug("datah    " + datah);
    charth.draw(datah, options);
}


function drawNewPlot(datapoints) {
    $('#realtime-chart svg').text("");
    var testdata = datapoints.map(function(series) {
        series.values = series.values.map(function(d) {
            return {
                x: d[0],
                y: d[1]
            }
        });
        return series;
    });

    var chart;
    nv.addGraph(function() {
        chart = nv.models.linePlusBarChart()
            .margin({
                top: 50,
                right: 60,
                bottom: 30,
                left: 70
            })
            .x(function(d, i) {
                return i
            })
            .legendRightAxisHint(' (right axis)')
            .color(d3.scale.category10().range());

            chart.xAxis.tickFormat(function(d) {
                var dx = testdata[0].values[d] && testdata[0].values[d].x || 0;
                return dx ? d3.time.format('%x %X')(new Date(dx)) : '';
            })
            .showMaxMin(false);

        chart.y1Axis.tickFormat(function(d) {
            return '$' + d3.format(',f')(d)
        });
        chart.bars.forceY([0]).padData(false);

        chart.xAxis.showMaxMin(false);
        chart.x2Axis.showMaxMin(false);

        d3.select('#realtime-chart svg')
            .datum(testdata)
            .transition().duration(500).call(chart);

        nv.utils.windowResize(chart.update);

        chart.dispatch.on('stateChange', function(e) {
            nv.log('New State:', JSON.stringify(e));
        });

        return chart;
    });
};