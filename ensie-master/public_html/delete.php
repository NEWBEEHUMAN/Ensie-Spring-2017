<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
$path = '/home/ensie/domains/ensie.nl/public_html/unzip/ensie';
if (PHP_OS === 'Windows')
{
    exec("rd /s /q {$path}");
}
else
{
    exec("rm -rf {$path}");
}
?>