<?php

namespace App\Http\Controllers;

use App\BannedUser;
use App\Http\Resources\TrashMark as TrashMarkResource;
use App\Lib\GeoPoint;
use App\TrashMark;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

define("SUBMISSION_TYPE_UNAUTHORIZED_DUMP", "UNAUTHORIZED_DUMP");
define("SUBMISSION_TYPE_FULL_DUMPSTER", "FULL_DUMPSTER");

class TrashMarkController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth")->only("destroy");
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return TrashMarkResource::collection(TrashMark::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($this->isSenderBanned($request)) {
            return response('', 403);
        }

        $submissionType = $request->input("type");
        switch ($submissionType) {
            case SUBMISSION_TYPE_UNAUTHORIZED_DUMP:
                $this->storeUnauthorizedDumpReport($request);
                break;
            case SUBMISSION_TYPE_FULL_DUMPSTER:
                $this->storeFullDumpsterReport($request);
                break;
            default:
                return response("submission type not specified", 400);
        }
        if (!$request->expectsJson()) {
            return redirect("/");
        }
    }

    private function isSenderBanned(Request $request)
    {
        return BannedUser::find(hexdec($request->input("id")));
    }

    private function savePhotoToStorage($request)
    {
        $photoBase64 = $request->input("photo");
        $tempFilename = tempnam(sys_get_temp_dir(), "");
        file_put_contents($tempFilename, base64_decode($photoBase64));
        $savedFilename = Storage::disk("public")->putFile("trash_mark_photos", new File($tempFilename));
        return $savedFilename;
    }

    private function storeUnauthorizedDumpReport(Request $request)
    {
        $photoPath = $this->savePhotoToStorage($request);
        $mark = new TrashMark([
            "latitude" => $request->input("coords.lat", $request->input("latitude")),
            "longitude" => $request->input("coords.long", $request->input("longitude")),
            "address" => $request->input("address"),
            "description" => $request->input("comment"),
            "photo_file" => $photoPath,
            "sender_id" => hexdec($request->input("id"))
        ]);
        $mark->save();
    }

    private function storeFullDumpsterReport(Request $request)
    {
        $reportPoint = new GeoPoint($request->input("coords.lat"), $request->input("coords.long"));
        $closestDumpster = $reportPoint->findClosestDumpster();
        $closestDumpster->full = true;
        $closestDumpster->update();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\TrashMark  $trashMark
     * @return \Illuminate\Http\Response
     */
    public function show(TrashMark $trashMark)
    {
        return new TrashMarkResource($trashMark);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TrashMark  $trashMark
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TrashMark $trashMark)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TrashMark  $trashMark
     * @return \Illuminate\Http\Response
     */
    public function destroy(TrashMark $trashMark)
    {
        $trashMark->delete();
    }
}
