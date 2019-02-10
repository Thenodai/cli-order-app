<?php
declare(strict_types=1);

namespace App\Normalizer;

use App\Entity\Order;

class OrderNormalizer
{
    public function mapToEntity(array $data)
    {
        if (count($data) !== 4) {
            throw new \Exception('cannot normalize data');
        }
        $preparedData = array_combine(['email', 'meal', 'comment', 'date'], $data);
        $order = new Order();
        if ($preparedData['email'] !== '') {
            $order->setEmail($preparedData['email']);
        }
        if ($preparedData['meal'] !== '') {
            $order->setMeal($preparedData['meal']);
        }
        if (isset($preparedData['comment'])) {
            $order->setComment($preparedData['comment']);
        }
        if ($preparedData['date'] !== '') {
            $order->setDate($preparedData['date']);
        };

        return $order;
    }

    public function mapToArray(Order $order)
    {
        return [
          'id' => $order->getId(),
          'email' => $order->getEmail(),
          'meal' => $order->getMeal(),
          'comment' => $order->getComment(),
          'date' => $order->getDate(),
        ];
    }
}
