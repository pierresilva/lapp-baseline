<?php

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use pierresilva\Modules\Facades\Module;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('settings', function () {
    $settings = \App\Setting::get()->toArray();
    $settingsResponse = [];

    foreach ($settings as $key => $value) {
        $settingsResponse[$value['group']][$value['key']] = $value['value'];
    }
    return response()->json($settingsResponse, 200);
});

Route::any('/schema', function (Request $request) {
    $model = "{$request->model}";

    if (!class_exists($model)) {
        return response()->json(['message' => 'model_unavailable'], 500);
    }

    $modelInstance = new $model();

    return response()->json(
        [
            'message' => 'Model obtained!',
            'data' => [
                'table' => $modelInstance->getTableColumns(),
            ]
        ]
    );
});

Route::get('users/{userId?}', function ($userId = null) {

    if (!$userId) {
        $users = \App\User::all()->toArray();

        return response()->json([
            'message' => 'usuarios obtenidos con éxito!',
            'data' => $users,
        ], 200);
    }

    $user = \App\User::findOrFail($userId)->toArray();

    return response()->json([
        'message' => 'usuario obtenido con éxito!',
        'data' => $user,
    ], 200);
});

Route::get('translations/{lang}', function ($lang) {
    app()->setLocale($lang);

    $translationFiles = File::files(base_path('resources/lang/' . app()->getLocale()));

    $modules = Module::all();

    if ($modules) {
        foreach ($modules as $module) {
            $moduleLangFiles = File::files(base_path('app/Modules/' . $module['basename'] . '/Resources/Lang/' . app()->getLocale()));
            foreach ($moduleLangFiles as $moduleLangFile) {
                $index = count($translationFiles);
                $translationFiles[$index] = $moduleLangFile;
            }
        }
    }

    $files = [];
    foreach ($translationFiles as $path) {
        $files[] = pathinfo($path);
    }

    $langs = [];

    foreach ($files as $file) {
        if (get_string_between($file['dirname'], 'Modules/', '/Resources')) {
            $langs['module.' . strtolower(get_string_between($file['dirname'],
                'Modules/', '/Resources')) . '.' . $file['filename']] = File::getRequire($file['dirname'] . '/' . $file['basename']);
            continue;
        }
        $langs[$file['filename']] = File::getRequire($file['dirname'] . '/' . $file['basename']);
    }

    $dbLangs = \pierresilva\TranslationLoader\LanguageLine::getTranslationsForGroup($lang);

    foreach ($langs as $langKey => $langValue) {
        foreach ($dbLangs as $dbLangKey => $dbLangValue) {
            if ($dbLangKey == $langKey) {
                foreach ($dbLangValue as $key => $value) {
                    $langs[$langKey][$key] = $value;
                }
            }
        }
    }

    array_walk_recursive(
        $langs,
        function (&$value) {
            if (is_string($value)) {
                $value = ngx_translate_parse($value);
            }
        }
    );

    return response()->json([
        'translations' => $langs,
    ], 200);
});

Route::get('/tests', function (\Request $request) {
    sleep(3);
    return response()->json([
        'status' => 1,
        'message' => 'Not Found',
        'response' => [
            'errors' => [
                'some-1' => 'Error One',
                'some-2' => 'Error Two',
            ],
        ],
    ], 404);
});


Route::get('/test', function (\Request $request) {
    sleep(3);
    return response()->json([
        'message' => 'Some Message',
        'status' => 0,
        'time' => Carbon::now()->timestamp,
        'response' => [
            'data' => 'Some Data',
            'meta' => null
        ],
    ]);
});

Route::any('/schema/parse', 'Api\SchemaParserController@parse');

Route::post('/test/test', function (Request $request) {
    sleep(3);
    $validator = Validator::make($request->all(), [
        'email' => 'required|email',
        'name' => 'required|min:6'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'message' => 'Validation error!',
            'status' => 1,
            'time' => Carbon::now()->timestamp,
            'response' => [
                'errors' => $validator->errors()->toArray()
            ],
        ], 422);
    }

    return response()->json([
        'message' => 'Some Message',
        'status' => 0,
        'time' => Carbon::now()->timestamp,
        'response' => [
            'data' => 'Some Data',
            'meta' => null
        ],
    ]);
});

Route::group([
    'namespace' => 'Api',
    'middleware' => 'api',
    'prefix' => 'auth',
], function () {
    Route::post('login', 'AuthController@login');
    Route::post('signup', 'AuthController@signup');
    Route::get('signup/activate/{token}', 'AuthController@signupActivate');
    Route::post('signup/activate/resend', 'AuthController@resendActivationCode');
    Route::group([
        'middleware' => 'auth:api',
    ], function () {
        Route::get('logout', 'AuthController@logout');
        Route::get('user', 'AuthController@user');
    });
    Route::group([
        'prefix' => 'password',
    ], function () {
        Route::post('create', 'PasswordResetController@create');
        Route::get('find/{token}', 'PasswordResetController@find');
        Route::post('reset', 'PasswordResetController@reset');
    });
});


/**
 * ADMIN ROUTES
 */
Route::group([
    'namespace' => 'Api\Admin',
    'middleware' => 'api',
    'prefix' => 'admin'
], function () {
    Route::resource('languages', 'LanguagesController');
});

function ngx_translate_parse($value)
{
    preg_match_all(
        "|(.:)(.*)[\s.]|U",
        $value,
        $out
    );
    if (isset($out[0])) {
        $ouN = 0;
        foreach ($out[0] as $ou) {
            $value = str_replace($ou, ' {{ ' . $out[2][$ouN] . ' }} ', $value);
            $ouN++;
        }
    }
    return $value;
}

function get_string_between($str, $from, $to)
{
    $sub = substr($str, strpos($str, $from) + strlen($from), strlen($str));
    return substr($sub, 0, strpos($sub, $to));
}
