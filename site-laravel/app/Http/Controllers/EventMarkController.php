<?php

namespace App\Http\Controllers;

use App\EventMark;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventMarkController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    public function store(Request $request)
    {
        $photo_file_path = Storage::disk("public")->putFile("event_photos", $request->file("photo"));
        (new EventMark([
            "name" => $request->input("name"),
            "due" => $request->input("datetime"),
            "site" => $request->input("url"),
            "latitude" => $request->input("latitude"),
            "longitude" => $request->input("longitude"),
            "address" => $request->input("address"),
            "description" => $request->input("description"),
            "photo_file" => $photo_file_path,
        ]))->save();
        return redirect()->route("dispatch");
    }

    public function destroy($id)
    {
        EventMark::destroy($id);
    }
}
