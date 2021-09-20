@extends('admin.layouts.app')

@section('title', 'Criar Novo Post')

@section('content')
    <h1 class="text-center text-3x1 uppercase font-black my-4">Cadastrar Novo Post</h1>

    <div class="w-11/12 pg-12 bg-white sm:w-8/12 md:w-1/2 lg:w-5/12 mx-auto">
        <form action="{{ route('posts.store') }}" method="post" enctype="multipart/form-data">
            @include('admin.posts._partials.form')
        </form>
    </div>
@endsection
