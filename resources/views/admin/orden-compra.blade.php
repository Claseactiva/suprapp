@extends('layouts.portalapp')

@section('content')

    <script>
        window.authUserName = @json(Auth::user()->name);
    </script>

    <div class="row">
        <div id="app" class="col-lg-12">
            <purchase-order-component></purchase-order-component>
        </div>
    </div>

@endsection
