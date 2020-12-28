<?php

namespace App\Http\Controllers;

use App\Models\Qualification_file;
use App\Models\Competition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Qualification_fileController extends Controller
{
    public function index()
    {
        if (Auth::user()->student()) {
            $files = Qualification_file::all(); // student sees all qualification_files

        } else {

            $files = Auth::user()->qualification_files(); // education sees only his/hers qualification files
        }

        // dd($files);

        return view('qualification_file.index', compact('files'));
    }

    public function create()
    {
        //
    }

    public function store()
    {
        Qualification_file::create($this->validateQualification_file());

        return back();
    }

    public function show(Qualification_file $qualification_file)
    {
        $competitions = Auth::user()->competitions();
        // $competitions = Competition::all();
        return view('qualification_file.show', compact('qualification_file', 'competitions'));
    }

    public function edit(Qualification_file $qualification_file)
    {
        return view('qualification_file.edit', compact('qualification_file'));
    }

    public function update()
    {
        //
    }

    public function destroy($id)
    {
        $sql = "DELETE FROM qualification_files WHERE id=$id";

        DB::update($sql);

        return back();
    }

    protected function validateQualification_file()
    {
        $attributes = request()->validate([
            'name' => 'required',
            'file' => 'required',
            'user_id' => 'required',
            'total_number_of_competitions' => 'required',
        ]);

        $attributes['file'] = request('file')->store('qualification_files');

        return $attributes;
    }
}
