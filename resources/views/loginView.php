<!DOCTYPE html>
<html lang="en">

<head>
	<?php require_once COMMON_RESOURCES . "/headers.php"; ?>
	<title>Login</title>
	<!-- My style -->
	<link rel="stylesheet" href="../css/login.css">
	<!-- My Script -->
	<script src="<?php echo $domain_name ?>/js/login.js"></script>
</head>

<body>
<div class="container">
	<div class="col-md-4 col-md-offset-4">
		<form method="post" id="formLogin">
			<h3>Enter Account Details</h3>
			<div id="formGroupUsername" class="form-group">
				<label for="inputUsername">Username</label>
				<input type="text" name="user" class="form-control" id="inputUsername" placeholder="Username">
			</div>
			<div id="formGroupPassword" class="form-group">
				<label for="inputPassword">Password</label>
				<input type="password" name="pass" class="form-control" id="inputPassword" placeholder="Password">
			</div>
			<div hidden id="errorMsg" class="error">Login details are incorrect</div>
			<button class="btn btn-default" type="submit" id="btnLogin">Sign in</button>
			or <a id="register" href="<?php echo $register_page ?>">Register</a>
		</form>
	</div>
</div>
</body>

</html>

