<?php

namespace ErpNET\Saas\v1\Controllers;


use ErpNET\Saas\v1\Services\ErpnetSparkService;

class DataController extends Controller
{

    public function index()
    {
        $tabs = ErpnetSparkService::dataTabs()->displayable();

        return view('erpnetSaas::data.home', compact('tabs'));
    }
}
