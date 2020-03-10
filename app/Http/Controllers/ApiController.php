<?php

namespace App\Http\Controllers;

use phpDocumentor\Reflection\Types\Boolean;

class ApiController extends Controller
{
    protected $perPage = 20;

    /**
     * Return a response error
     *
     * @param string $message
     * @param array $errors
     * @param integer $code
     * @param integer $status
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseError(
        string $message = 'Error',
        array $errors = [],
        int $code = 400,
        int $status = 1
    ) : \Illuminate\Http\JsonResponse {
        $body = [
            'status' => $status,
            'message' => $message,
            'response' => [
                'errors' => $errors,
            ],
        ];

        return response()->json($body, $code);
    }

    /**
     * Return a success response
     *
     * @param string $message
     * @param array $data
     * @param bool $meta
     * @param integer $code
     * @param integer $status
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseSuccess(
        string $message = 'OK',
        array $data = [],
        bool $meta = true,
        bool $notify = false,
        int $code = 200,
        int $status = 0
    ) : \Illuminate\Http\JsonResponse {
        $body = [
            'status' => $status,
            'message' => $message,
            'notify' => $notify,
            'response' => [
                'data' => $data['data'] ?? $this->getData($data),
                'meta' => $meta ? ($data['meta'] ?? $this->getMeta($data)) : null,
            ],
        ];

        return response()->json($body, $code);
    }

    /**
     * Return a pagination meta array
     *
     * @param array $data
     * @return array
     */
    public function getMeta(array $data = []) : array
    {
        if (isset($data['data']) && null !== @$data['data']) {
            unset($data['data']);
            return $data;
        }

        return $data;
    }

    /**
     * Return pagination data   array
     *
     * @param array $data
     * @return array
     */
    public function getData(array $data = []): array
    {
        if (isset($data['data']) && null !== @$data['data']) {
            return $data['data'];
        }

        return $data;
    }

    /**
     * Filter the query
     *
     * @param $request
     * @param $query
     * @return mixed
     */
    public function filtering($request, $query)
    {
        if (is_array($request->input('q'))) {

            foreach ($request->input('q') as $key => $value) {

                if ($key !== 's') {

                    $parts = preg_split('~:(?=[^:]*$)~', $key);

                    $searchParts = preg_split('~\.(?=[^\.]*$)~', $parts[0]);
                    // dd($parts, $searchParts);

                    $collumn_name = $searchParts[0];
                    $related_column_name = isset($searchParts[1]) ? $searchParts[1] : '';

                    $operatorSymbol = isset($parts[1]) ? $parts[1] : 'cont';

                    if ($operatorSymbol == 'eq') {
                        $operator = '=';
                    } elseif ($operatorSymbol == 'cont') {
                        $operator = 'like';
                        $value = '%' . $value . '%';
                    } elseif ($operatorSymbol == 'gt') {
                        $operator = '>=';
                    } elseif ($operatorSymbol == 'lt') {
                        $operator = '<=';
                    } else {
                        $operator = 'like';
                        $value = '%' . $value . '%';
                    }

                    if ($related_column_name !== '') {  // search at related table column
                        $query = $query->whereHas($collumn_name, function ($q) use ($related_column_name, $operator, $value) {
                            $q->where($related_column_name, $operator, $value);
                        });
                    } else {
                        $query = $query->where($collumn_name, $operator, $value);
                    }
                }
            }
        }

        return $query;
    }

    /**
     * Sorting the query
     *
     * @param $request
     * @param $query
     * @return mixed
     */
    public function sorting($request, $query)
    {
        $q_s = $request->input('q.s');
        if ($q_s) {
            // sort dir and sort column
            if (substr($q_s, -5, 5) === ':desc') {
                $sort_column = substr($q_s, 0, strlen($q_s) - 5);
                $query = $query->sortByDesc($sort_column);
            } elseif (substr($q_s, -4, 4) === ':asc') {
                $sort_column = substr($q_s, 0, strlen($q_s) - 4);
                $query = $query->sortBy($sort_column);
            } else {
                $sort_column = $q_s;
                $query = $query->sortByDesc($sort_column);
            }

        } else {
            $query = $query->sortByDesc('id');
        }

        return $query;
    }

    /**
     * @param $data
     * @return array
     */
    protected function getArrayData($data)
    {

        $array = [];

        foreach ($data as $key => $value) {
            $array[] = $value;
        }

        return $array;

    }

}
