DELIMITER $$
CREATE DEFINER=`root`@`localhost` FUNCTION `get_options`(`in_good_id` INT(8)) RETURNS varchar(128) CHARSET utf8
    READS SQL DATA
BEGIN
	DECLARE options VARCHAR(128);
        SELECT GROUP_CONCAT(`goods_options_values`.`value` SEPARATOR ' / ') INTO options FROM `goods_options_values` LEFT JOIN `goods_options` ON `goods_options`.`id` = `goods_options_values`.`option_id` WHERE `goods_options_values`.`good_id` = in_good_id AND `goods_options_values`.`status` = '1' AND `goods_options`.`status` = '1';
	RETURN options;
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `get_status`(`in_status_id` INT(4)) RETURNS varchar(128) CHARSET utf8
    READS SQL DATA
BEGIN
	RETURN (SELECT `name` FROM `orders_status` WHERE `id` = in_status_id LIMIT 1);
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `get_tags`(`in_variation_id` INT(8)) RETURNS varchar(128) CHARSET utf8
    READS SQL DATA
BEGIN
	DECLARE tags VARCHAR(128);
	SELECT GROUP_CONCAT(`tags`.`value` SEPARATOR ' / ') INTO tags FROM `tags` LEFT JOIN `tags_groups` ON `tags_groups`.`id` = `tags`.`group_id` WHERE `tags`.`id` IN (SELECT `tag_id` FROM `tags_links` WHERE `variation_id` = in_variation_id AND `status` = '1') AND `tags`.`status` = '1' AND `tags_groups`.`type` = '1' AND `tags_groups`.`status` = '1' ORDER BY `tags_groups`.`position` ASC;
	IF tags IS NOT NULL THEN
		RETURN tags;
	ELSE
        	RETURN '';
	END IF;
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `good_on`(`in_good_id` INT(8)) RETURNS int(8)
    READS SQL DATA
BEGIN
	RETURN (SELECT `goods`.`id` FROM `goods` LEFT JOIN `shops` ON `shops`.`id` = `goods`.`shop_id` WHERE `goods`.`id` = in_good_id AND `goods`.`show` = '1' AND `goods`.`confirm` = '1' AND `goods`.`status` = '1' AND `shops`.`status` = '1' LIMIT 1);
END$$

DELIMITER ;

CREATE TABLE IF NOT EXISTS `auth_assignment` (
  `item_name` varchar(64) NOT NULL,
  `description` varchar(255) NOT NULL,
  `user_id` varchar(64) NOT NULL,
  `created_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`item_name`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `auth_item` (
  `name` varchar(64) NOT NULL,
  `type` int(11) NOT NULL,
  `description` text,
  `rule_name` varchar(64) DEFAULT NULL,
  `data` text,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`),
  KEY `rule_name` (`rule_name`),
  KEY `type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `auth_item_child` (
  `parent` varchar(64) NOT NULL,
  `child` varchar(64) NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `auth_rule` (
  `name` varchar(64) NOT NULL,
  `data` text,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `auth_assignment`
  ADD CONSTRAINT `auth_assignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `auth_item`
  ADD CONSTRAINT `auth_item_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `auth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE `auth_item_child`
  ADD CONSTRAINT `auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;
