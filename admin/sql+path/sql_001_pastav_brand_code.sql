CREATE TABLE IF NOT EXISTS `tbl_tovar_postav_artikl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tovar_artkl` varchar(100) CHARACTER SET utf8 NOT NULL,
  `tovar_postav_artkl` varchar(100) CHARACTER SET utf8 NOT NULL,
  `postav_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) 