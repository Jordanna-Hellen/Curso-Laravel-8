@extends('admin/layouts/app')

@section('title', 'Listagem dos Posts')

@section('content')

    <a href="{{route('posts/create')}}">Criar novo post</a>

    @if (session('message'))
        <div>
            {{session('message')}}
        </div>
    @endif

    <hr>
    <h1>Posts</h1>

    <form action="{{ route('posts/search') }}" method="post">

        @csrf
        <input type="search" name="search" placeholder="Pesquisar">
        <button type="submit">Enviar</button>
    </form>

    @foreach ($posts as $post)

    <p>
        <img src="{{ url("storage/{$post->image}") }}" alt="{{ $post->title }}">
        {{ $post->title }}
        [
            <a href="{{route("posts/show", $post -> id)}}">Ver</a> |
            <a href="{{route("posts/edit", $post -> id)}}">Editar</a>
        ]
    </p>

    @endforeach

    <hr>

    @if (isset($filters))
        {{ $posts->appends($filters)->links() }}
    @else
        {{ $posts->links() }}
    @endif


@endsection
