<?php
$page = (isset($_GET['page']) && !is_numeric($_GET['page']) && !is_null($_GET['page'])) ? $_GET['page'] : 'erreur-javascript';
include_once('includes/views/blocks/head/view.block-head.php');
include_once('includes/views/blocks/menu/view.block-menu.php');
include_once('includes/views/menus/erreur-javascript-page/view.block-milieu.php');
include_once('includes/views/blocks/footer/view.block-footer.php');
?>