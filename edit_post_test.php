<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>


<?php
print "<pre>";
print_r($_REQUEST);
print_r($_FILES);
$id_titik[] = array_unique($_POST['dd_id_titik']);

print_r(array_filter($id_titik));
print "</pre>";


?>

</body>
</html>
