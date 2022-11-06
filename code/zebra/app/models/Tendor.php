<?php

namespace App\Models;

use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Behavior\Timestampable;

class Tendor extends Model {
    public $code;
    public $number;
    public $status;
    public $name;
    public $created_at;
    public $updated_at;

    public function initialize()
    {
        $this->setSource('tendors');
    }
}