<?php

namespace ErpNET\Saas\v1\Controllers;

use Parsedown;

class TermsController extends Controller
{
    /**
     * Show the terms of service for the application.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $terms = (new Parsedown)->text(file_get_contents(base_path('terms.md')));

        return view('terms', compact('terms'));
    }
}
