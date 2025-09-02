<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use Illuminate\Support\Facades\Storage;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Models\Post;
// TODO: Eliminar
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // La mala practica porque tenemos un Model.
        // return response()->json(DB::table("posts")->get());
        return $this->ok("Todo ok, como dijo el Pibeee", Post::get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        // $newPost = Post::create($request->only(['title','content','status'])); // Simplemente inserta sin validaciones, ni comparacion, ni actualizacion
        $data = $request->validated();

        if($request->hasFile('cover_image')){
            $data['cover_image'] = $request->file('cover_image')->store('posts', 'public');
        }
        
        $newPost = Post::create($data); // las columnas para reistrar o actualizar

        if(!empty($data['category_ids'])){
            $newPost->categories()->sync($data['category_ids']);
        }

        return $this->ok("Todo melo basuras", [$newPost]);

        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $result = Post::find($id);
        if ($result){
            return $this->ok("Todo ok, como dijo el Pibeee", $result);
        } else{
            return $this->success("Todo mal, como NO dijo el Pibeee", [], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        $data = $request->validated();

        if($request->hasFile('vocer_image')){
            // Borrado (Opcional)
            if($post->cover_image){
                storage::disk('public')->delete($Post-cover_image);
            }
             $data['cover_image'] = $request->file('cover_image')->store('posts', 'public');
        }

        $post->update($data);
        if(\array_key_exists('category_ids', $data)){
            $post->categories()->sync($data['category_ids'] ?? []);
        }
        return $this->ok('Todo melo perro', [$post]);


    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $post->delete(); // Soft Delete
        /// return response()->noContent
        return $this->ok('Todo eliminadooooooooo', [$post]);
    }
}
