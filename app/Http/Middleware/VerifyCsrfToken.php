<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        //
        'admin/column/pictures_edit/pictures_save',
        'admin/product/pictures_edit/pictures_save',
        'admin/product/product_goods/pictures_edit/pictures_save',
        'admin/product/good_cats/pictures_edit/pictures_save',
        'admin/yiru/yiru_assets/actions/multipleimg'
    ];
}
