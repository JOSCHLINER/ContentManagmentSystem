<?php
// including the admin pages template file
include __DIR__ . '/admin.template.view.php';

/* The autoloader as it is is a temporary solution */
// autoloader for the right pages
spl_autoload_register( function (string $className)
{
    $filepath = __DIR__ . '/pages/admin.' . strtolower(substr($className, 10)) . '.view.php';
    if (file_exists($filepath)) {
        include $filepath;
    }
});

$_SESSION['userPrivileges'] = 'a';
$page = AdminPagesSettings::createInstance();
$page->renderPage();

foreach ($_SERVER as $key => $value) {
    if (is_array($value)) {
        echo "[$key]:   " . implode(", ", $value) . "<br>";
    } else {
        echo "[$key]:   $value" . "<br>";
    }
}

print_r($_POST);
print_r($_GET);
?>
<form action="" method="POST">
    <input type="text" name="test" value="hej">
    <input type="submit">
</form>