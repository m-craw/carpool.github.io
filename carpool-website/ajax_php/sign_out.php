<?php
$dir = realpath(__DIR__ . '/..').'/vendor/autoload.php';
require_once ($dir);
\Codebird\Codebird::setConsumerKey('AeqD1VXFLxni6wINS9AzYZhJv', '44fHeMU0GzeK5tcFjUhi8ESiqgDwU79UUqtsF9i0nc6IpF8EKI'); // static, see README


$cb = \Codebird\Codebird::getInstance();

$cb->logout();
?>