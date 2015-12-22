CREATE TABLE IF NOT EXISTS `tbl_orders` (
  `order_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_customer` varchar(150) COLLATE utf8_bin NOT NULL,
  `order_product_id` int(11) NOT NULL,
  `order_item` int(11) NOT NULL,
  PRIMARY KEY (`order_id`)
) AUTO_INCREMENT=1 ;

ALTER TABLE  `tbl_orders` ADD  `delivery_days` INT NOT NULL AFTER  `product_postav_id` ;