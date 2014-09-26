
CREATE TABLE IF NOT EXISTS `log_messages` (
	`id` INTEGER NOT NULL PRIMARY KEY,
	`channel` VARCHAR (255) NOT NULL,
	`user` VARCHAR (255) NOT NULL,
	`message` TEXT NOT NULL,
	`created` DATETIME NOT NULL
);
CREATE INDEX `idx_created` ON `log_messages` (`created` ASC);
CREATE INDEX `idx_channel` ON `log_messages` (`channel` ASC);
