<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
$zip = new ZipArchive;
$res = $zip->open('ensie.zip');
if ($res === TRUE) {
    echo $zip->extractTo('/home/ensie/domains/ensie.nl/public_html/unzip/');

    $zip->close();
    echo 'woot!';
} else {
    echo 'doh!';
}
?>