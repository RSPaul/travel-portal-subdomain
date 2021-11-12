@extends('layouts.app-header')
@section('content')  
<section class="state_flight_info" style="background: #f4f4f4 !important;">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1>Transaction History</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="table-responsive">
                    <table class="table table-bordered table-secondary table-striped">
                        <thead>
                            <tr>
                                <th>Transaction Type</th>
                                <th>Amount</th>
                                <th>Detail</th>
                                <th>Transaction Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($trans as $txn)
                            <tr>
                                <td>{{ ucfirst($txn->type) }}</td>
                                <td>{{ $txn->amount }}</td>
                                <td>
                                    @foreach ($txn->meta as $key => $val)
                                    {{ ucfirst($key) }} : {{$val}} <br/>
                                    @endforeach
                                </td>
                                <td>{{ date("d-m-Y h:i a",strtotime($txn->created_at)) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection