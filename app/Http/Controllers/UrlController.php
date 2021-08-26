<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class UrlController extends Controller
{
    public function create(): View
    {
        return view('urls.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $input = $request->input('url.name');
        $validator = Validator::make($request->input(), [
            'url.name' => ['required', 'url', 'max:255'],
        ]);

        if ($validator->fails()) {
            flash("Некорректный URL: $input")->error();
            return back()->withInput()->withErrors($validator);
        }

        $url = $this->normalizeUrl($input);

        if ($this->isUrlExists($url)) {
            flash('Страница уже существует')->info();
            $id = DB::table('urls')->where('name', '=', $url)->value('id');

            return redirect()->route('urls.show', ['id' => $id]);
        }

        $now = now();
        $urlData = [
            'name' => $url,
            'created_at' => $now,
            'updated_at' => $now
        ];

        $id = DB::table('urls')->insertGetId($urlData);
        flash('Страница успешно добавлена')->success();

        return redirect()->route('urls.show', ['id' => $id]);
    }

    public function index(): View
    {
        $urls = DB::table('urls')->paginate(3); // TODO: скорректировать количество элементов
        return view('urls.index', ['urls' => $urls]);
    }

    public function show(int $id): View
    {
        $url = DB::table('urls')->find($id);
        if (is_null($url)) {
            abort(404);
        }

        return view('urls.show', ['url' => $url]);
    }

    private function normalizeUrl(string $url): string
    {
        $urlData = parse_url($url);
        $scheme = $urlData['scheme'];
        $host = $urlData['host'];

        return "$scheme://$host";
    }

    private function isUrlExists(string $name): bool
    {
        return DB::table('urls')->where('name', '=', $name)->exists();
    }
}
