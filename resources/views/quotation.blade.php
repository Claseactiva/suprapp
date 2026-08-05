@extends('layouts.quotation')

@section('content')


<div id="app" class="col-lg-12">
    <quotationuser-component owner-id="{{ $ownerId }}"></quotationuser-component>
</div>


@endsection