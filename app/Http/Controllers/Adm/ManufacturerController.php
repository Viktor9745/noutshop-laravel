<?php

namespace App\Http\Controllers\Adm;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \App\Models\Manufacturer;
use Illuminate\Support\Facades\Auth;

class ManufacturerController extends Controller
{
    public function index(Request $request){
        $manufacturers=null;
        if($request->search){
            $manufacturers = Manufacturer::where('name', 'LIKE', '%'.$request->search.'%')->get();
        }else{
            $manufacturers = Manufacturer::get();
        }

        return view('adm.manufacturers',['manufacturers'=>$manufacturers]);
    }

    public function create()
    {
        return view('adm.createmanufacturer');
    }

    public function store(Request $request){
        $validated = $request->validate([
            'name' => 'required|max:255',
            'code' => 'required|max:5',
        ]);
        Manufacturer::create($validated);
        return back()->with('message', __('messages.man_create'));
    }

    public function edit(Manufacturer $manufacturer){
        return view('adm.editmanufacturer',['manufacturer'=>$manufacturer]);
    }

    public function update(Request $request,Manufacturer $manufacturer){
        $manufacturer->update([
            'name'=>$request->input('name'),
            'code'=>$request->input('code'),
        ]);

        return redirect()->route('adm.manufacturers.index')->with('message', __('messages.man_edit'));
    }

    public function destroy(Manufacturer $manufacturer)
    {
        $manufacturer->delete();
        return redirect()->route('adm.manufacturers.index')->with('message', __('messages.man_delete'));
    }
}
