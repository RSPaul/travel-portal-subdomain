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
                                    <li class="breadcrumb-item"><a href="/agent/kyc">Dashboard</a>
                                    </li>
                                    <li class="breadcrumb-item active">KYC
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
                                    <h4 class="card-title">Bank Details</h4>
                                </div>
                                <div class="card-body">
                                    <form class="form formValidation" id="bankDetails" novalidate action="/api/agent/kyc">
                                        <div class="row">
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label for="first_name">First Name</label>
                                                    <input type="text" id="first_name" class="form-control" placeholder="First Name" name="first_name" required value="{{ $bank_details->first_name }}" />
                                                    <div class="invalid-feedback">Please enter your first name.</div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label for="last_name">Last Name</label>
                                                    <input type="text" id="last_name" class="form-control" placeholder="Last Name" name="last_name" required value="{{ $bank_details->last_name }}"/>
                                                    <div class="invalid-feedback">Please enter your last name.</div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label for="bank_name">Bank Name</label>
                                                    <input type="text" id="bank_name" class="form-control" placeholder="Bank Name" name="bank_name" required value="{{ $bank_details->bank_name }}" />
                                                    <div class="invalid-feedback">Please enter bank name.</div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label for="account_number">Account Number</label>
                                                    <input type="text" id="account_number" class="form-control" name="account_number" placeholder="Bank Account Number" required value="{{ $bank_details->account_number }}" />
                                                    <div class="invalid-feedback">Please enter account number.</div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label for="swift_code">Swift Code</label>
                                                    <input type="text" id="swift_code" class="form-control" name="swift_code" placeholder="Swift Code" required value="{{ $bank_details->swift_code }}"/>
                                                    <div class="invalid-feedback">Please enter bank swift code.</div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label for="zip_code">Zip Code</label>
                                                    <input type="text" id="zip_code" class="form-control" name="zip_code" placeholder="Zip Code" required value="{{ $bank_details->zip_code }}" />
                                                    <div class="invalid-feedback">Please enter your zip code.</div>
                                                </div>
                                            </div>

                                            <div class="col-md-6 col-6">
                                                <div class="form-group">
                                                    <label for="address">Address</label>
                                                    <textarea id="address" class="form-control" name="address" placeholder="Address" required />{{ $bank_details->address }}</textarea>
                                                    <div class="invalid-feedback">Please enter your address.</div>
                                                </div>
                                            </div>

                                            <div class="col-md-6 col-6">
                                                <div class="form-group">
                                                    <label for="address">Mobile Number</label>
                                                    <input type="text" id="mobile_number" class="form-control" name="mobile_number" placeholder="Mobile Number" required value="{{ $bank_details->mobile_number }}" />
                                                    <div class="invalid-feedback">Please enter your mobile number.</div>
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <input type="hidden" name="user_id" value="{{ $bank_details->user_id }}">
                                                <button type="submit" class="btn btn-primary mr-1">Submit</button>
                                                <a href="/agent/dashboard" class="btn btn-outline-secondary" >Cancel</a>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>                

            </div>
        </div>
    </div>
@endsection