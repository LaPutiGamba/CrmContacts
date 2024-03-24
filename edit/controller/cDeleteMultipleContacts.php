<?php
require_once ("../model.php");
$menu = new Contact();

$contacts = $_POST["contacts"];

$deleted = $menu->deleteMultipleContacts($contacts);

if ($deleted)
  echo true;
else
  echo false;
?>