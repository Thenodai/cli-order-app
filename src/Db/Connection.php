<?php
declare(strict_types=1);

namespace App\Db;

use App\Entity\Order;

class Connection
{
    private $connection;

    public function __construct()
    {
        $this->connection = new \PDO('mysql:host=localhost;dbname=cli-app', 'root', '');
    }

    public function save(Order $order)
    {
        $prepared = $this->connection->prepare('INSERT INTO orders (email, meal, comment, date) VALUES (?, ?, ?, ?)');
        $prepared->execute(
            [
                $order->getEmail(),
                $order->getMeal(),
                $order->getComment(),
                $order->getDate(),
            ]
        );
        if ($prepared){
            return $this->connection->lastInsertId();
        }
    }

    public function update(Order $order)
    {
        $prepared = $this->connection->prepare('UPDATE orders SET email=:email, meal=:meal, comment=:comment, date=:date WHERE id=:id');

        return $prepared->execute([
            'id' => $order->getId(),
            'email' => $order->getEmail(),
            'meal' => $order->getMeal(),
            'comment' => $order->getComment(),
            'date' => $order->getDate(),
        ]);
    }

    public function findById(string $id)
    {
        $prepared = $this->connection->prepare('SELECT * FROM orders where id=:id');
        $prepared->execute(['id' => $id]);
        $prepared->setFetchMode(\PDO::FETCH_CLASS, Order::class);

        return $prepared->fetch();
    }

    public function deleteOrder($id)
    {
        $prepared = $this->connection->prepare('DELETE FROM orders WHERE id=:id');
        $prepared->execute(['id' => $id]);
        if (!$prepared->rowCount()) {
            throw new \Exception('No order with this id');
        }
    }

    public function findAll()
    {
        $prepared = $this->connection->prepare('SELECT * FROM orders');
        $prepared->execute();
        $prepared->setFetchMode(\PDO::FETCH_CLASS, Order::class);
        return $prepared->fetchAll();
    }
}
