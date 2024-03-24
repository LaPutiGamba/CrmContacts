<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<title>Sign In</title>
	<link href="style.css" rel="stylesheet" type="text/css">
	<link href="https://fonts.googleapis.com/css2?family=Kanit&display=swap" rel="stylesheet">
	<link rel="icon" href="./info/images/favicon.png" type="image/x-icon">
</head>

<body class="body-login">
	<div class="login-center-wrap">
		<div class="login-wrap">
			<div class="login-html">
				<label for="tab-1" class="tab">Login</label>

				<div class="login-form" style="margin-top: 5vh;">
					<form method="post" action="./edit/controller/cSignIn.php">
						<div class="group">
							<label for="user" class="label">Username</label>
							<input id="user" type="text" class="input" name="USERNAME">
						</div>
						<div class="group">
							<label for="pass" class="label">Password</label>
							<input id="pass" type="password" class="input" data-type="password" name="PASSWORD" minlength="8">
						</div>
						<div class="group">
							<input type="submit" class="button" value="SIGN IN" style="margin-top: 7vh; color: white">
						</div>
					</form>
					<div class="hr"></div>
					<div class="foot-lnk">
					</div>
				</div>
			</div>
		</div>
	</div>
</body>

</html>