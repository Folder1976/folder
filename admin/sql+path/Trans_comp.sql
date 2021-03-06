CREATE TABLE IF NOT EXISTS `tbl_transp_comp` (
  `TranspID` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `TranspClass` varchar(255) NOT NULL,
  `TranspSmNazv` text NOT NULL,
  `TranspUrlZakaz` text NOT NULL,
  `TranspPhone` text NOT NULL,
  `TranspMail` text NOT NULL,
  `TranspAddr` text NOT NULL,
  `TranspFullNazv` text NOT NULL,
  `SummDostavToTK` smallint(5) unsigned NOT NULL,
  `SummDostavNacenka` smallint(5) NOT NULL DEFAULT '0',
  `SummLimitToNull` smallint(5) unsigned NOT NULL DEFAULT '0',
  `DefaultOplatID` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `letter_id_zknakl_full` tinyint(3) unsigned NOT NULL,
  `letter_id_zknakl_empty` tinyint(3) unsigned NOT NULL,
  `KurVoznagrajd` smallint(5) unsigned NOT NULL DEFAULT '0',
  `UsePartPredoplat` enum('0','1') NOT NULL DEFAULT '0',
  `SendDocNum` mediumint(8) unsigned NOT NULL,
  `ParentTranspID` smallint(5) unsigned NOT NULL,
  `IsEnable` enum('0','1') NOT NULL DEFAULT '1',
  PRIMARY KEY (`TranspID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=33 ;

--
-- Dumping data for table `tbl_transp_comp`
--

INSERT INTO `tbl_transp_comp` (`TranspID`, `TranspClass`, `TranspSmNazv`, `TranspUrlZakaz`, `TranspPhone`, `TranspMail`, `TranspAddr`, `TranspFullNazv`, `SummDostavToTK`, `SummDostavNacenka`, `SummLimitToNull`, `DefaultOplatID`, `letter_id_zknakl_full`, `letter_id_zknakl_empty`, `KurVoznagrajd`, `UsePartPredoplat`, `SendDocNum`, `ParentTranspID`, `IsEnable`) VALUES
(1, '', 'ПЭК', '', '', '', '', '', 0, 0, 0, 3, 23, 22, 150, '0', 0, 0, '1'),
(2, '', 'Желдорэкспедиция', '', '', '', '', '', 100, 100, 7000, 3, 23, 22, 200, '0', 0, 0, '1'),
(3, '', 'Объем', '', '', '', '', '', 400, 0, 15000, 3, 23, 22, 0, '0', 0, 0, '1'),
(4, '', 'Автотрейдинг', '', '', '', '', '', 250, 0, 10000, 3, 23, 22, 250, '0', 0, 0, '1'),
(5, '', 'КИТ', '', '', '', '', '', 0, 0, 7000, 3, 23, 22, 150, '0', 0, 0, '1'),
(6, '', 'Деловые линии', '', '', '', '', '', 100, 0, 15000, 3, 23, 22, 200, '0', 0, 0, '1'),
(7, '', 'РАТЭК', '', '', '', '', '', 400, 0, 20000, 3, 23, 22, 0, '0', 0, 0, '1'),
(8, '', 'Энергия', '', '', '', '', '', 250, 0, 15000, 3, 23, 22, 250, '0', 0, 0, '1'),
(9, '', 'Ир-Траст', '', '', '', 'Нагорный пр., д.12, кор.3\r\n8(495)-543-73-80, 8(499)-176-09-50 ', '', 400, 0, 20000, 3, 23, 22, 0, '0', 0, 0, '1'),
(10, '', 'ТРАНС-ПЭК', '', '', '', '', '', 400, 0, 20000, 3, 23, 22, 0, '0', 0, 0, '1'),
(11, '', 'Севертранс (Варшавское ш., д.170 Г)', '', '8-800-500-75-57, (495) 981-57-57, (985) 222-05-44', '', 'Москва, Варшавское ш., д.170 Г', '', 300, 0, 20000, 3, 23, 22, 500, '0', 0, 0, '1'),
(12, '', 'ТрансЛогист', '', '', '', '', '', 300, 0, 10000, 3, 23, 22, 0, '0', 0, 0, '1'),
(14, '', 'Стейл', '', '', '', 'Москва, поселение Московский, д. Саларьево, коммунальная зона, владение 5.', '', 500, 0, 20000, 3, 23, 22, 0, '0', 0, 0, '1'),
(15, '', 'Байкал Сервис', '', '', '', '', '', 500, 0, 30000, 3, 0, 0, 250, '0', 0, 0, '1'),
(17, '', 'Север КиМ', '', '', '', '', '', 500, 0, 20000, 3, 23, 22, 0, '0', 0, 0, '1'),
(18, '', 'ГлавДоставка', '', '+7 (495) 660-2049', '', 'ул. Молодогвардейская, д. 65', '', 250, 0, 10000, 2, 0, 0, 250, '0', 0, 0, '1'),
(19, '', 'Е-КИТ', '', '+7-912-642-43-00 Татьяна', 'malusya84@mail.ru', 'Менеджер Татьяна', '', 0, 0, 0, 3, 23, 22, 150, '1', 0, 5, '1'),
(20, '', 'Беломортранс', '', '', '', '', '', 600, 0, 30000, 3, 23, 22, 600, '0', 0, 0, '1'),
(21, '', 'GREENLINE', '', '8 (495) 783-60-01', '', 'http://tcgreenline.ru/filiali\r\nДмитровское шоссе, владение 62\r\n\r\nТел.: 8 (495) 783-60-01\r\n\r\nТел.: 8 (985) 143 67 77\r\n\r\nТел.: 8 (499) 745-96-82\r\n\r\nE-mail: 7836001@gmail.com\r\n\r\nE-mail: 7836001@mail.ru', '', 400, 0, 15000, 3, 23, 22, 500, '0', 0, 0, '1'),
(22, '', 'Азимут', '', '8-(916)-35-34-333,  8-(916)-65-60-555', '', 'ул. Верхняя Красносельская, 10а', '', 400, 0, 15000, 3, 23, 22, 500, '0', 0, 0, '1'),
(23, '', 'ЭМСК', '', '+7 (499) 260-98-48,  +7 (499) 262-51-49', '', 'г. Москва, Комсомольская площадь, д.3/49а', '', 400, 0, 20000, 3, 23, 22, 500, '0', 0, 0, '1'),
(24, '', 'СДЭК-НП', '', '8-8422-70-55-81 Мария / 8-800-250-04-05 горячая линия', '', '', '', 0, 50, 0, 3, 23, 22, 200, '1', 180, 30, '1'),
(25, '', 'МАС-Хэндлинг', '', '', '', '', '', 0, 0, 0, 3, 0, 0, 0, '0', 0, 0, '1'),
(26, '', 'РГ-ГРУПП', '', '', '', '', '', 300, 0, 10000, 3, 0, 0, 200, '0', 0, 0, '1'),
(27, '', 'Восточный транзит', '', '+7 (495) 778-73-77 пн-пт 9:00-18:00м', '', 'м. Университет\r\nул. Раменки, 43', '', 500, 0, 0, 3, 0, 0, 600, '0', 0, 0, '1'),
(28, '', 'Аэрогруз', '', '', '', 'ул. Подольских курсантов вл. 7', '', 500, 0, 0, 3, 0, 0, 600, '0', 0, 0, '1'),
(29, '', 'АДАМАНТ', '', '', '', '', '', 400, 0, 15000, 3, 0, 0, 500, '0', 0, 0, '1'),
(30, '', 'СДЭК', '', '8-8422-70-55-81 Мария / 8-800-250-04-05 горячая линия', '', '', '', 0, 50, 0, 3, 23, 22, 200, '0', 0, 0, '1'),
(31, '', 'ПЭК-НП', '', '', '', '', '', 0, 0, 10000, 3, 23, 22, 150, '1', 0, 1, '0'),
(32, '', 'Энергия НП', '', '', '', '', '', 250, 0, 15000, 3, 0, 0, 250, '1', 0, 8, '0');
