<?php
require_once("../model.php");
$menu = new Contact();

$selectedFilter = isset($_POST['label']) ? $_POST['label'] : '';

foreach ($selectedFilter as $filter) {
  $contacts = $menu->selectContactStatusByState($filter);
}
?>