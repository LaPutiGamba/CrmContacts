<?php
require_once("./edit/model.php");
$menu = new Contact();
$allSelectedContactsStatus = $menu->selectAllContactsStatus();
$allSelectedContacts = $menu->selectAllContacts();
?>