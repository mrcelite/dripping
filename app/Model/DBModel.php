<?php
namespace App\Model;

class DBModel {

    public function array2object($array)
    {
        if (is_array($array)) {
            $obj = new StdClass();
            foreach ($array as $key => $val){
                $obj->$key = $val;
            }
        } else {
            $obj = $array;
        }

        return $obj;
    }

    public function object2array($object)
    {
        if (is_object($object)) {
            foreach ($object as $key => $item) {
                if (is_object($item)) {
                    $item = self::object2array($item);
                }
                $array[$key] = $item;
            }
        } else {
            foreach ($object as $key => $item) {
                if (is_object($item)) {
                    $item = self::object2array($item);
                }
                $array[$key] = $item;
            }
        }
        return $array;
    }
}