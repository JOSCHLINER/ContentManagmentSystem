<?php

//phpinfo();

require __DIR__. "/database.model.php";

$conn = new DatabaseConnection("contentmanagment", "127.0.0.1", "root", "root");
echo $conn.query("SHOW TABLES;");

?>

<!DOCTYPE html>
<html lang="en">
<head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Document</title>
</head>
<body>
     <p><?php echo 'We are up and running PHP version: ' . phpversion();?></p>
</body>
</html>