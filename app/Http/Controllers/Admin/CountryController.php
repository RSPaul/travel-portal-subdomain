<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;
use Validator;

class CountryController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $destinations = Country::all();

        return view('admin.countries.index', compact('destinations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view('admin.countries.create');
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
            'sub_title' => 'required',
            'photo' => 'image|max: 1999|required'
        ]);
        
        $upload_path='/uploads/photos/';
        
        if ($request->hasFile('photo')) {

            $photo = $request->file('photo');
            $extension = $request->file('photo')->getClientOriginalExtension();
            $fileName = 'country_' . time() . '.' . $extension;

            $dir = public_path($upload_path);
            $photo->move($dir, $fileName);
        } else {
            $fileName = 'noimage.jpg';
        }

        $data = $request->all();
        $data['photo'] = $upload_path.$fileName;

        Country::create($data);

        return redirect()->route('countries.index')
                        ->with('success', 'Country created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Destination  $destination
     * @return \Illuminate\Http\Response
     */
    public function show(Country $country) {
        return view('admin.countries.show', compact('country'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Destination  $destination
     * @return \Illuminate\Http\Response
     */
    public function edit(Country $country) {
        return view('admin.countries.edit', compact('country'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Destination  $destination
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Country $country) {


        $rule = [
            'title' => 'required',
            'sub_title' => 'required',
            'photo' => 'image|max: 1999'
        ];

        $request->validate($rule);

        $data = $request->all();
        
        $upload_path='/uploads/photos/';

        if ($request->hasFile('photo')) {

            $photo = $request->file('photo');
            $extension = $request->file('photo')->getClientOriginalExtension();
            $fileName = 'country_' . time() . '.' . $extension;

            $dir = public_path($upload_path);
            $photo->move($dir, $fileName);

            $data['photo'] = $upload_path.$fileName;
        }

        $country->update($data);

        return redirect()->route('countries.index')
                        ->with('success', 'Country updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Destination  $destination
     * @return \Illuminate\Http\Response
     */
    public function destroy(Country $country) {
        
        $country->delete();
        return redirect()->route('countries.index')
                        ->with('success', 'Country deleted successfully');
        
    }

}
