<?php

namespace App\Http\Controllers;

use App\Actions\File\File;
use App\Models\Crud;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class CrudController extends Controller
{
    public function index()
    {
        return view('crud.index');
    }

    public function getData()
    {
        return response()->json([
            "data" => Crud::latest()->get()
        ]);
    }

    public function show(Crud $curd)
    {
        return $curd;
    }

    public function delete(Crud $crud)
    {
        $file = $crud->image;
        File::deleteFile($file);
        return $crud->delete();
    }
}
