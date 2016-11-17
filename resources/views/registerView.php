<!DOCTYPE html>
<html lang="en">

<head>
	<?php require_once COMMON_RESOURCES . "/headers.php"; ?>
	<title>Registration</title>
	<!-- My style -->
	<link rel="stylesheet" href="../css/register.css">
	<!-- My Script -->
	<script src="../js/register.js"></script>
</head>
<body>
<div class="container">
	<div class="col-md-4 col-md-offset-4">
		<form id="formRegister" method="post">
			<h3>Enter Account Details</h3>
			<div id="formGroupUsername" class="form-group">
				<label for="inputUsername">Username</label>
				<input type="text" name="user" class="form-control" id="inputUsername" placeholder="Username">
			</div>
			<div id="formGroupPassword" class="form-group">
				<label for="inputPassword">Password</label>
				<input type="password" name="pass" class="form-control" id="inputPassword" placeholder="Password">
			</div>
			<div id="formGroupPasswordVerify" class="form-group">
				<label for="inputPassword">Please re-enter password</label>
				<input type="password" name="pass" class="form-control" id="inputPasswordVerify" placeholder="Password">
			</div>
			<div class="checkbox">
				<label><input id="formLecturer" type="checkbox" value="">Lecturer</label>
			</div>
			<div class="error"></div>
			<button type="submit" class="btn btn-default">Submit</button>
			Already registered? <a href="<?php echo $login_page ?>">Sign in.</a>
		</form>
	</div>
</div>
</body>
</html>




