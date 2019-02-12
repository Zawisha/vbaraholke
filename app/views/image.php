<?php
print_r($_FILES);

$uploaddir = '../app/images/'.'1.jpg';
$r= $_FILES['userfile']['tmp_name'];
echo move_uploaded_file($r, "$uploaddir")
?>

