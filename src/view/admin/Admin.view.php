<?php

$start = microtime(true);

// including the admin pages template file
include __DIR__ . '/admin.template.view.php';
include __DIR__ . '/admin.loader.controller.php';
include __DIR__ . '/../error/errorpages.model.php';
include __DIR__ . '/../users/usersauthenticate.controller.php';

use Controller\Admin\AdminPagesLoader;

$loader = new AdminPagesLoader($_SERVER['REQUEST_URI']);


$_SESSION['userPrivileges'] = 'a';

$className = $loader->extractClassName();
echo $className;
if (class_exists($className)) {
    $page = $className::createInstance();
    $page->renderPage();
} else {
    include __DIR__ . '/pages/admin.home.view.php';

    $homePage = AdminPagesHome::createInstance();
    $homePage->renderPage();
}




// foreach ($_SERVER as $key => $value) {
//     if (is_array($value)) {
//         echo "[$key]:   " . implode(", ", $value) . "<br>";
//     } else {
//         echo "[$key]:   $value" . "<br>";
//     }
// }

// print_r($_POST);
// print_r($_GET);
echo microtime(true) - $start;
?>
<form action="" method="GET">
    <input type="text" name="test" value="hej">
    <input type="submit">
</form>