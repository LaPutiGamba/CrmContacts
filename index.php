<?php
if (session_status() == PHP_SESSION_NONE)
	session_start();
require_once ("./edit/controller/cSelectAllContacts.php");
if (isset ($_GET["userLogOut"])) {
	session_destroy();
	echo "<script> window.location.replace(\"./signIn.php\"); </script>";
}
if (!isset ($_SESSION["USERTYPE"]) || $_SESSION["USERTYPE"] == null)
	echo "<script> window.location.replace(\"./signIn.php\"); </script>";
?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<title>CRM</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
	<script src="https://kit.fontawesome.com/dbe027e14f.js" crossorigin="anonymous"></script>
	<link href="./style.css" rel="stylesheet" type="text/css">
	<link href="https://fonts.googleapis.com/css2?family=Kanit&display=swap" rel="stylesheet">
</head>

<body>
	<header>
		<p id="logo">CONTACTS</p>
		<a href="./index.php?userLogOut=true" id="signIn" onclick><i class="fa fa-sign-out" aria-hidden="true"></i></a>
	</header>

	<table id="dataTable">
		<tr>
			<?php if ($_SESSION["USERTYPE"] == "Admin")
				echo '<td class="removeTableStyle"></td>';
			?>
			<td class="removeTableStyle" colspan="5">
				<?php if ($_SESSION["USERTYPE"] == "Admin")
					echo '<input class="checkBox" type="checkbox" id="selectAll">
				<i class="fa fa-trash-o icon deleteMultipleButton" aria-hidden="true" data-href="./edit/controller/cDeleteMultipleContacts.php"></i>';
				?>
				<i class="fa-solid fa-rotate-right icon refreshIconButton" aria-hidden="true"
					data-href="./edit/controller/cRefreshAllContacts.php"></i>
				<div class="dropdown">
					<i class="fa-solid fa-list icon dropdownButton"></i>
					<div class="dropdownContent" id="filterNumberOfRows">
						<label for="opt2"><input type="radio" id="opt2" name="dropdownOption" value="2"> 2</label>
						<label for="opt5"><input type="radio" id="opt5" name="dropdownOption" value="5"> 5</label>
						<label for="opt10"><input type="radio" id="opt10" name="dropdownOption" value="10"> 10</label>
						<label for="opt20"><input type="radio" id="opt20" name="dropdownOption" value="20"> 20</label>
						<label for="opt50"><input type="radio" id="opt50" name="dropdownOption" value="50"> 50</label>
					</div>
				</div>
				<input type="text" id="searchBar" placeholder="Search..." style="margin-left: 10px">
			</td>
			<?php if ($_SESSION["USERTYPE"] == "Admin")
				echo '<td class="removeTableStyle" style="text-align: right;">
					<i class="fa fa-address-card-o icon addContact" aria-hidden="true" data-href="./edit/view/vAddContact.php"></i>
					</td>';
			?>
		</tr>
		<tr>
			<?php if ($_SESSION["USERTYPE"] == "Admin")
				echo '<td class="removeTableStyle"></td>';
			?>
			<th>
				STATUS
				<div class="dropdown">
					<i class="fa-solid fa-filter filterIcon dropdownButton"></i>
					<div class="dropdownContent" id="filterStatus">
						<label><input type="checkbox" value="WITHOUT STATE"> WITHOUT STATE</label>
						<label><input type="checkbox" value="START CONTACT"> START CONTACT</label>
						<label><input type="checkbox" value="IN CONTACT"> IN CONTACT</label>
						<label><input type="checkbox" value="WITHOUT CONTACT"> WITHOUT CONTACT</label>
						<label><input type="checkbox" value="WAITING ANSWER"> WAITING ANSWER</label>
						<label><input type="checkbox" value="PENDING ANSWER"> PENDING ANSWER</label>
						<label><input type="checkbox" value="GOOD RELATIONSHIP"> GOOD RELATIONSHIP</label>
						<label><input type="checkbox" value="BAD RELATIONSHIP"> BAD RELATIONSHIP</label>
					</div>
				</div>
			</th>
			<th>
				ENTERPRISE
				<i class="fa-solid fa-sort filterIcon" id="sortEnterprise"></i>
			</th>
			<th>CONTACT</th>
			<th>
				TYPE
				<div class="dropdown">
					<i class="fa-solid fa-filter filterIcon dropdownButton"></i>
					<div class="dropdownContent" id="filterType">
						<label><input type="checkbox" value="PROGRAMMER"> PROGRAMMER</label>
						<label><input type="checkbox" value="2D ARTIST"> 2D ARTIST</label>
						<label><input type="checkbox" value="3D ARTIST"> 3D ARTIST</label>
						<label><input type="checkbox" value="AUDIO"> AUDIO</label>
						<label><input type="checkbox" value="MARKETING"> MARKETING</label>
						<label><input type="checkbox" value="GAME DESIGNER"> GAME DESIGNER</label>
						<label><input type="checkbox" value="GAME TESTER"> GAME TESTER</label>
						<label><input type="checkbox" value="TRANSLATOR"> TRANSLATOR</label>
						<label><input type="checkbox" value="VOICE ACTOR"> VOICE ACTOR</label>
					</div>
				</div>
			</th>
			<th>NOTES</th>
			<?php if ($_SESSION["USERTYPE"] == "Admin")
				echo '<th>ACTIONS</th>';
			?>
		</tr>

		<?php if ($allSelectedContacts && $allSelectedContactsStatus) {
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

				if ($state && $color) { ?>
					<tr>
						<?php if ($_SESSION["USERTYPE"] == "Admin")
							echo '<td class="removeTableStyle">
								<div class="centerStyle">
									<input class="checkBox" type="checkbox" name="contacts[]" value="' . $contact["ID"] . ',' . $stateID . '">
								</div>
							</td>';
						?>
						<td class="basicTableBoxStyle" style="background-color:<?php echo $color; ?>">
							<b>
								<?php echo $state; ?>
							</b>
						</td>
						<td class="basicTableBoxStyle">
							<?php echo $contact["ENTERPRISE"]; ?>
							<i>
								<?php echo "(" . $contact["COUNTRY"] . ")"; ?>
							</i>
							<br>
							<?php echo $contact["PERSON"]; ?>
						</td>
						<td class="basicTableBoxStyle">
							<div class="scrollStyle">
								<b>EMAIL</b><br>
								<a target="_blank" href="mailto:" <?php echo $contact["EMAIL"]; ?>>
									<?php echo $contact["EMAIL"]; ?>
								</a>
								<br>
								<b>PHONE</b><br>
								<?php echo $contact["PHONE"]; ?>
								<br>
								<b>WEBSITE</b><br>
								<a target="_blank" href='<?php echo $contact["WEB"]; ?>'>
									<?php echo parse_url($contact["WEB"], PHP_URL_HOST); ?>
								</a>
								<br>
								<b>OTHER</b>
								<ul>
									<?php
									$input = $contact["OTHER_MEDIA"];
									$lines = explode("\n", $input);

									foreach ($lines as $line) {
										$parts = explode("@", $line);

										if (count($parts) == 2) {
											$platform = strtolower(trim($parts[0]));
											$platform = substr($platform, 0, -1);
											$username = trim($parts[1]);

											$url = "https://www." . $platform . ".com/" . $username;

											echo '<li><a target="_blank" href="' . $url . '">' . $line . '</a></li>';
										}
									}
									?>
								</ul>
							</div>
						</td>
						<td class="basicTableBoxStyle">
							<?php echo $contact["CSV"]; ?>
						</td>
						<td class="basicTableBoxStyle">
							<div class="scrollStyle">
								<?php echo $contact["RECORD"]; ?>
							</div>
						</td>
						<?php if ($_SESSION["USERTYPE"] == "Admin")
							echo '<td class="basicTableBoxStyle">
							<i class="fa fa-edit icon editButton" aria-hidden="true" data-href="./edit/controller/cModifyContact.php?ID=' . $contact["ID"] . '&STATEID=' . $stateID . '"></i>
							<i class="fa fa-trash-o icon deleteButton" aria-hidden="true" data-href="./edit/controller/cDeleteContact.php?ID=' . $contact["ID"] . '&STATEID=' . $stateID . '"></i>
							</td>';
						?>
					</tr>
				<?php }
			}
		} ?>
		<tr style="background-color: #f2f2f2;">
			<?php if ($_SESSION["USERTYPE"] == "Admin")
				echo '<td></td>';
			?>
			<td colspan="6">
				<i class="fa fa-chevron-left icon" id="prevPageButton"></i>
				<i class="fa fa-chevron-right icon" id="nextPageButton"></i>
				<span>Page </span>
				<input type="number" id="currentPageInput" min="1" max="" value="">
				<span id="totalOfPages"> of </span>
			</td>
		</tr>
	</table>

	<script src="script.js"></script>
</body>

</html>