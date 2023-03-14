<?php

    require_once '../config.php';

    class Pmweb_Orders_Stats {
        private $db;
        private $start_date;
        private $end_date;

        public function __construct() {
            $this->db = new PDO(
                "mysql:host=" . DATABASE_HOSTNAME . ";dbname=" . DATABASE_DATABASE,
                DATABASE_USERNAME,
                DATABASE_PASSWORD
            );
        }

        public function setStartDate($date) {
            $this->start_date = $date;
        }

        public function setEndDate($date) {
            $this->end_date = $date;
        }

        public function getOrdersCount() {
            $stmt = $this->db->prepare("
                SELECT COUNT(*) as count
                FROM pedido
                WHERE order_date >= :start_date AND order_date <= :end_date
            ");
            $stmt->execute([
                'start_date' => $this->start_date,
                'end_date' => $this->end_date
            ]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['count'];
        }

        public function getOrdersRevenue() {
            $stmt = $this->db->prepare("
                SELECT SUM(price * quantity) as revenue
                FROM pedido
                WHERE order_date >= :start_date AND order_date <= :end_date
            ");
            $stmt->execute([
                'start_date' => $this->start_date,
                'end_date' => $this->end_date
            ]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['revenue'];
        }

        public function getOrdersQuantity() {
            $stmt = $this->db->prepare("
                SELECT SUM(quantity) as quantity
                FROM pedido
                WHERE order_date >= :start_date AND order_date <= :end_date
            ");
            $stmt->execute([
                'start_date' => $this->start_date,
                'end_date' => $this->end_date
            ]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['quantity'];
        }

        public function getOrdersRetailPrice() {
            $stmt = $this->db->prepare("
                SELECT AVG(price) as retail_price
                FROM pedido
                WHERE order_date >= :start_date AND order_date <= :end_date
            ");
            $stmt->execute([
                'start_date' => $this->start_date,
                'end_date' => $this->end_date
            ]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['retail_price'];
        }

        public function getOrdersAverageOrderValue() {
            $stmt = $this->db->prepare("
                SELECT SUM(price * quantity) / COUNT(DISTINCT order_id) as average_order_value
                FROM pedido
                WHERE order_date >= :start_date AND order_date <= :end_date
            ");
            $stmt->execute([
                'start_date' => $this->start_date,
                'end_date' => $this->end_date
            ]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['average_order_value'];
        }

        public function getOrders() {
            $stmt = $this->db->prepare("
                SELECT DATE(order_date) AS order_date, COUNT(*) AS order_count
                FROM pedido
                WHERE order_date >= :start_date AND order_date <= :end_date
                GROUP BY DATE(order_date)
            ");
            $stmt->execute([
                'start_date' => $this->start_date,
                'end_date' => $this->end_date
            ]);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }

        public function getOrdersByDate() {
            $orders = $this->getOrders();
    
            foreach ($orders as $order) {
                $date = $order['order_date'];
                $count = $order['order_count'];
                $data[$date] = intval($count);
            }
        
            return json_encode(['data' => $data]);
        }
    }


    // $orders = new Pmweb_Orders_Stats();
    // $orders->setStartDate('2021-11-09');
    // $orders->setEndDate('2021-11-09');
    // $ordersByDate = $orders->getOrdersByDate();

    // print_r($ordersByDate);
?>