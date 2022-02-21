<?php
require '../connection.php';

$conn = new connection();
if($conn->isAuthorized()) {
    if (!empty($_GET)) $conn->addLog($_GET);
} else echo "Authorization denied";