<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

class AssetController extends Controller
{
    public function css(): Response
    {
        return response(file_get_contents(public_path('static/assets/app-DFSc02Nj.css')), 200, [
            'Content-Type' => 'text/css',
            'Cache-Control' => 'public, max-age=31536000, immutable',
        ]);
    }

    public function js(): Response
    {
        return response(file_get_contents(public_path('static/assets/app-CohnTwkU.js')), 200, [
            'Content-Type' => 'application/javascript',
            'Cache-Control' => 'public, max-age=31536000, immutable',
        ]);
    }
}
