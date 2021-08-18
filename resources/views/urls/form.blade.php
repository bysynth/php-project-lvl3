@extends('layout')

@section('content')
    <div class="container-lg">
        <div class="row">
            <div class="col-12 col-md-10 col-lg-8 mx-auto my-5">
                <h1 class="display-3">Анализатор страниц</h1>
                <p class="lead">Бесплатно проверяйте сайты на SEO пригодность</p>
                <form action="{{ route('url.store') }}" method="post" class="d-flex justify-content-center">
                    @csrf

                    {{-- TODO: Errors, value --}}
                    <input type="text" name="url[name]" value="" class="form-control form-control-lg"
                           placeholder="https://www.example.com" required>
                    <button type="submit" class="btn btn-lg btn-primary ml-3 px-5 text-uppercase">Проверить</button>
                </form>
            </div>
        </div>
    </div>
@endsection
