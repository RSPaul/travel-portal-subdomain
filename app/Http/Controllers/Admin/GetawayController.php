<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Getaway;
use Illuminate\Http\Request;
use App\Models\Cities;
use Validator;

class GetawayController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $list = Getaway::all();

        return view('admin.getaways.index', compact('list'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $countries = Cities::select('Country','CountryCode')->where('Country','!=','')->groupBy('CountryCode')->get();
        return view('admin.getaways.create',['countries'=>$countries]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {

        $request->validate([
            'title' => 'required',
            'category' => 'required',
            'country' => 'required',
            'photo' => 'image|max: 1999|required'
        ]);
        
        $upload_path='/uploads/photos/';
        
        if ($request->hasFile('photo')) {

            $photo = $request->file('photo');
            $extension = $request->file('photo')->getClientOriginalExtension();
            $fileName = 'getaway_' . time() . '.' . $extension;

            $dir = public_path($upload_path);
            $photo->move($dir, $fileName);
        } else {
            $fileName = 'noimage.jpg';
        }

        $data = $request->all();
        $data['photo'] = $upload_path.$fileName;

        Getaway::create($data);

        return redirect()->route('getaways.index')
                        ->with('success', 'New Getaway created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Destination  $destination
     * @return \Illuminate\Http\Response
     */
    public function show(Getaway $getaway) {
        return view('admin.getaways.show', compact('getaway'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Destination  $destination
     * @return \Illuminate\Http\Response
     */
    public function edit(Getaway $getaway) {
         $countries = Cities::select('Country','CountryCode')->where('Country','!=','')->groupBy('CountryCode')->get();
        return view('admin.getaways.edit', compact('getaway','countries'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Destination  $destination
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Getaway $getaway) {


        $rule = [
            'title' => 'required',
            'category' => 'required',
            'country' => 'required',
            'photo' => 'image|max: 1999'
        ];

        $request->validate($rule);

        $data = $request->all();
        
        $upload_path='/uploads/photos/';

        if ($request->hasFile('photo')) {

            $photo = $request->file('photo');
            $extension = $request->file('photo')->getClientOriginalExtension();
            $fileName = 'getaway_' . time() . '.' . $extension;

            $dir = public_path($upload_path);
            $photo->move($dir, $fileName);

            $data['photo'] = $upload_path.$fileName;
        }

        $getaway->update($data);

        return redirect()->route('getaways.index')
                        ->with('success', 'Getaway updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Destination  $destination
     * @return \Illuminate\Http\Response
     */
    public function destroy(Getaway $getaway) {
        
        $getaway->delete();
        return redirect()->route('getaways.index')
                        ->with('success', 'Getaway deleted successfully');
        
    }

}
