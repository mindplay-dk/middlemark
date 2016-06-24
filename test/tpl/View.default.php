<?php

/**
 * @var View $view
 */

use mindplay\middlemark\View;

?>
<!DOCTYPE html>
<html>

<head>
    <title><?= htmlspecialchars($view->doc->getTitle() ?: "No Title") ?></title>
</head>

<body>
<?= $view->body ?>
</body>

</html>