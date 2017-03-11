<?php

//设置时区
date_default_timezone_set("PRC");

/* 取得当前根目录 */
define('ROOT_PATH', str_replace('includes/init.php', '', str_replace('\\', '/', __FILE__)));

/* 初始化数据库类 */
require(ROOT_PATH . 'data/config.php');
require(ROOT_PATH . 'includes/cls_mysql.php');
$db = new cls_mysql(DB_HOST, DB_USER, DB_PASS, DB_NAME);

/*公共方法 */
require(ROOT_PATH . 'includes/functions.php');

session_start();


?>