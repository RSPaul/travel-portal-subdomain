@extends('layouts.app-agent-header')
@section('content') 
<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/agent/wallet">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item active">Wallet
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
                
                <section id="multiple-column-form">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Add New Payment</h4>
                                </div>
                                <div class="card-body">
                                    <form class="form formValidation" id="addPayment" novalidate action="/api/agent/add-pay">
                                        <div class="row">
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label for="amount">Amount <span class="info-text">(USD)</span></label>
                                                    <input type="text" id="amount" class="form-control" placeholder="Add Payment Amount" name="amount" required value="" />
                                                    <div class="invalid-feedback">Please enter payment amount.</div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="pay_receipt">Upload Receipt <span class="info-text">(Use the receipt for wire transfer transaction)</span></label>
                                                    <input type="file" id="pay_receipt_select" class="form-control" name="pay_receipt_select" required value="" accept="application/pdf, image/*"/>
                                                    <input type="hidden" name="pay_receipt" id="pay_receipt">
                                                    <div class="invalid-feedback">Please select the payment receipt.</div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="comments">Comments</label>
                                                    <textarea id="comments" class="form-control" name="comments" placeholder="Add any comments about this transaction." /></textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <h4 class="card-title details_card_bnk">INIS Venture Bank Account</h4>
                                                <span class="bank_account_details">
                                                    <p class="detail_bnk_field">ACCOUNT NAME: INISVENTURE PVT LTD</p>
                                                    <p class="detail_bnk_field">ACCOUNT NUMBER: 004305006645</p>
                                                    <p class="detail_bnk_field">BANK NAME: ICICI BANK LIMITED</p>
                                                    <p class="detail_bnk_field">SWIFT CODE: ICICINBBCTS</p>
                                                    <p class="detail_bnk_field">CITY: PANCHKULA</p>
                                                    <p class="detail_bnk_field">STATE: HARYANA</p>
                                                    <p class="detail_bnk_field">COUNTRY: INDIA</p>
                                                </pre>
                                            </div>

                                            <div class="col-12">
                                                <button type="submit" class="btn btn-primary mr-1">Submit</button>
                                                <input type="hidden" id="reload" value="true">
                                                <a href="javascript:void(0);" onclick="return history.back()" class="btn btn-outline-secondary" >Cancel</a>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>                

                <section id="multiple-column-form">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">All Requests</h4>
                                </div>
                                <div class="card card-company-table">
                                    <div class="card-body p-0">
                                        <div class="table-responsive">
                                            <table class="datatables-basic table">
                                                <thead>
                                                    <tr>
                                                        <th>S No.</th>
                                                        <th>Date</th>
                                                        <th>Amount</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php $counter = 1 @endphp
                                                    @foreach($requests as $request)
                                                        <tr>
                                                            <td>{{ $counter++ }}</td>
                                                            <td>{{ date('l, F jS, Y', strtotime($request->created_at)) }}</td>
                                                            <td>{{$request->amount}}</td>
                                                            <td>
                                                                @if($request->status == 'pending')
                                                                    Pending
                                                                @elseif($request->status == 'approved')
                                                                    Approved
                                                                @else
                                                                    Rejected
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
    </div>
</div>
@endsection