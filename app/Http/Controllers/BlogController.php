<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(){
        return response()->json(Blog::all());

    }

    public function store(Request $request) {
        $request->validate([
           'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $imageName = time().'.'.$request->image->extension();
    $request->image->move(public_path('uploads'), $imageName);
        $blog = Blog::create([
            'title' => $request->title,
            'content'=> $request ->content,
              'image' => $imageName,
        ]);
     
        return response()->json([
            'message'=> 'Blog ugurla yaradildi',
            'Blog' => $blog
        ],201);
    }

    public function update(Request $request, $id)
{
    $blog = Blog::findOrFail($id);

    $request->validate([
        'title' => 'nullable|string|max:255',
        'content' => 'nullable|string',
        'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    if ($request->has('title')) {
        $blog->title = $request->title;
    }

    if ($request->has('content')) {
        $blog->content = $request->content;
    }

    if ($request->hasFile('image')) {
        $imageName = time().'.'.$request->image->extension();
        $request->image->move(public_path('uploads'), $imageName);
        $blog->image = $imageName;
    }

    $blog->save();

    return response()->json([
        'message' => 'Blog redaktə olundu',
        'blog' => $blog
    ]);
}
public function destroy($id)
{
    $blog = Blog::findOrFail($id);

    // Əgər şəkil varsa, onu da sil (istəyə bağlı)
    $imagePath = public_path('uploads/'.$blog->image);
    if (file_exists($imagePath)) {
        unlink($imagePath);
    }

    $blog->delete();

    return response()->json([
        'message' => 'Blog silindi'
    ]);
}
}
