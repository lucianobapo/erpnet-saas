# erpnet-saas

## Start using
```shell
php artisan vendor:publish --provider=ErpNET\Permissions\Providers\ErpnetPermissionsServiceProvider --force
php artisan vendor:publish --tag=erpnetPermissions --force
php artisan vendor:publish --tag=publicSaas --force
php artisan vendor:publish --tag=configSaas
```

## Develop assets
```shell
npm install gulp font-awesome bootstrap-sass vue@1.0.0 vue-resource@0.1.11 vue-toastr@^1.0.4 underscore promise moment laravel-elixir laravel-elixir-browserify-official --save-dev
npm install gulp font-awesome bootstrap-sass vue vue-resource underscore promise moment laravel-elixir laravel-elixir-browserify-official --save-dev
php artisan vendor:publish --tag=assetsSaas --force
php artisan vendor:publish --tag=translationsSaas --force
gulp --gulpfile gulpfileErpnetSaas.js
```

[Site do GitHub](https://github.com/lucianobapo/erpnet-saas)

[![Latest Stable Version](https://poser.pugx.org/ilhanet/erpnet-saas/v/stable)](https://packagist.org/packages/ilhanet/erpnet-saas) 
[![Total Downloads](https://poser.pugx.org/ilhanet/erpnet-saas/downloads)](https://packagist.org/packages/ilhanet/erpnet-saas) 
[![Latest Unstable Version](https://poser.pugx.org/ilhanet/erpnet-saas/v/unstable)](https://packagist.org/packages/ilhanet/erpnet-saas) 
[![License](https://poser.pugx.org/ilhanet/erpnet-saas/license)](https://packagist.org/packages/ilhanet/erpnet-saas)