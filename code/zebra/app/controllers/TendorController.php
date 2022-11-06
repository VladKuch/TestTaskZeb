<?php
namespace App\Controller;

use Phalcon\Mvc\Controller;
use Phalcon\Mvc\View;
use App\Helpers\TendorImportHelper;
use Phalcon\Mvc\Model\Query;
use Phalcon\Di;

class TendorController extends Controller {

    public function indexAction()
    {
        $this->view->setRenderLevel(View::LEVEL_NO_RENDER);
        return $this->response->setStatusCode(200)->setContent("Hello World!");
    }

    public function getAction(string $number = '')
    {
        if (!empty($number)) {
            $this->view->setRenderLevel(View::LEVEL_NO_RENDER);
            $query = new Query(
                'SELECT t.code, t.number, s.name as status, t.name, DATE_FORMAT(t.updated_at, "%d.%m.%Y %H:%i:%S") as date 
                FROM App\Models\Tendor as t 
                LEFT JOIN App\Models\Status s ON t.status = s.id 
                WHERE t.number = :number:',
                $this->di
            );
    
            $result = reset($query->execute(['number' => $number])->toArray());
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
        $directions = ['asc', 'desc'];
        $where_str = '';
        $where_arr = [];
        $execute_arr = [];
        $dir = 'asc';
        if (!empty($name)) {
                $where_arr[] = 't.name LIKE :name:';
                $execute_arr['name'] = '%'.$name.'%';
        }

        // По описанию задания понял, что нужно фильтровать именно по дате, не учитывая время. Возможно ошибся
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
            FROM App\Models\Tendor as t 
            LEFT JOIN App\Models\Status s ON t.status = s.id
            $where_str 
            ORDER BY t.updated_at $dir",
            $this->di
        );

        $result = $query->execute($execute_arr)->toArray();

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
            if (!empty($number) && !empty($name)) {
                $save_arr = [$number, $status, $name];
                $import = new TendorImportHelper();
                $result = $import->addRow($save_arr);
                if (!$result) {
                    $result = ['message' => "Что-то пошло не так."];
                    if (!empty($import->getErrors())) {
                        $result = $import->getErrors();
                    }
                } else {
                    $result = ['message' => "Тендор успешно добавлен."];
                }
            } else {
                $result = ['message' => "number и name - обязательные параметры."];
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
            
            $import = new TendorImportHelper();
            $result = $import->importFromCSV($file->getTempName());
            if (!$result) {
                $result = ['message' => "Что-то пошло не так."];
                if (!empty($import->getErrors())) {
                    $result = $import->getErrors();
                }
            } else {
                $result = ['message' => "Тендоры успешно импортированы."];
            }
            $this->response->setJsonContent($result, JSON_PRETTY_PRINT);
           
            return $this->response;
        }
    }
}