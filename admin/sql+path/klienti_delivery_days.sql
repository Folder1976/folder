ALTER TABLE  `tbl_klienti` ADD  `delivery_days` INT NOT NULL AFTER  `klienti_last_comment` ;
ALTER TABLE  `tbl_klienti` ADD  `price_coef` FLOAT NOT NULL AFTER  `delivery_days` ;

