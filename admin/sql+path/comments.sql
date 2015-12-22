ALTER TABLE  `tbl_comments` CHANGE  `comments_tovar`  `comments_tovar` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL ;
ALTER TABLE  `tbl_comments` CHANGE  `comments_klient`  `comments_name` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL ;
ALTER TABLE  `tbl_comments` ADD  `comments_email` VARCHAR( 150 ) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL AFTER  `comments_name` ;
ALTER TABLE  `tbl_comments` ADD  `comments_date` DATETIME NOT NULL AFTER  `comments_tovar` ;
