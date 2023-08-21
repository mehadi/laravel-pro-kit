<?php

namespace App\Http\Controllers;

use App\Http\Requests\Post\UpdateRequest;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::latest()->paginate(12);

        return view('posts.index', ['posts' => $posts]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return response()->view('posts.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|min:3|max:250',
            'content' => 'required|string|min:3|max:6000',
            'featured_image' => 'required|image|mimes:jpg,jpeg,png',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors(),
            ], 422);
        }

        $validated = $validator->validated();

        if ($request->hasFile('featured_image')) {
            // Put image in the public storage
            $filePath = Storage::disk('public')->put('images/posts/featured-images', request()->file('featured_image'));
            $validated['featured_image'] = $filePath;
        }

        $post = Post::create($validated);

        if ($post) {
            return response()->json([
                'success' => true,
                'message' => 'Post created successfully!',
                'data' => $post,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to create post.',
        ], 500);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $post = Post::findOrFail($id);
        return response()->json($post);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return response()->view('posts.form', [
            'post' => Post::findOrFail($id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, string $id)
    {
        $post = Post::findOrFail($id);

        if ($request->has('title')) {
            $post->title = $request->input('title');
        }

        if ($request->has('content')) {
            $post->content = $request->input('content');
        }

        if ($request->hasFile('featured_image')) {
            // Delete old image
            Storage::disk('public')->delete($post->featured_image);

            $filePath = Storage::disk('public')->put('images/posts/featured-images', $request->file('featured_image'), 'public');
            $post->featured_image = $filePath;
        }

        $update = $post->save();

        if ($update) {
            return response()->json(['success' => true, 'message' => 'Post updated successfully', 'data' => $post]);
        }

        return response()->json(['success' => true, 'message' => 'Post update failed!','data' => $post]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $post = Post::findOrFail($id);

        Storage::disk('public')->delete($post->featured_image);

        $delete = $post->delete($id);

        if ($delete) {
            return response()->json(['success' => true, 'message' => 'Post deleted successfully']);
        } else {
            return response()->json(['success' => false, 'message' => 'Post deleted unsuccessfull']);
        }

    }
}
