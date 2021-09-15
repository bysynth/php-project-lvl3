<?php

namespace App\Http\Controllers;

use DiDom\Document;
use DiDom\Exceptions\InvalidSelectorException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class UrlController extends Controller
{
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
            return back()->withInput()->withErrors($validator);
        }

        $urlData = parse_url($input);
        $scheme = $urlData['scheme'];
        $host = $urlData['host'];
        $path = $urlData['path'] ?? '';
        $url = "$scheme://{$host}{$path}";

        if (DB::table('urls')->where('name', '=', $url)->exists()) {
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
        $urls = DB::table('urls')->orderBy('id')->paginate(5);
        $lastChecks = DB::table('url_checks')
            ->distinct('url_id')
            ->select('url_id', 'status_code')
            ->orderBy('url_id')
            ->orderByDesc('created_at')
            ->get()
            ->keyBy('url_id');

        return view('urls.index', ['urls' => $urls, 'lastChecks' => $lastChecks]);
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
}
