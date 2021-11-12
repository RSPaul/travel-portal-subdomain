<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use Illuminate\Http\Request;
use Validator;

class DestinationController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $destinations = Destination::all();

        return view('admin.destinations.index', compact('destinations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view('admin.destinations.create');
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
            'photo_url' => 'image|max: 1999|required'
        ]);
        
        $upload_path='/uploads/photos/';
        
        if ($request->hasFile('photo_url')) {

            $photo = $request->file('photo_url');
            $extension = $request->file('photo_url')->getClientOriginalExtension();
            $fileName = 'destination_' . time() . '.' . $extension;

            $dir = public_path($upload_path);
            $photo->move($dir, $fileName);
        } else {
            $fileName = 'noimage.jpg';
        }

        $data = $request->all();
        $data['photo_url'] = $upload_path.$fileName;

        Destination::create($data);

        return redirect()->route('destinations.index')
                        ->with('success', 'Destination created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Destination  $destination
     * @return \Illuminate\Http\Response
     */
    public function show(Destination $destination) {
        return view('admin.destinations.show', compact('destination'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Destination  $destination
     * @return \Illuminate\Http\Response
     */
    public function edit(Destination $destination) {
        return view('admin.destinations.edit', compact('destination'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Destination  $destination
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Destination $destination) {


        $rule = [
            'title' => 'required',
            'sub_title' => 'required',
            'photo_url' => 'image|max: 1999'
        ];

        $request->validate($rule);

        $data = $request->all();
        
        $upload_path='/uploads/photos/';

        if ($request->hasFile('photo_url')) {

            $photo = $request->file('photo_url');
            $extension = $request->file('photo_url')->getClientOriginalExtension();
            $fileName = 'destination_' . time() . '.' . $extension;

            $dir = public_path($upload_path);
            $photo->move($dir, $fileName);

            $data['photo_url'] = $upload_path.$fileName;
        }

        $destination->update($data);

        return redirect()->route('destinations.index')
                        ->with('success', 'Destination updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Destination  $destination
     * @return \Illuminate\Http\Response
     */
    public function destroy(Destination $destination) {
        
        $destination->delete();
        return redirect()->route('destinations.index')
                        ->with('success', 'Destination deleted successfully');
        
    }

}
