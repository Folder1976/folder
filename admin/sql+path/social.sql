
ALTER TABLE  `tbl_klienti` ADD  `social_key` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL AFTER  `klienti_email` ;
ALTER TABLE  `tbl_klienti` ADD  `provider` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL AFTER  `social_key` ;