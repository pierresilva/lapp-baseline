<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use pierresilva\OpeningHours\Exceptions\Exception;
use pierresilva\TranslationLoader\LanguageLine;

class LanguagesController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        //
        $languageLines = LanguageLine::query();
        // (1)filltering
        $languageLines = $this->filtering($request, $languageLines);
        $languageLines = $languageLines->get();

        // (2)sorts
        $languageLines = $this->sorting($request, $languageLines);

        // (3)paginate
        $languageLines = $languageLines->paginate($request->input('perPage') ?? 10);

        return $this->responseSuccess('Lista obtenida con éxito!', [
            'data' => $this->getArrayData($this->getData($languageLines->toArray())),
            'meta' => $this->getMeta($languageLines->toArray()),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        //
        $requestData = $request->all();

        $this->validate($request, [
            'key' => 'required',
            'group' => 'required',
            'text.en' => 'required',
            'text.es' => 'required',
        ]);

        \DB::beginTransaction();

        try{
            $newLangLine = LanguageLine::create($requestData);
        } catch (\Exception $exception) {
            \DB::rollBack();

            return $this->responseError('Ocurrió un error!', (config('app.env') != 'production') ? [
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
            ] : []);
        }

        \DB::commit();

        return $this->responseSuccess('Item creado con éxito!', $newLangLine->toArray(), false, true);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        //
        $langLine = LanguageLine::findOrFail($id);

        return $this->responseSuccess('Item obtenido con éxito!', $langLine->toArray(), false);
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
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Exception
     */
    public function update(Request $request, $id)
    {
        //
        $requestData = $request->all();

        $this->validate($request, [
            'key' => 'required',
            'group' => 'required',
            'text.en' => 'required',
            'text.es' => 'required',
        ]);

        $langLine = LanguageLine::findOrFail($id);

        \DB::beginTransaction();

        try{
            $langLine->update($requestData);
        } catch (\Exception $exception) {
            \DB::rollBack();

            return $this->responseError('Ocurrió un error!', (config('app.env') != 'production') ? [
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
            ] : []);
        }

        \DB::commit();

        return $this->responseSuccess('Item actualizado con éxito!', $langLine->toArray(), false, true);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy($id)
    {
        //
        $langLine = LanguageLine::findOrFail($id);
        $langLine->delete();
        return $this->responseSuccess('Item elimminado con éxito!', $langLine->toArray(), false, true);
    }
}
