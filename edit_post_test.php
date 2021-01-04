<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>
<?php
  foreach($_POST['id_detail_donasi'] as $twat){
    echo $twat.'<br>';
  }
?>
<hr/>

<?php
print "<pre>";
print_r($_REQUEST);
print_r($_FILES);
print "</pre>";
?>

</body>
</html>
