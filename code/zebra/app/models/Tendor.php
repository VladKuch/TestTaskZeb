<?php

namespace App\Models;

use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Behavior\Timestampable;

class Tendor extends Model {
    public $id;
    public $code;
    public $number;
    public $status;
    public $name;
    public $created_at;
    public $updated_at;

    public function initialize()
    {
        $this->setSource('tendors');

        $this->addBehavior(
            new Timestampable(
                [
                    'beforeCreate' => [
                        'field'  => 'created_at',
                        'format' => 'Y.m.d H:i:s',
                    ]
                ]
            )
        );

        $this->addBehavior(
            new Timestampable(
                [
                    'beforeCreate' => [
                        'field'  => 'updated_at',
                        'format' => 'Y.m.d H:i:s',
                    ],
                    'beforeUpdate' => [
                        'field'  => 'updated_at',
                        'format' => 'Y.m.d H:i:s',
                    ]
                ]
            )
        );
    }
}