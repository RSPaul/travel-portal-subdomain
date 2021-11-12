<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Experience;
use App\Models\Cities;
use Illuminate\Http\Request;
use DB;

class ExperienceController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $list = Experience::all();

        return view('admin.experiences.index', compact('list'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        
        $countries = Cities::select('Country','CountryCode')->where('Country','!=','')->groupBy('CountryCode')->get();

        return view('admin.experiences.create',['countries'=>$countries]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {

        $request->validate([
            'city' => 'required',
            'country' => 'required',
            'price' => 'required',
            'photo' => 'image|max: 1999|required'
        ]);
        
        $upload_path='/uploads/photos/';
        
        if ($request->hasFile('photo')) {

            $photo = $request->file('photo');
            $extension = $request->file('photo')->getClientOriginalExtension();
            $fileName = 'experience_' . time() . '.' . $extension;

            $dir = public_path($upload_path);
            $photo->move($dir, $fileName);
        } else {
            $fileName = 'noimage.jpg';
        }

        $data = $request->all();
        $data['photo'] = $upload_path.$fileName;

        Experience::create($data);

        return redirect()->route('experiences.index')
                        ->with('success', 'New City created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Destination  $destination
     * @return \Illuminate\Http\Response
     */
    public function show(LocalExperience $experience) {
        return view('admin.experiences.show', compact('experience'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Destination  $destination
     * @return \Illuminate\Http\Response
     */
    public function edit(Experience $experience) {
        $countries = Cities::select('Country','CountryCode')->where('Country','!=','')->groupBy('CountryCode')->get();
        return view('admin.experiences.edit', compact('experience','countries'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Destination  $destination
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Experience $experience) {


        $rule = [
            'city' => 'required',
            'country' => 'required',
            'price' => 'required',
            'photo' => 'image|max: 1999'
        ];

        $request->validate($rule);

        $data = $request->all();
        
        $upload_path='/uploads/photos/';

        if ($request->hasFile('photo')) {

            $photo = $request->file('photo');
            $extension = $request->file('photo')->getClientOriginalExtension();
            $fileName = 'experience_' . time() . '.' . $extension;

            $dir = public_path($upload_path);
            $photo->move($dir, $fileName);

            $data['photo'] = $upload_path.$fileName;
        }

        $experience->update($data);

        return redirect()->route('experiences.index')
                        ->with('success', 'City updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Destination  $destination
     * @return \Illuminate\Http\Response
     */
    public function destroy(Experience $experience) {
        
        $experience->delete();
        return redirect()->route('experiences.index')
                        ->with('success', 'City deleted successfully');
        
    }

}
