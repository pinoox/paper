<?php
/**
 *      ****  *  *     *  ****  ****  *    *
 *      *  *  *  * *   *  *  *  *  *   *  *
 *      ****  *  *  *  *  *  *  *  *    *
 *      *     *  *   * *  *  *  *  *   *  *
 *      *     *  *    **  ****  ****  *    *
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       pinoox.com
 * @copyright  pinoox
 */

namespace pinoox\app\com_pinoox_paper\model;

use pinoox\component\Config;

class UpdateModel extends PaperDatabase
{

    private static $tables = [
        'CREATE TABLE `{dbprefix}comment`  (
  `comment_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) UNSIGNED NULL DEFAULT NULL,
  `article_id` int(10) UNSIGNED NULL DEFAULT NULL,
  `user_id` int(10) UNSIGNED NULL DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL,
  `mobile` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL,
  `full_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL,
  `message` varchar(5000) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL,
  `insert_date` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`comment_id`) USING BTREE,
  INDEX `{dbprefix}comment_user_id`(`user_id`) USING BTREE,
  INDEX `{dbprefix}comment_parent_id`(`parent_id`) USING BTREE,
  CONSTRAINT `{dbprefix}comment_parent_id` FOREIGN KEY (`parent_id`) REFERENCES `{dbprefix}comment` (`comment_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `{dbprefix}comment_user_id` FOREIGN KEY (`user_id`) REFERENCES `{dbprefix}user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_bin ROW_FORMAT = Dynamic;',

        'CREATE TABLE `{dbprefix}page`  (
  `page_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `page_key` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL,
  `content` text CHARACTER SET utf8 COLLATE utf8_bin NULL,
  `status` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL,
  `insert_date` datetime(0) NULL DEFAULT NULL,
  `visitors` int(11) NULL DEFAULT 0,
  `visits` int(11) NULL DEFAULT 0,
  PRIMARY KEY (`page_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_bin ROW_FORMAT = Dynamic;',

        'CREATE TABLE `{dbprefix}contact`  (
  `contact_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `full_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL,
  `subject` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL,
  `mobile` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL,
  `message` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL,
  `insert_date` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`contact_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_bin ROW_FORMAT = Dynamic;',
        'ALTER TABLE `{dbprefix}settings` ADD COLUMN s_dataset text;',
        'UPDATE `{dbprefix}settings` SET s_dataset=\'{"fa":"فارسی","en":"english"}\' WHERE settings_id=11'
    ];



    public static function insert_tables()
    {
        $prefix = Config::get('~database.prefix');
        foreach (self::$tables as $table) {
            $create_table = str_replace('{dbprefix}', $prefix, $table);
            self::$db->rawQuery($create_table);
        }
    }

}