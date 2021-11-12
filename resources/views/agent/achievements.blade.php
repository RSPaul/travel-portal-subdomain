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
                                <li class="breadcrumb-item"><a href="/agent/achievements">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item active">Achievements
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <section id="card-demo-example">
                <div class="row match-height">
                    <div class="col-md-6 col-lg-4">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title medals">Brownz Medal</h4>                                
                            </div>
                            <img class="img-fluid" src="{{asset('/images/agent/illustration/bros-madal.png')}}" alt="Card image cap" width="120" height="120" style="margin-left: 200px;" />
                            <div class="card-body">
                                <p class="card-text">Bear claw sesame snaps gummies chocolate.</p>                                
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title medals">Silver Medal</h4>                                
                            </div>
                            <img class="img-fluid" src="{{asset('/images/agent/illustration/silver-madal.png')}}" alt="Card image cap" width="120" height="120" style="margin-left: 200px;" />
                            <div class="card-body">
                                <p class="card-text">Bear claw sesame snaps gummies chocolate.</p>                                
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title medals">Gold Medal</h4>                                
                            </div>
                            <img class="img-fluid" src="{{asset('/images/agent/illustration/gold-madal.png')}}" alt="Card image cap" width="120" height="120" style="margin-left: 200px;" />
                            <div class="card-body">
                                <p class="card-text">Bear claw sesame snaps gummies chocolate.</p>                                
                            </div>
                        </div>
                    </div>
                </div>
            </section>

        </div>
    </div>
</div>
@endsection