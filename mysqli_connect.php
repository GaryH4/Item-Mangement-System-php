<?php
DEFINE ('DB_USER','maker');
DEFINE ('DB_PASSWORD','imakerkitLib');
DEFINE ('DB_HOST','127.0.0.1');
DEFINE ('DB_NAME','imakerkit');

$dbc=@mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME) OR die('Could not to connect to Mysql:'.mysqli_connect_error());

mysqli_set_charset($dbc, 'utf8');
