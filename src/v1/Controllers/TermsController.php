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
        $terms = (new Parsedown)->text(file_get_contents(resource_path('views/vendor/erpnetSaas/terms.md')));

        return view('erpnetSaas::terms', compact('terms'));
    }
}
