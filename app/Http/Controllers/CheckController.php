<?php

namespace App\Http\Controllers;

use DiDom\Document;
use DiDom\Exceptions\InvalidSelectorException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class CheckController extends Controller
{
    /**
     * @param int $id
     * @return RedirectResponse
     * @throws InvalidSelectorException
     */
    public function store(int $id): RedirectResponse
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
