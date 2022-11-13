<?php
namespace App\Repositories;

use Phalcon\Mvc\Model\Query;
use Phalcon\Di\Di;

class TenderRepository {

    public static function findOne(array $params = []) 
    {
        $di = Di::getDefault();
        
        $where_str = "";
        if (!empty($params)) {
            $keys = array_keys($params);
            $where_str = "WHERE ";
            $where_str .= array_reduce($keys, function ($carry, $item) {
                return $carry . "t.$item = :$item: AND ";
            });

            $where_str = substr($where_str, 0, -4);
        }
        $query = new Query(
            "SELECT t.code, t.number, s.name as status, t.name, DATE_FORMAT(t.updated_at, '%d.%m.%Y %H:%i:%S') as date 
            FROM App\Models\Tender as t 
            LEFT JOIN App\Models\Status s ON t.status = s.id 
            $where_str",
            $di
        );

        return reset($query->execute($params)->toArray());
    }

    public static function findByNameAndDate($name, $date, $order)
    {
        $di = Di::getDefault();
        $directions = ['asc', 'desc'];
        $where_str = '';
        $where_arr = [];
        $execute_arr = [];
        $dir = 'asc';

        if (!empty($name)) {
            $where_arr[] = 't.name LIKE :name:';
            $execute_arr['name'] = '%'.$name.'%';
        }

        if (!empty($date)) {
            $where_arr[] = "DATE(t.updated_at) = STR_TO_DATE(:date:, '%d.%m.%Y')";
            $execute_arr['date'] = $date;
        } 

        if (!empty($where_arr)) {
            $where_str = " WHERE " . implode(', ', $where_arr);
        }

        if (!empty($order) && in_array(strtolower($order), $directions)) {
            $dir = $order;
        }

        $query = new Query(
            "SELECT t.code, t.number, s.name as status, t.name, DATE_FORMAT(t.updated_at, '%d.%m.%Y %H:%i:%S') as date 
            FROM App\Models\Tender as t 
            LEFT JOIN App\Models\Status s ON t.status = s.id
            $where_str 
            ORDER BY t.updated_at $dir",
            $di
        );

        return $query->execute($execute_arr)->toArray();
    }
}

