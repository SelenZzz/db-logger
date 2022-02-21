<?php
require '../ini.php';
global $username, $password;
if (!isset($_SERVER['PHP_AUTH_USER'])) {
    header('WWW-Authenticate: Basic realm=""');
    header('HTTP/1.0 401 Unauthorized');
    echo "Аутентификация отклонена\n";
    exit;
} else if ($_SERVER['PHP_AUTH_USER']===$username and $_SERVER['PHP_AUTH_PW']===$password){
    echo "Hello {$_SERVER['PHP_AUTH_USER']}.\n";
    echo "Вы ввели пароль {$_SERVER['PHP_AUTH_PW']}.\n";
}