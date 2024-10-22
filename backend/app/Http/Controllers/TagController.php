<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function index()
    {
        return Tag::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate(['name' => 'required|unique:tags']);
        return Tag::create($validated);
    }

    public function show($id)
    {
        return Tag::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $tag = Tag::findOrFail($id);
        $validated = $request->validate(['name' => 'required|unique:tags']);
        $tag->update($validated);
        return $tag;
    }

    public function destroy($id)
    {
        $tag = Tag::findOrFail($id);
        $tag->delete();
        return response(null, 204);
    }
}
