<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use Illuminate\Support\Facades\Storage;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\Mail;
use App\Mail\PostCreatedMail;
// TODO: Eliminar
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     */
    public function index():JsonResponse
    {
        // La mala practica porque tenemos un Model.
        // return response()->json(DB::table("posts")->get());
        $posts = Post::with('user', 'categories')->get();
        return $this->success(PostResource::collection($posts));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request):JsonResponse
    {
        // $newPost = Post::create($request->only(['title','content','status'])); // Simplemente inserta sin validaciones, ni comparacion, ni actualizacion
        $data = $request->validated();

        // en el Body no voy a recibir ID del usuario / Simpre se toma el token
        $data['user_id'] = $request->user()->id;

        if($request->hasFile('cover_image')){
            $data['cover_image'] = $request->file('cover_image')->store('posts', 'public');
        }
        
        $newPost = Post::create($data); // las columnas para reistrar o actualizar

        if(!empty($data['category_ids'])){
            $newPost->categories()->sync($data['category_ids']);
        }

        $newPost->load(['user','categories']);

        Mail::to($newPost->user->email)->queue(new PostCreatedMail($newPost));

        return $this->success(new PostResource($newPost), 'Post creado correctamente', 201);

        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id):JsonResponse
    {
        $result = Post::find($id);
        if ($result){
            $result->load(['user','categories']);
            return $this->success(new PostResource($result),"Todo ok, como dijo el Pibeee");
        } else{
            return $this->error("Todo mal, como NO dijo el Pibeee", 404, ['id'=> 'no se encontro el recurso con el id']);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post):JsonResponse
    {
        
        $data = $request->validated();

        if($request->hasFile('cover_image')){
            // Borrado (Opcional)
            if($post->cover_image){
                storage::disk('public')->delete($post->cover_image);
            }
             $data['cover_image'] = $request->file('cover_image')->store('posts', 'public');
        }

        $post->update($data);
        if(array_key_exists('category_ids', $data)){
            $post->categories()->sync($data['category_ids'] ?? []);
        }
        $post->load(['user', 'categories']);
        Mail::to($post->user->email)->queue(new PostCreatedMail($post));
        return $this->success(new PostResource($post));


    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post):JsonResponse
    {
        $post->delete(); // Soft Delete
        /// return response()->noContent
        return $this->success(null, 'Post eliminado', 204);
    }

    public function restore(int $id): JsonResponse{
        $post = Post::onlyTrashed()->findOrFail($id);
        if (!$post) {
        //throw new ModelNotFoundException('Post no encontrado', 404);
        throw new RecordsNotFoundException('Post no encontrado', 404);
    }

        $post->restore();
        $post->load(['user','categories']);
        return $this->success($post, 'Post restaurado correctamente');
    }
}
