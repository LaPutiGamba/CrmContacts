<?php
require_once ("../model.php");

if (session_status() == PHP_SESSION_NONE)
  session_start();

if ($_POST['CSRF_TOKEN'] != $_SESSION['CSRF_TOKEN'])
  die('Invalid CSRF token');

$_SESSION['CSRF_TOKEN'] = bin2hex(random_bytes(32));

$menu = new Contact();
$color = "#ffffff";
if ($_POST["STATE"] == "WITHOUT STATE") {
  $color = "#ffffff";
} else if ($_POST["STATE"] == "START CONTACT") {
  $color = "#b3e5fc";
} else if ($_POST["STATE"] == "IN CONTACT") {
  $color = "#c8e6c9";
} else if ($_POST["STATE"] == "WITHOUT CONTACT") {
  $color = "#bdbdbd";
} else if ($_POST["STATE"] == "WAITING ANSWER") {
  $color = "#fff9c4";
} else if ($_POST["STATE"] == "PENDING ANSWER") {
  $color = "#ffe0b2";
} else if ($_POST["STATE"] == "GOOD RELATIONSHIP") {
  $color = "#a5d6a7";
} else if ($_POST["STATE"] == "BAD RELATIONSHIP") {
  $color = "#ef9a9a";
}

$idStatusAdded = false;
$contactAdded = false;

$idStatusAdded =
  $menu->addContactStatus(
    $_POST["STATE"],
    $color
  );

if ($idStatusAdded) {
  $contactAdded =
    $menu->addContact(
      $idStatusAdded,
      $_POST["PERSON"],
      $_POST["ENTERPRISE"],
      $_POST["COUNTRY"],
      $_POST["CSV"],
      $_POST["EMAIL"],
      $_POST["PHONE"],
      $_POST["WEB"],
      $_POST["OTHER_MEDIA"],
      $_POST["RECORD"]
    );
}

if ($idStatusAdded && $contactAdded)
  echo true;
else
  echo false;
?>