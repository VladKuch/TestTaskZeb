<?php
namespace App\Helpers;

use App\Models\Tendor;
use App\Models\Status;

class TendorImportHelper {
    private array $keys = ['number', 'status', 'name'];
    private array $statuses = [];
    private array $errors = []; 

    public function __construct() 
    {
        $this->statuses = Status::fetch();
    }

    /**
     * Добавить и сохранить тендр
     * 
     * @param array $tendor_data
     * 
     * @return bool
     */
    public function addRow(array $tendor_data): bool
    {
        if (!empty($tendor_data)) {
            return $this->saveTendor($tendor_data);
        }
        return false;
    } 
    
    /**
     * Импортировать тендоры из CSV файла
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
                        $this->saveTendor($data);
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
     * Сохранить тендр
     * @param array $tendor_data
     * 
     * @return bool
     */
    private function saveTendor(array $tendor_data): bool
    {
        try {
            $tendor = new Tendor();
            $tendor_data = array_combine($this->keys, $tendor_data);
            $tendor_data['status'] = $this->statuses[$tendor_data['status']] ?? 0;
            $tendor->assign($tendor_data);
            $status = $tendor->create();
            return $status;
        } catch (\Throwable $e) {
            $this->errors['errors'][] = json_encode($tendor_data) . ': ' .$e->getMessage();
            return false;
        }
    }

    public function getErrors()
    {
        return $this->errors;
    }
}