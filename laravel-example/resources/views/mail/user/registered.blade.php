@component('mail::message')
    # !HOLAAAA {{$user->name}}!
    Tu registro fue tan exitoso como tu polla tio!
    Ya puedes usar nuestra nitro nitro nitro nitrooooooooooo colosal API tio

    @component('mail::button', ['url' => config('app.url')])
        Ir a la basura
    @endcomponent

    Gracias BASURITA, con mucho cariño,
    {{config('app.name')}}
@endcomponent