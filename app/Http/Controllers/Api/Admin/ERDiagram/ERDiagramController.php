<?php

namespace App\Http\Controllers\Api\Admin\ERDiagram;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use pierresilva\OpeningHours\Exceptions\Exception;

class ERDiagramController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        //
        $files = collect(\Storage::disk('public')->files('er-diagram'))->sortKeysDesc()->values()->all();

        return $this->responseSuccess('Schema files successful loaded!', $files, false);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        //
        $data = $request->get('file');

        $fileName = 'er-diagram/' . date('YmdHis') . '.json';

        $jsonString = json_encode(json_decode($data), JSON_PRETTY_PRINT);

        try {
            \Storage::disk('public')->put($fileName, $jsonString);
        } catch (\Exception $exception) {
            return $this->responseError('Error saving schema file!', [
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
            ], 400, $exception->getCode());
        }
        return $this->responseSuccess('Schema successful saved!', [
            'file' => $fileName
        ], false);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
