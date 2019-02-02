<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SchemaParserController extends Controller
{
    //

    public function parse(Request $request) {
        $tables = [];

        foreach ($request->schema['tables'] as $table) {
            $tables[$table['id']] = [
                'name' => $table['name'],
                'comment' => isset($table['comment']) ? $table['comment'] : null,
                'logActivities' => isset($table['logActivities']) ? $table['logActivities'] : null,
                'softDelete' => $table['softDelete'],
                'timeStamp' => $table['timeStamp'],
                'columns' => [],
                'relations' => [],
            ];
            foreach ($request->schema['columns'] as $key => $value) {
                if ($key === $table['id']) {
                    foreach ($value as $val) {
                        $tables[$table['id']]['columns'][] = [
                            "name" => $val['name'],
                            "type" => $val['type'],
                            "length" => $val['length'],
                            "defValue" => $val['defValue'],
                            "comment" => $val['comment'],
                            "autoInc" => $val['autoInc'],
                            "nullable" => $val['nullable'],
                            "unique" => $val['unique'],
                            "index" => $val['index'],
                            "unsigned" => $val['unsigned'],
                        ];
                        if (null !== $val['foreignKey']['references']['id'] && null !== $val['foreignKey']['on']['id']) {
                            $tables[$table['id']]['relations'][] = [
                                'column' => $val['name'],
                                'references' => $val['foreignKey']['references']['name'],
                                'on' => $val['foreignKey']['on']['name'],
                                'type' => $val['foreignKey']['type'],
                                'method' => $val['foreignKey']['method'],
                            ];
                        }
                    }
                }
            }
        }

        dd($tables);
    }
}
