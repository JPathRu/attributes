CREATE TABLE IF NOT EXISTS `#__attrs` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`published` INT NOT NULL,
	`name` VARCHAR(100) NOT NULL,
	`title` VARCHAR(100) NOT NULL,
	`tp` VARCHAR(10) NOT NULL,
	`val` TEXT NOT NULL,
	`multiple` INT NOT NULL,
	`filter` VARCHAR(20) NOT NULL,
	`class` VARCHAR(50) NOT NULL,
	`destsystem` TINYINT NOT NULL,
	`destmenu` TINYINT NOT NULL,
	`destarticles` TINYINT NOT NULL,
	`destcategories` TINYINT NOT NULL,
	`destmodules` TINYINT NOT NULL,
	`destplugins` TINYINT NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
