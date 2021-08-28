<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class UrlController extends Controller
{
    /**
     * @return View
     */
    public function create(): View
    {
        return view('urls.create');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $input = $request->input('url.name');

        $validator = Validator::make($request->input('url'), [
            'name' => ['required', 'url', 'max:255'],
        ]);

        if ($validator->fails()) {
            flash("Некорректный URL: $input")->error();
            return back()->withInput()->withErrors($validator)->with('name');
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

    /**
     * @return View
     */
    public function index(): View
    {
        $urls = DB::table('urls')
            ->distinct('urls.id')
            ->select('urls.id', 'urls.name', 'url_checks.status_code', 'url_checks.created_at AS last_checked_at')
            ->leftJoin('url_checks', 'urls.id', '=', 'url_checks.url_id')
            ->orderBy('urls.id')
            ->orderByDesc('url_checks.created_at')
            ->paginate(5);

        return view('urls.index', ['urls' => $urls]);
    }

    /**
     * @param int $id
     * @return View
     */
    public function show(int $id): View
    {
        $url = DB::table('urls')->find($id);
        if (is_null($url)) {
            abort(404);
        }

        $checks = DB::table('url_checks')
            ->where('url_id', '=', $url->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('urls.show', ['url' => $url, 'checks' => $checks]);
    }

    /**
     * @param string $url
     * @return string
     */
    private function normalizeUrl(string $url): string
    {
        $urlData = parse_url($url);
        $scheme = $urlData['scheme'];
        $host = $urlData['host'];
        $path = $urlData['path'] ?? '';

        return "$scheme://{$host}{$path}";
    }

    /**
     * @param string $name
     * @return bool
     */
    private function isUrlExists(string $name): bool
    {
        return DB::table('urls')->where('name', '=', $name)->exists();
    }
}
