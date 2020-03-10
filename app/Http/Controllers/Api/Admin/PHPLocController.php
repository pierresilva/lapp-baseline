<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;

class PHPLocController extends ApiController
{
    //

    public function getStatics(Request $request, $make = null)
    {

        if ($make) {
            \Artisan::call('code:analysis');
        }

        $statics = json_decode(file_get_contents(public_path('phploc/phploc.json')));

        return $this->responseSuccess('PHP Loc analysis load!', [
            'statics' => $statics
        ], false);
    }
}
