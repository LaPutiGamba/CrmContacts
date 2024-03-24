<?php
if (session_status() == PHP_SESSION_NONE)
  session_start();
require_once ("../model.php");

$menu = new Contact();

$allSelectedContactsStatus = $menu->selectAllContactsStatus();
$allSelectedContacts = $menu->selectAllContacts();

function generateTableRows($allSelectedContacts, $allSelectedContactsStatus)
{
  $tableRows = '';

  if ($allSelectedContacts && $allSelectedContactsStatus) {
    foreach ($allSelectedContacts as $contact) {
      if (empty ($contact))
        continue;

      $state = '';
      $color = '';
      foreach ($allSelectedContactsStatus as $status) {
        if ($contact["STATE"] == $status["ID"]) {
          $stateID = $status["ID"];
          $state = $status["STATE"];
          $color = $status["COLOR"];
          break;
        }
      }

      if ($state && $color) {
        $tableRows .= '<tr style="">';
        if ($_SESSION["USERTYPE"] == "Admin") {
          $tableRows .= '<td class="removeTableStyle"><div class="centerStyle"><input class="checkBox" type="checkbox" name="contacts[]" value="' . $contact["ID"] . ',' . $stateID . '"></div></td>';
        }
        $tableRows .= '<td class="basicTableBoxStyle" style="background-color:' . $color . '"><b>' . $state . '</b></td><td class="basicTableBoxStyle">' . $contact["ENTERPRISE"] . ' <i>(' . $contact["COUNTRY"] . ')</i><br>' . $contact["PERSON"] . '</td><td class="basicTableBoxStyle"><b>EMAIL</b><br><a target="_blank" href="mailto:' . $contact["EMAIL"] . '">' . $contact["EMAIL"] . '</a><br><b>PHONE</b><br>' . $contact["PHONE"] . '<br><b>WEBSITE</b><br><a target="_blank" href="' . $contact["WEB"] . '">' . parse_url($contact["WEB"], PHP_URL_HOST) . '</a><br><b>OTHER</b><ul>';
        $input = $contact["OTHER_MEDIA"];
        $lines = explode("\n", $input);

        foreach ($lines as $line) {
          $parts = explode("@", $line);

          if (count($parts) == 2) {
            $platform = strtolower(trim($parts[0]));
            $platform = substr($platform, 0, -1);
            $username = trim($parts[1]);

            $url = "https://www." . $platform . ".com/" . $username;

            $tableRows .= '<li><a target="_blank" href="' . $url . '">' . $line . '</a></li>';
          }
        }
        $tableRows .= '</ul></td><td class="basicTableBoxStyle">' . $contact["CSV"] . '</td><td class="basicTableBoxStyle">' . $contact["RECORD"] . '</td>';
        if ($_SESSION["USERTYPE"] == "Admin") {
          $tableRows .= '<td class="basicTableBoxStyle"><i class="fa fa-edit icon editButton" aria-hidden="true" data-href="./edit/controller/cModifyContact.php?ID=' . $contact["ID"] . '&STATEID=' . $stateID . '"></i><i class="fa fa-trash-o icon deleteButton" aria-hidden="true" data-href="./edit/controller/cDeleteContact.php?ID=' . $contact["ID"] . '&STATEID=' . $stateID . '"></i></td>';
        }
        $tableRows .= '</tr>';
      }
    }

    return $tableRows;
  }
}

echo generateTableRows($allSelectedContacts, $allSelectedContactsStatus);
?>