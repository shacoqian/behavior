<?php
#----------------------------------
#@description �����ļ�
#@author      �ӱߵ���ţ
#@email       qianfeng5511@163.com
#----------------------------------


$host = 'localhost';
$port = 3306;
$username = 'root';
$password = 'eric3yang';
$dbname = 'behavior';

include_once('mysql.php');

$mysql = new Mysql($host, $port, $username, $password, $dbname );
