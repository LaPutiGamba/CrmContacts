<?php
require_once("../model.php");
$menu = new Contact();
$selectedContactStatus = $menu->selectContactStatus($_GET["STATEID"]);
$selectedContact = $menu->selectContact($_GET["ID"]);

require_once("../view/vShowEditingContact.php");
?>