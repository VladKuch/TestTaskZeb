<?php
namespace App\Controller;

use Phalcon\Mvc\Controller;
use Phalcon\Mvc\View;

class ApiController extends Controller {

    public function indexAction()
    {
        $this->view->setRenderLevel(View::LEVEL_NO_RENDER);
        $this->response->setJsonContent(["test" => "Hello World"], JSON_PRETTY_PRINT, 512);
        return $this->response;
    }
}