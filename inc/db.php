<?php
require_once 'rb.php';

$db = [
    'dsn' => 'mysql:host=localhost;dbname=market;charset=utf8',
    'user' => 'root',
    'pass' => '',
];

R::setup($db['dsn'], $db['user'], $db['pass']);
R::freeze(true);
