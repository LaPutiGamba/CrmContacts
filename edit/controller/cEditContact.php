<?php
require_once ("../model.php");

if (session_status() == PHP_SESSION_NONE)
  session_start();

if ($_POST['CSRF_TOKEN'] != $_SESSION['CSRF_TOKEN'])
  die ('Invalid CSRF token');

$_SESSION['CSRF_TOKEN'] = bin2hex(random_bytes(32));

$menu = new Contact();
$edited = $menu->editContact(
  $_GET["ID"],
  $_GET["STATEID"],
  $_POST["PERSON"],
  $_POST["ENTERPRISE"],
  $_POST["COUNTRY"],
  $_POST["CSV"],
  $_POST["EMAIL"],
  $_POST["PHONE"],
  $_POST["WEB"],
  $_POST["OTHER_MEDIA"],
  $_POST["RECORD"],
  $_POST["STATE"]
);

if ($edited) {
  echo "<script>
  alert(\"The " . $_POST["PERSON"] . " with ID " . $_GET["ID"] . " was modified SUCCESFULLY.\");
  window.location.replace(\"../../index.php\");
  </script>";
} else {
  echo "<script>
  alert(\"ERROR!\");
  window.location.replace(\"../../index.php\");
  </script>";
}
?>