<?php


$targetFolder = $_SERVER['DOCUMENT_ROOT'] . '/../momo-application/storage/app/public';
$linkFolder = $_SERVER['DOCUMENT_ROOT'] . '/winners/storage/';
symlink($targetFolder, $linkFolder) or die("error creating symlink");
echo 'Symlink process successfully completed';
?>
