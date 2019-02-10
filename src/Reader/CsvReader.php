<?php
declare(strict_types=1);

namespace App\Reader;

class CsvReader
{
    public function read(string $file)
    {
        $handle = fopen($file, 'r');
        if ($handle === false) {
            throw new \Exception('file not found');
        }
        $items = [];
        while (($data = fgetcsv($handle)) !== false) {
            $items[] = $data;
        }

        return $items;
    }
}

