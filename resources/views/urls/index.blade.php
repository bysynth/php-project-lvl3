@extends('layout')

@section('content')
    <div class="container-lg">
        <h1 class="mt-5 mb-3">Сайты</h1>
        <div class="table-responsive">
            <table class="table table-bordered table-hover text-nowrap">
            {{--  TODO: foreach table generation  --}}
                <tr>
                    <th>ID</th>
                    <th>Имя</th>
                    <th>Последняя проверка</th>
                    <th>Код ответа</th>
                </tr>
                <tr>
                    <td>1</td>
                    <td><a href="{{ url('/urls/1') }}">https://www.notion.so</a></td>
                    <td>2021-08-13 13:58:34</td>
                    <td>200</td>
                </tr>
                <tr>
                    <td>2</td>
                    <td><a href="https://php-l3-page-analyzer.herokuapp.com/urls/2">https://phpstan.org</a></td>
                    <td>2021-08-18 12:45:53</td>
                    <td>200</td>
                </tr>
                <tr>
                    <td>3</td>
                    <td><a href="https://php-l3-page-analyzer.herokuapp.com/urls/3">https://www.deezer.com</a></td>
                    <td>2021-08-18 12:46:27</td>
                    <td>200</td>
                </tr>
            </table>
            {{--  TODO: laravel paging  --}}
            <nav>
                <ul class="pagination">
                    <li class="page-item disabled" aria-disabled="true" aria-label="&laquo; Previous">
                        <span class="page-link" aria-hidden="true">&lsaquo;</span>
                    </li>
                    <li class="page-item active" aria-current="page"><span class="page-link">1</span></li>
                    <li class="page-item"><a class="page-link"
                                             href="https://php-l3-page-analyzer.herokuapp.com/urls?page=2">2</a></li>
                    <li class="page-item"><a class="page-link"
                                             href="https://php-l3-page-analyzer.herokuapp.com/urls?page=3">3</a></li>
                    <li class="page-item disabled" aria-disabled="true"><span class="page-link">...</span></li>
                    <li class="page-item"><a class="page-link"
                                             href="https://php-l3-page-analyzer.herokuapp.com/urls?page=13">13</a></li>
                    <li class="page-item"><a class="page-link"
                                             href="https://php-l3-page-analyzer.herokuapp.com/urls?page=14">14</a></li>
                    <li class="page-item">
                        <a class="page-link" href="https://php-l3-page-analyzer.herokuapp.com/urls?page=2" rel="next"
                           aria-label="Next &raquo;">&rsaquo;</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
@endsection
