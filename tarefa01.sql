CREATE TABLE `pedido` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `order_date` datetime NOT NULL,
  `product_sku` varchar(12) NOT NULL,
  `size` varchar(2) NOT NULL,
  `color` varchar(20) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(8,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1