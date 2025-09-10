@php
    
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Facades\File;

    $cid = null;
    if(!empty($post->cover_image)) {
        $absPath = Storage::disk('public')->path($post->cover_image);
        if(File::exists($absPath)) {
            $cid = $message->embed($absPath);
        }
    }       
@endphp

<x-mail::message>
<div style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto;">
<h1 style="color: #1a73e8; font-size: 28px; text-align: center; margin-bottom: 20px;">Nueva Publicación Creada 🥵🥵🥵🥵</h1>

@if ($cid)
<div style="text-align: center; margin: 20px 0;">
<img src="{{ $cid }}" alt="Portada del Post" style="max-width: 100%; height: auto; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
</div>
@endif

<div style="background-color: #f8f9fa; padding: 15px; border-radius: 8px; margin: 20px 0;">
<p style="margin: 5px 0; font-size: 16px;">
            <strong>Título:</strong> {{ $post->title }}
</p>
<p style="margin: 5px 0; font-size: 16px;">
<strong>Autor:</strong> {{ $author }}
</p>
<p style="margin: 5px 0; font-size: 16px;">
<strong>Fecha de Publicación:</strong> {{ $published_at ?? 'NO DEFINIDO' }}
</p>
</div>
<p style="font-size: 15px; color: #555; margin: 20px 0;">
{{ Str::limit($post->content, 200) }}
</p>

<x-mail::button :url="''" style="background-color: #1a73e8; color: #fff; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600;">
Ver Publicación Completa
</x-mail::button>
<p style="font-size: 14px; color: #777; margin-top: 20px; font-style: italic;">
> Nota: La mala pa sanAtiagO qUe nO VIno A clAsSe, JuLiAn lO ExTrAñO MUhcHO</p>
<hr style="border: none; border-top: 1px solid #eee; margin: 20px 0;">
<p style="font-size: 14px; color: #555; text-align: center;">
Gracias,<br>
<strong>{{ config('app.name') }}</strong>
</p>
</div>
</x-mail::message>