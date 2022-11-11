<?php
namespace App\Helpers;

use App\Models\Tender;
use App\Models\Status;

class TenderImportHelper {
    private array $keys = ['number', 'status', 'name'];
    private array $statuses = [];

    public function __construct() 
    {
        $this->statuses = Status::fetch();
    }

    /**
     * Добавить и сохранить тендeр
     * 
     * @param array $tender_data
     * 
     */
    public function addRow(array $tender_data)
    {
        if (empty($tender_data)) {
           throw new \Exception("Входящие данные пустые.");
        }

        $this->saveTender($tender_data);
    } 
    
    /**
     * Импортировать тендeры из CSV файла
     * 
     * @param string $file_path - путь к временному файлу
     * 
     */
    public function importFromCSV(string $file_path)
    {
        if (($handle = fopen($file_path, "rb")) == FALSE) {
            throw new \Exception("Не удалось открыть файл");
        }
        
        fgetcsv($handle, 1000, ",");
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $data = array_slice($data, 1, -1);
                $this->saveTender($data);
        }
        fclose($handle);
    }

    /**
     * Сохранить тендeр
     * @param array $tender_data
     * 
     */
    private function saveTender(array $tender_data)
    {
        $tender = new Tender();
        $tender_data = array_combine($this->keys, $tender_data);
        $tender_data['status'] = $this->statuses[$tender_data['status']] ?? 0;
        $tender->assign($tender_data);
        $status = $tender->create();

        if (!$status) {
            throw new \Exception("Сохранить не удалось.");
        }
    }
}