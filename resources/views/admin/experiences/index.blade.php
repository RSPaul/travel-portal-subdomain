@extends('layouts.app-admin')
@section('content')
<div id="page-content-wrapper">
    <section class="maindiv">
        <div class="container admin-table">

            @if ($message = Session::get('success'))
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-success">
                        <p>{{ $message }}</p>
                    </div>
                </div>
            </div>
            @endif

            <div class="row headingtop">
                <div class="col-lg-6">
                    <h2>Local Experiences</h2>
                </div>
                <div class="col-lg-6">
                    <div class="text-right">
                        <a class="btn btn-success" href="{{ route('experiences.create') }}"> <span class="fa fa-plus-circle"></span> Add New</a>
                    </div>
                </div>
            </div>
            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered dataTable addaddress" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Photo</th>
                                    <th>City</th>
                                    <th>Country</th>
                                    <th>Price</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>                     
                            <tbody>
                                @foreach($list as $row)
                                <tr>
                                    <td><img style="width:100px;" src="{{$row->photo}}" class="img-responsive" /></td>
                                    <td>{{$row->city}}</td>
                                    <td>{{$row->country}}</td>
                                    <td>{{$row->price}}</td>
                                    <td> 
                                        <form action="{{ route('experiences.destroy',$row->id) }}" method="POST">
                                            
                                            <a class="btn btn-primary btn-sm" href="{{ route('experiences.edit',$row->id) }}">Edit</a>
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" onclick="return confirm('Do you really want to delete this record?');" class="btn btn-danger btn-sm">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection