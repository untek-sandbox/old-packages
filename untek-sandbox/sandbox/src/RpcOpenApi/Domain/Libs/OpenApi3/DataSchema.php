<?php

namespace Untek\Sandbox\Sandbox\RpcOpenApi\Domain\Libs\OpenApi3;

use DateTime;
use Untek\Core\Arr\Helpers\ArrayHelper;
use Untek\Core\Contract\Encoder\Interfaces\EncodeInterface;

class DataSchema implements EncodeInterface
{

    public function encode($data): array
    {
//        $oldDada = $data;
//        $isArr = is_array($data);
        $data = $this->encodeItem($data);
        /*if($isArr) {
            $data['example'] = $oldDada;
        }*/
        return $data;
    }

    function encodeItem($data): array
    {
        $item = [
            'description' => "Field description",
        ];
        /*if(is_string($data) && !is_numeric($data) && strtotime($data)) {
            $data = date(DateTime::ISO8601, $data);
        }*/
        if (is_array($data) && ArrayHelper::isIndexed($data)) {
            $item['type'] = 'array';
            if($data) {
                $item['items'] = $this->encode($data[0]);
//                $item['example'] = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
//                $item['example'] = $data;
            }
        } elseif (is_array($data) && ArrayHelper::isAssociative($data)) {
            $item['type'] = 'object';
            $item['properties'] = $this->encodeItems($data);
//            $item['example'] = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
//            $item['example'] = $data;
//            dd($item['example']);
        } else {
            $item['type'] = gettype($data);
            $item['example'] = $data;
        }
        return $item;
    }

    function encodeItems(array $data): array
    {
        $res = [];
        foreach ($data as $fieldName => $value) {
            $res[$fieldName] = $this->encode($value);
        }
        return $res;
    }
}
