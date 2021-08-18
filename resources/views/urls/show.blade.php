@extends('layout')

@section('content')
    <div class="container-lg">
        <h1 class="mt-5 mb-3">Сайт: https://www.notion.so</h1>
        <div class="table-responsive">
            <table class="table table-bordered table-hover text-nowrap">
                <tr>
                    <td>ID</td>
                    <td>1</td>
                </tr>
                <tr>
                    <td>Имя</td>
                    <td>https://www.notion.so</td>
                </tr>
                <tr>
                    <td>Дата создания</td>
                    <td>2021-01-28 07:43:44</td>
                </tr>
                <tr>
                    <td>Дата обновления</td>
                    <td>2021-01-28 07:43:44</td>
                </tr>
            </table>
        </div>
        <h2 class="mt-5 mb-3">Проверки</h2>
        {{-- TODO: add check action route  --}}
        <form action="/" method="post">
            @csrf
            <input type="submit" class="btn btn-primary mb-3" value="Запустить проверку">
        </form>
        <div class="table-responsive">
            <table class="table table-bordered table-hover text-nowrap">
                {{--  TODO: foreach table generation  --}}
                <tr>
                    <th>ID</th>
                    <th>Код ответа</th>
                    <th>h1</th>
                    <th>keywords</th>
                    <th>description</th>
                    <th>Дата создания</th>
                </tr>
                <tr>
                    <td>600</td>
                    <td>200</td>
                    <td>All-in-one...</td>
                    <td></td>
                    <td>A new tool that blends your ev...</td>
                    <td>2021-08-13 13:58:34</td>
                </tr>
                <tr>
                    <td>595</td>
                    <td>200</td>
                    <td>All-in-one...</td>
                    <td></td>
                    <td>A new tool that blends your ev...</td>
                    <td>2021-08-11 17:49:45</td>
                </tr>
            </table>
        </div>
    </div>
@endsection
