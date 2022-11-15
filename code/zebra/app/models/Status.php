<?php

namespace App\Models;

use Phalcon\Mvc\Model;

class Status extends Model {
    public $id;
    public $name;

    public function initialize()
    {
        $this->setSource('ref_statuses');
    }

    public static function fetch(): array
    {
        $tmp = self::find()->toArray();
        
        if (empty($tmp)) {
            return [];
        }

        $keys = array_column($tmp, 'name');
        $values = array_column($tmp, 'id');

        return array_combine($keys, $values);
    }
}