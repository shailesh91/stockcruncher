var PLOT_range = "Year";
var PLOT_indicators = "ema";
var interval = 1000;
var execuctionEngineAddr = "http://localhost:8080/stockcruncher-ee/data/";
var chart;

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
    if(PLOT_range == "Week") pastWeekPlot();
	else if(PLOT_range == "Month") pastMonthPlot();
	else if(PLOT_range == "6month") past6MonthsPlot();
	else if(PLOT_range == "Year") pastYearPlot();
    $('#indicator-selector>li.active').removeClass('active');
    $('#'+PLOT_indicators+'-indicator-btn').addClass('active');
}

function pastWeekPlot() {
    $('ul#range-selector>li.active').removeClass('active');
    PLOT_range = "Week";
    console.debug("pastWeekPlot");
    $('#week-hist-btn').addClass('active');
    doAjaxHist($.now() - 7 * 24 * 3600 * 1000, $.now(), 2);
}

function pastMonthPlot() {
    $('ul#range-selector>li.active').removeClass('active');
    PLOT_range = "Month";
    console.debug("pastMonthPlot");
    $('#month-hist-btn').addClass('active');
    doAjaxHist($.now() - 30 * 24 * 3600 * 1000, $.now(), 5);
}

function past6MonthsPlot() {
    $('ul#range-selector>li.active').removeClass('active');
    PLOT_range = "6month";
    console.debug("past6MonthsPlot");
    $('#6month-hist-btn').addClass('active');
    doAjaxHist($.now() - 6 * 30 * 24 * 3600 * 1000, $.now(), 10);
}

function pastYearPlot() {
    $('ul#range-selector>li.active').removeClass('active');
    PLOT_range = "Year";
    console.debug("pastYearPlot");
    $('#year-hist-btn').addClass('active');
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
            var json2_data = JSON.stringify(eval("(" + data.messagel + ")"));
            var json2_obj = jQuery.parseJSON(json2_data);
            drawHistoricPlot(json2_obj.values);
        }
    });
}

function drawHistoricPlot(datapoints) {
    var dataTable = google.visualization.arrayToDataTable(datapoints, true);
    // Draw the chart.
    var chart = new google.visualization.ComboChart(document.getElementById('chart_historic'));
    if(PLOT_indicators=="none"){
        chart.draw(dataTable, {
            legend: 'none',
            height: 350,
            seriesType: 'candlesticks',
            backgroundColor: 'transparent',
            vAxes: {0: {logScale: false},1: {logScale: false}},
            series: {
                0: {
                    color: "orange",
                    targetAxisIndex:0
                },
                1:{
                    color: "#4682b4",
                    type: 'bars',
                    targetAxisIndex:1
                }
            }
        });
    }else{   
        chart.draw(dataTable, {
            legend: 'none',
            height: 350,
            seriesType: 'candlesticks',
            backgroundColor: 'transparent',
            vAxes: {0: {logScale: false},1: {logScale: false}},
            series: {
                0: {
                    color: "orange",
                    targetAxisIndex:0
                },
                1: {
                    type: 'line',
                    targetAxisIndex:0
                },
                2:{
                    color: "#4682b4",
                    type: 'bars',
                    targetAxisIndex:1
                }
            }
        });
    }
}

function realTimePlot() {
    console.debug("Real Time Data");
    var d = new Date();
    var output =  new Date(d.getFullYear(), d.getMonth(), d.getDate()).getTime();
    doAjax(output - 24 * 3600 * 1000 , output + 24 * 3600 * 1000 - 1);
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
            chart.options({height: 350});

        chart.y1Axis.tickFormat(function(d) {
            return d3.format(',f')(d)
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
