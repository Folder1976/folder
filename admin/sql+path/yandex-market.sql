CREATE TABLE IF NOT EXISTS `tbl_yandex_market_setup` (
  `MarketCategID` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `MarketCategNazv` varchar(100) NOT NULL,
  `ShopCategoryID` int(10) unsigned NOT NULL,
  `MarketPath` text NOT NULL,
  `CategViewTovDisabled` enum('0','1') NOT NULL DEFAULT '0',
  `CategClickPrice` float unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`MarketCategID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO `tbl_functions` (`function_id`, `function_alias`, `function_name`, `function_patch`, `function_level`, `function_sort`) VALUES
('', 'market', 'Файл для Яндекс.Маркет', 'market/index.php', 1000, 1);