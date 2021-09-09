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
     * @param int $id
     * @return RedirectResponse
     * @throws InvalidSelectorException
     */
    public function checkStore(int $id): RedirectResponse
    {
        $url = DB::table('urls')->find($id);

        if (is_null($url)) {
            abort(404);
        }

        try {
            $response = HTTP::get($url->name);
        } catch (\Exception $e) {
            flash($e->getMessage())->error();
            return back();
        }

        $statusCode = $response->status();

        $dom = new Document($response->body());
        $h1 = optional($dom->first('h1'))->text();
        $keywords = optional($dom->first('meta[name=keywords]'))->getAttribute('content');
        $description = optional($dom->first('meta[name=description]'))->getAttribute('content');

        $now = now();
        $checkData = [
            'url_id' => $id,
            'status_code' => $statusCode,
            'h1' => $h1,
            'keywords' => $keywords,
            'description' => $description,
            'created_at' => $now,
            'updated_at' => $now
        ];

        DB::table('url_checks')->insert($checkData);
        DB::table('urls')->where('id', '=', $id)->update(['updated_at' => $now]);

        flash('Страница успешно проверена')->success();

        return back();
    }
}
