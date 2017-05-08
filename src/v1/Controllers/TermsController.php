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
        $file = base_path('terms.md');
        $content = "Arquivo `".$file."` não foi encontrado. Sem conteúdo para mostrar.";
        if (file_exists($file))
            $content = file_get_contents($file);

        $terms = (new Parsedown)->text($content);

        return view('erpnetSaas::terms', compact('terms'));
    }
}
