<?php

namespace App\Http\Controllers;

use App\Http\Resources\Mark as MarkResource;
use App\Mark;
use App\RecyclableMarkClasses;
use Illuminate\Http\Request;
use ReflectionClass;

class MarkController extends Controller
{
    function __construct()
    {
        $this->middleware("auth")->only(["destroy", "store"]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $markType = $request->input("mark-type");
        if ($markType) {
            $marks = Mark::select('*')->where('type', "App\\" . $markType);
            if ($category = $request->input('category'))
                $marks = $marks->where('category', $category);
            return MarkResource::collection($marks->get());
        } else {
            return MarkResource::collection(Mark::all());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // FIXME
        try {
            $refl = new ReflectionClass($request->type);
        } catch (\ReflectionException $e) {
            return "Unknown mark class: {$request->type}";
        }
        $filename = $request->file('photo')?->storePublicly('photos');
        $mark = $refl->newInstance(array_merge($request->all(), ['photo_file' => $filename]));
        $mark->save();
        if (!$request->expectsJson()) {
            return redirect()->route('dispatch');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Mark  $mark
     * @return \Illuminate\Http\Response
     */
    public function show(Mark $mark)
    {
        return new MarkResource($mark);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Mark  $mark
     * @return \Illuminate\Http\Response
     */
    public function edit(Mark $mark)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Mark  $mark
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Mark $mark)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Mark  $mark
     * @return \Illuminate\Http\Response
     */
    public function destroy(Mark $mark)
    {
        $mark->delete();
    }
}
