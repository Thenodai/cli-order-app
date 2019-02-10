<?php
declare(strict_types=1);

namespace App\Validator;

use App\Entity\Order;

class OrderValidator
{
    public function validate(Order $order)
    {
        $violations = [];
        if ($order->getEmail() === null) {
            $violations[] = 'email';
        }
        if ($order->getMeal() === null){
            $violations[] = 'meal';
        }
        if ($order->getDate() === null) {
            $violations[] = 'date';
        } elseif ($order->getDate() !== null && !$this->normalizeDate($order->getDate())) {
            $violations[] = 'date';
        }

        if (count($violations) > 0) {
            return $violations;
        }

        return false;
    }

    private function normalizeDate(string $date): bool
    {
        $tempDate = explode('-', $date);
        if (count($tempDate) !== 3) {
            throw new \Exception('Wrong date format');
        }
        return checkdate((int)$tempDate[1], (int)$tempDate[2], (int)$tempDate[0]);
    }
}

