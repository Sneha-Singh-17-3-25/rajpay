@extends('layouts.app')
@section('title', "Dual Statement")
@section('pagetitle',  "Dual Statement")

@php
    $table = "yes";
@endphp

@section('content')
<div class="content">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">Dual Statement</h4>
                </div>
                <table class="table table-bordered table-striped table-hover" id="datatable">
                    <thead>
                        <tr>
                            <th>Txnid</th>
                            <th>Count</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dual as $dual)
                            <tr>
                                <th>{{$dual->txnid}}</th>
                                <th>{{$dual->count}}</th>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection