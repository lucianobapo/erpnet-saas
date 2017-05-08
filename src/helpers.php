<?php
/**
 * Created by PhpStorm.
 * User: luciano
 * Date: 08/05/17
 * Time: 12:37
 */

if (! function_exists('t')) {
    /**
     * Translate the given message.
     *
     * @param  string  $key
     * @param  array  $replace
     * @param  string  $locale
     * @return \Illuminate\Contracts\Translation\Translator|string
     */
    function t($key = null, $replace = [], $locale = null)
    {
        $translated = app('translator')->getFromJson('erpnetSaas::spark.'.$key, $replace, $locale);

        if(strpos($translated,'erpnetSaas::spark.')!==false)
            return app('translator')->getFromJson($key, $replace, $locale);

        return $translated;
    }
}