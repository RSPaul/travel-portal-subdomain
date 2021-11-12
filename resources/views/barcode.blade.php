@extends('layouts.app-header')

@section('content')
<style type="text/css">
	img{
		padding-left: 20px;
	}
</style>


<div class="container text-center" style="border: 1px solid #a1a1a1;padding: 15px;width: 70%;">
	<img src="data:image/png;base64,{{DNS1D::getBarcodePNG($no, 'C39')}}" alt="barcode" />
</div>


@endsection