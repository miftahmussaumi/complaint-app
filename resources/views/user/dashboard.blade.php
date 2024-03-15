@extends('template')

@section('content')
    <div class="row" style="display: inline-block;">
        <div class="tile_count">
            <div class="col-md-3 col-sm-3  tile_stats_count">
                <span class="count_top"><i class="fa fa-user"></i> Total Complaint</span>
                <div class="count">4500</div>
            </div>
            <div class="col-md-3 col-sm-3  tile_stats_count">
                <span class="count_top"><i class="fa fa-clock-o"></i> On Progress</span>
                <div class="count">14.50</div>
            </div>
            <div class="col-md-3 col-sm-3  tile_stats_count">
                <span class="count_top"><i class="fa fa-user"></i> On Check</span>
                <div class="count green">4,500</div>
            </div>
            <div class="col-md-3 col-sm-3  tile_stats_count">
                <span class="count_top"><i class="fa fa-user"></i> Finish</span>
                <div class="count">4,567</div>
            </div>
        </div>
    </div>
@endsection