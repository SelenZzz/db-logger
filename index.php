<?php
require './connection.php';
require './DB/auth.php';

$conn = new connection();
if($opts=getopt("",array("server:", "base:", "event:", "status:")))
    $conn->addRow($opts);
else
    $conn->showTable();