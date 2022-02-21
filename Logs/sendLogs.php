<?php
require '../connection.php';
require './mailLog.php';

$conn = new connection();
$conn->getUserList();
foreach ($conn->userList as $username)
    new mailLog($conn, $username);
