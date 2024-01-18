<?php

//phpinfo();

require __DIR__. "/database.model.php";

$conn = new DatabaseConnection("contentmanagment", "mysql", "root", "root");
$result = $conn -> _unsafe_query("SHOW TABLES;");
print_r($result -> fetch_assoc());

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