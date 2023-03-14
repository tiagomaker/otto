<?php

    require_once '../tarefa02/index.php';

    class Pmweb_Orders_Stats_Controller {

        private $stats;

        public function __construct() {
            $this->stats = new Pmweb_Orders_Stats();
        }

        public function handleRequest() {
            // Check if start and end dates are provided as parameters
            if (!isset($_GET['startDate']) || !isset($_GET['endDate'])) {
                http_response_code(400); // Bad Request
                echo json_encode(array("error" => "startDate and endDate parameters are required"));
                return;
            }

            // Set start and end dates
            $startDate = $_GET['startDate'];
            $endDate = $_GET['endDate'];
            $this->stats->setStartDate($startDate);
            $this->stats->setEndDate($endDate);

            // Calculate the statistics
            $ordersCount = $this->stats->getOrdersCount();
            $ordersRevenue = $this->stats->getOrdersRevenue();
            $ordersQuantity = $this->stats->getOrdersQuantity();
            $ordersRetailPrice = $this->stats->getOrdersRetailPrice();
            $ordersAvgOrderValue = $this->stats->getOrdersAverageOrderValue();

            // Encapsulate the statistics in JSON format
            $result = array(
                "orders" => array(
                    "count" => $ordersCount,
                    "revenue" => $ordersRevenue,
                    "quantity" => $ordersQuantity,
                    "averageRetailPrice" => $ordersRetailPrice,
                    "averageOrderValue" => $ordersAvgOrderValue
                )
            );
            echo json_encode($result);
        }
    }

    // Instantiate the controller and handle the request
    $controller = new Pmweb_Orders_Stats_Controller();
    $controller->handleRequest();
?>
