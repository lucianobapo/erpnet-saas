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
        $locale = config('app.locale');
        $translated = __('erpnetSaas::erpnetSaas.'.$key, $replace, $locale);

        if(strpos($translated,'erpnetSaas::erpnetSaas.')!==false){
            $translatedFallback = __('erpnetSaas.'.$key, $replace, $locale);
            if(strpos($translatedFallback,'erpnetSaas.')!==false){
                $translator = __($key, $replace, $locale);
                return $translator;
            }
            return $translatedFallback;
        }

        return $translated;
    }
}