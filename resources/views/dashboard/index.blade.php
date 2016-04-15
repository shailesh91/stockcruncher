@extends('layouts.app')
@section('stylesheets')
<link href="assets/vendor/select2/css/select2.min.css" rel="stylesheet" />
<style type="text/css">
.select2-result-repository { padding-top: 4px; padding-bottom: 3px; }
</style>
@endsection
@section('content')
<div class="container">
    @include('common.errors')
    @include('flash::message')
    <div class="row">
        <div class="col-md-9">
            <div class="panel panel-default">
                <div class="panel-heading">Portfolio Dashboard</div>
                <div class="panel-body">
                  {!! Form::open(array('route' => 'addStocks')) !!}
                    {!! Form::token() !!}
                    <div class="form-group">
                      <label for="portfolio-category-selector">Select Category for Stock Listing</label>
                      <select class="portfolio-category-selector form-control" name="category[]" multiple="multiple" id="portfolio-category-selector">
                        @foreach($categories as $category)
                          <option value="{{$category->id}}">{{$category->category_name}}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="portfolio-stock-selector">Select Stocks</label>
                      <select class="portfolio-stock-selector form-control" name="stocks[]" multiple="multiple" id="portfolio-stock-selector"></select>
                    </div>
                    <button type="submit" class="btn btn-success btn-block">Add Stocks</button>
                  {!! Form::close() !!}
                </div>
            </div>
            <div class="row">
              <div class="col-sm-12">

              </div>
            </div>
        </div>
        <div class="col-md-3">
          <div class="panel panel-default">
              <div class="panel-heading">Top Gainers</div>
              <div class="panel-body"></div>
          </div>
          <div class="panel panel-default">
              <div class="panel-heading">Top Losers</div>
              <div class="panel-body"></div>
          </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script src="assets/vendor/select2/js/select2.full.min.js"></script>
<script type="text/javascript">
  $.fn.select2.amd.require([
    "select2/core",
    "select2/utils",
    "select2/compat/matcher"
  ], function (Select2, Utils, oldMatcher) {
    function formatStock(stocks) {
      if (stocks.loading) return stocks.text;
      var markup = "<div class='select2-result-repository clearfix'>"+
              stocks.stock_symbol+" - "+stocks.stock_name +"</div>";
      return markup;
    }
    function formatStockSelection (stocks) {
      return stocks.stock_symbol || stocks.id;
    }

    $(".portfolio-category-selector").select2({
      placeholder: "Select stock categories for listing below",
    });

    $(".portfolio-stock-selector").select2({
      placeholder: "Select stocks you would like to add to the portfolio",
      ajax: {
        url: "getStockNames",
        dataType: 'json',
        data: function (params) {
          console.log(params);
          return {
            term: params.term, // search term
            category: $('#portfolio-category-selector').val()
          };
        },
        processResults: function (data, params) {
          return {
            results: data
          };
        },
        cache: true
      },
      escapeMarkup: function (markup) { return markup; },
      minimumInputLength: 1,
      templateResult: formatStock,
      templateSelection: formatStockSelection
    });
  });
</script>
@endsection
