<?php
namespace App\Controllers;

use Phalcon\Mvc\Controller;
use Phalcon\Mvc\View;
use App\Helpers\TenderImportHelper;
use App\Repositories\TenderRepository;

class TenderController extends Controller {

    public function indexAction()
    {
        $this->view->setRenderLevel(View::LEVEL_NO_RENDER);
        return $this->response->setStatusCode(200)->setContent("Hello World!");
    }

    public function getAction(string $number = '')
    {
        $this->view->setRenderLevel(View::LEVEL_NO_RENDER);
        $result = [];

        if (!empty($number)) {
            $result = TenderRepository::findOne(['number' => $number]);
        }

        if (empty($result)) {
            $result = ['message' => "По данному запросу ничего не было найдено."];
        }

        $this->response->setJsonContent($result, JSON_PRETTY_PRINT);

        return $this->response;
    }

    public function fetchAction()
    {
        $this->view->setRenderLevel(View::LEVEL_NO_RENDER);
        
        $name =   $this->request->getQuery('name');
        $date = $this->request->getQuery('date');
        $order = $this->request->getQuery('order');

        $result = TenderRepository::findAll($name, $date, $order);

        if (empty($result)) {
            $result = ['message' => "По данному запросу ничего не было найдено."];
        }

        $this->response->setJsonContent($result, JSON_PRETTY_PRINT);
        
        return $this->response;
    }

    public function addAction()
    {
        if ($this->request->isPost()) {
            $number = $this->request->getPost('number');
            $status = $this->request->getPost('status');
            $name =   $this->request->getPost('name');

            try {
                \App\Helpers\ValidateHelper::validateInputData(['number' => $number, 'name' => $name]);
                $import = new TenderImportHelper();
                $import->addRow([$number, $status, $name]);
                $result = ['message' => "Тендер успешно добавлен."];
            } catch(\Throwable $error) {
                $result = ['message' => $error->getMessage()];
            }  
            
            $this->response->setJsonContent($result, JSON_PRETTY_PRINT);
            return $this->response;
        }
    }

    public function importAction()
    {
        if ($this->request->isPost()) {
            $files = $this->request->getUploadedFiles();
            $file = reset($files);
            try {
                $import = new TenderImportHelper();
                $import->importFromCSV($file->getTempName());
                $result = ['message' => "Тендеры успешно импортированы."];
            } catch (\Throwable $error) {
                $result = ['message' => $error->getMessage()];
            }
            $this->response->setJsonContent($result, JSON_PRETTY_PRINT);
           
            return $this->response;
        }
    }
}