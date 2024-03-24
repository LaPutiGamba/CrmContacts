<?php
session_start();
require_once ("../model.php");
$menu = new Contact();
$_SESSION["USERTYPE"] = $menu->signIn($_POST["USERNAME"], $_POST["PASSWORD"]);

if ($_SESSION["USERTYPE"] != null) {
  echo "<script>
  window.location.replace(\"../../index.php\");
  </script>";
} else {
  echo "<script>
  alert(\"The username or the password was incorrect.\");
  window.location.replace(\"../../signIn.php\");
  </script>";
}
?>