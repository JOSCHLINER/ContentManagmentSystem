<?php 
    use Controller\Error\ResponseMessages;
?>

<!-- 
    Template for all admin pages, with calls to the right functions already inside.
    Can only be used by the AdminPagesTemplate class and its children.
 -->
<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="/static/css/AdminStyle.css">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <div class="container">
        <nav class="sidebar">
            <label for="navigation-list" class="sidebar-list-label">Main</label>
            <ul id="navigation-list">
                <li class="navigation-item"><a href="/admin/home">Home</a></li>
                <li class="navigation-item"><a href="/admin/settings">Settings</a></li>
                <li class="navigation-item"><a href="/admin/settings/edit">Edit Settings</a></li>
                <li class="navigation-item"><a href="/admin/pages/create">Create page</a></li>
            </ul>
        </nav>

        <div class="settings">
            <nav class="settings-path">
                <!-- Path to setting -->
                <?= $this->renderSettingsPath() ?>
            </nav>
            <div class="dashboard-container">
                <span class="dashboard-name">
                    <!-- Settings name -->
                    <?= $this->renderSettingsName() ?>
                </span>
                <main class="dashboard">
                    <!-- here are the settings placed -->
                    <?= ResponseMessages::displayMessage() ?>
                    <?= $this->renderSettingsDashboard() ?>
                </main>
            </div>
        </div>
    </div>
</body>

</html>