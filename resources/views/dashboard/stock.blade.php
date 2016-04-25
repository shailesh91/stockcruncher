@extends('layouts.app')
@section('stylesheets')
<link href="{{ asset("assets/vendor/nv.d3/nv.d3.min.css")}}" rel="stylesheet">
@endsection
@section('content')
<div class="row">
	<div class="col-sm-12">
		<div class="panel ">
			<div class="panel-body">
				<div class="row">
				  	<div class="col-sm-3">
				  		<h2 style="margin-top: 10px">{{$st[0]->stock_name}}(<strong>{{$st[0]->stock_symbol}}</strong>)</h2>
				  		<h4>{{$st[0]->category->category_name}}</h4>
				  	</div>
				  	<div class="col-sm-9"></div>
				  </div>
			</div>
		</div>
	</div>
</div>
<div class="row">
    <div class="col-sm-9">
    	<div class="row">
          <div class="col-sm-12">
          	<ul class="nav nav-pills">
			    <li><a onclick="pastWeekPlot()" href="#">Past week</a></li>
			    <li><a onclick="pastMonthPlot()" href="#">Past month</a></li>
			    <li><a onclick="past6MonthsPlot()" href="#">Past 6 months</a></li>
			    <li><a onclick="pastYearPlot()" href="#">Past year</a></li>
			    <li><a onclick="realTimePlot()" href="#">Get Real Time Data</a></li>
			</ul>
			<span style="font-size:13px; font-weight:bold; margin-left:10px">Indicators</span>
			<ul class="nav nav-pills">
			    <li><a onclick="noIndicatorsPlot()" href="#">No Indicators</a></li>
			    <li><a onclick="maPlot()" href="#">Moving Average</a></li>
			    <li><a onclick="emaPlot()" href="#">Exponential Moving Average</a></li>
			    <li><a onclick="rsiPlot()" href="#">RSI</a></li>
			</ul>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-12">
            <canvas id="myChart" height="200"></canvas>
          </div>
        </div>
	</div>
	<div class="col-sm-3">
		<div class="panel panel-primary">
		  <div class="panel-heading">
		    <h3 class="panel-title">News about {{$st[0]->stock_name}}</h3>
		  </div>
		  <div class="panel-body">
		  	<div id="news_content"  style="text-align: justify;">Loading...</div>
		  	<div id="news_branding"  style="font-color:#aaa font-size:12px; margin-left:0px; border-spacing:6px;"></div>
		  </div>
		</div>
	</div>
</div>
@endsection
@section('scripts')
<script type="text/javascript">
	stockid = {{$st[0]->id}};
	symbol = "{{$st[0]->stock_symbol}}";
</script>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript" src="{{ asset("assets/js/stock-main.js")}}"></script>
<script type="text/javascript" src="{{ asset("assets/vendor/chart.js/Chart.js")}}"></script>
<script type="text/javascript">
	google.load("visualization", "1", {packages:["corechart"]});
	google.load("search", "1");
</script>
<!--
<script type="text/javascript">
function doPoll(){
$.getJSON( "getRealTimeData", {
    stockid:11
  }).done(function( data ) {
      console.log(data);
      setTimeout(doPoll,5000);
    });
}</script>
<script src="{{ asset("assets/vendor/nv.d3/nv.d3.min.js")}}"></script>-->


<!--Start Load News about the Stock from Google News-->
<script type="text/javascript">
var newsSearch;
window.onload = function() {
    newsSearch = new google.search.NewsSearch();
    newsSearch.setSearchCompleteCallback(this, searchComplete, null);
    newsSearch.execute("{{$st[0]->stock_name}}");
    google.search.Search.getBranding('news_branding');
    realTimePlot();
}
function searchComplete() {
  	var news_content_el = document.getElementById('news_content');
	news_content_el.innerHTML = '';
    if (newsSearch.results && newsSearch.results.length > 0) {
      for (var i = 0; i < newsSearch.results.length; i++) {
        var p = document.createElement('p');
        var a = document.createElement('a');
        a.href=decodeURIComponent(newsSearch.results[i].url);
        a.innerHTML = newsSearch.results[i].title;
        p.appendChild(a);
        news_content_el.appendChild(p);
      }
    }
  }
</script>
<!--End Load News about the Stock from Google News-->
@endsection