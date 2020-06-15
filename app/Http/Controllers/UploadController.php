<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadController extends Controller
{
    //
    public function index(){
        return view("upload.index");
    }

    public function upload(Request $request){
        /**
         * @var UploadedFile $file
         */
        $file=$request->file("file");
        $file->move(storage_path("uploads"), $file->getClientOriginalName());
        echo test();
        dd($file);

    }
}
