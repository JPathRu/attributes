ALTER TABLE `#__attrs` ADD IF NOT EXISTS `destusers` TINYINT NOT NULL AFTER `destmenu`;
ALTER TABLE `#__attrs` ADD IF NOT EXISTS `destcontacts` TINYINT NOT NULL AFTER `destusers`;
