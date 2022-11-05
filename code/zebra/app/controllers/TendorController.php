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

    public function getAction(int $code = 0)
    {
        if (!empty($code)) {
            $this->view->setRenderLevel(View::LEVEL_NO_RENDER);

            $query = new Query(
                'SELECT t.code, t.number, s.name as status, t.updated_at 
                FROM App\Models\Tendor as t 
                LEFT JOIN App\Models\Status s ON t.status = s.id 
                WHERE t.code = :code:',
                $this->di
            );
    
            $tendor = $query->execute(['code' => $code]);

            $this->response->setJsonContent($tendor, JSON_PRETTY_PRINT);
        }
        
        return $this->response;
    }

    public function addAction()
    {
        if ($this->request->isPost()) {
            $code =   $this->request->getPost('code');
            $number = $this->request->getPost('number');
            $status = $this->request->getPost('status');
            $name =   $this->request->getPost('name');

            $save_arr = [$code, $number, $status, $name];
            $tendors = new TendorImportHelper();
            $result = $tendors->addRow($save_arr);
            $this->response->setJsonContent($result, JSON_PRETTY_PRINT);
            return $this->response;
        }
    }

    public function importAction()
    {
        if ($this->request->isPost()) {
            $files = $this->request->getUploadedFiles();
            $file = reset($files);
            
            $tendors = new TendorImportHelper();
            $res = $tendors->importFromCSV($file->getTempName());
            $this->response->setJsonContent($res, JSON_PRETTY_PRINT);
           
            return $this->response;
        }
    }
}