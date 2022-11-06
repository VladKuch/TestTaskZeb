<?php
namespace App\Helpers;

use App\Models\Tender;
use App\Models\Status;

class TenderImportHelper {
    private array $keys = ['number', 'status', 'name'];
    private array $statuses = [];
    private array $errors = []; 

    public function __construct() 
    {
        $this->statuses = Status::fetch();
    }

    /**
     * Добавить и сохранить тендeр
     * 
     * @param array $tender_data
     * 
     * @return bool
     */
    public function addRow(array $tender_data): bool
    {
        if (!empty($tender_data)) {
            return $this->saveTender($tender_data);
        }
        return false;
    } 
    
    /**
     * Импортировать тендeры из CSV файла
     * 
     * @param string $file_path - путь к временному файлу
     * 
     * @return true  
     */
    public function importFromCSV(string $file_path): bool 
    {
        try {
            $is_first_row = true;
            if (($handle = fopen($file_path, "rb")) !== FALSE) {
                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    if (!$is_first_row) {
                        $data = array_slice($data, 1, -1);
                        $this->saveTender($data);
                    } else {
                        $is_first_row = false;
                    }
                }
                fclose($handle);
            }
            return true;
        } catch (\Trowable $e) {
            $this->errors['errors'][] = $e->getMessage();
            return false;
        }
    }

    /**
     * Сохранить тендeр
     * @param array $tender_data
     * 
     * @return bool
     */
    private function saveTender(array $tender_data): bool
    {
        try {
            $tender = new Tender();
            $tender_data = array_combine($this->keys, $tender_data);
            $tender_data['status'] = $this->statuses[$tender_data['status']] ?? 0;
            $tender->assign($tender_data);
            $status = $tender->create();
            return $status;
        } catch (\Throwable $e) {
            $this->errors['errors'][] = json_encode($tender_data) . ': ' .$e->getMessage();
            return false;
        }
    }

    /**
     * Получить список ошибок
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}