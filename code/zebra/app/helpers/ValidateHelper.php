<?php
namespace App\Helpers;

class ValidateHelper {

    /**
     * Валидация входных данных
     */
    public static function validateInputData(array $input_data): bool
    {
        foreach ($input_data as $key => $value) {
            if (empty($value)) {
                throw new \Exception("$key - обязательный параметр");
            }
        }

        return true;
    }
}