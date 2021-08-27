<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class CheckController extends Controller
{
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

        $now = now();
        $checkData = [
            'url_id' => $id,
            'status_code' => $statusCode,
            'created_at' => $now,
            'updated_at' => $now
        ];

        DB::table('url_checks')->insert($checkData);
        DB::table('urls')->where('id', '=', $id)->update(['updated_at' => $now]);

        flash('Страница успешно проверена')->success();

        return back();
    }
}
