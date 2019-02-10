<?php
declare(strict_types=1);

namespace App\Writer;

class CsvWriter
{
    public function write(array $orders)
    {
        $handle = fopen('exported.csv', 'w');
        foreach ($orders as $order) {
            fputcsv($handle, $order);
        }
        $finished = fclose($handle);

        return $finished;
    }
}

