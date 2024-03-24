<?php
require_once("../model.php");
$menu = new Contact();
$deleted = $menu->deleteContact($_GET["ID"], $_GET["STATEID"]);
echo $deleted;
?>