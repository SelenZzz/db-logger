<?php
require "../ini.php";
require '../connection.php';

global $db, $logs_tbl, $events_tbl, $users_tbl, $servername, $username, $password;
// connect
$conn = new mysqli($servername, $username, $password);
// create db
$sql = "CREATE DATABASE IF NOT EXISTS $db";
$conn->query($sql);
// use db
$sql = "USE $db";
$conn->query($sql);
// create tables
$query = file_get_contents("logger.sql");
$conn->multi_query($query);


