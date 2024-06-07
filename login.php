<?php
require './config/config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$username = $_POST['signin-username'];
	$password = $_POST['signin-password'];
	$remember = isset($_POST['RememberPassword']);

	$stmt = $conn->prepare("SELECT * FROM user WHERE username = :username");
	$stmt->bindParam(':username', $username);
	$stmt->execute();
	$user = $stmt->fetch(PDO::FETCH_ASSOC);

	if ($user && password_verify($password, $user['password'])) {
		$_SESSION['user_id'] = $user['id'];

		if ($remember) {
			setcookie('user_id', $user['id'], time() + (86400 * 30), "/");
		}

		header("Location: index.php");
		exit();
	} else {
		error_log("Invalid username or password");
	}
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<title>Login</title>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="Portal - Bootstrap 5 Admin Dashboard Template For Developers">
	<meta name="author" content="Xiaoying Riley at 3rd Wave Media">
	<link rel="shortcut icon" href="favicon.ico">
	<script defer src="assets/plugins/fontawesome/js/all.min.js"></script>
	<link id="theme-style" rel="stylesheet" href="assets/css/portal.css">
</head>

<body class="app app-login p-0">
	<div class="row g-0 app-auth-wrapper">
		<div class="col-12 col-md-7 col-lg-6 auth-main-col text-center p-5">
			<div class="d-flex flex-column align-content-end">
				<div class="app-auth-body mx-auto">
					<div class="app-auth-branding mb-4"><a class="app-logo" href="index.html"><img class="logo-icon me-2" src="assets/images/app-logo.svg" alt="logo"></a></div>
					<h2 class="auth-heading text-center mb-5">Log in to Portal</h2>
					<div class="auth-form-container text-start">
						<form class="auth-form login-form" method="POST">
							<div class="email mb-3">
								<label class="sr-only" for="signin-username">Username</label>
								<input id="signin-username" name="signin-username" type="text" class="form-control signin-username" placeholder="Username" required="required">
							</div>
							<div class="password mb-3">
								<label class="sr-only" for="signin-password">Password</label>
								<input id="signin-password" name="signin-password" type="password" class="form-control signin-password" placeholder="Password" required="required">
								<div class="extra mt-3 row justify-content-between">
									<div class="col-6">
										<div class="form-check">
											<input class="form-check-input" type="checkbox" value="" id="RememberPassword" name="RememberPassword">
											<label class="form-check-label" for="RememberPassword">
												Remember me
											</label>
										</div>
									</div>
									<div class="col-6">
										<div class="forgot-password text-end">
											<a href="reset-password.html">Forgot password?</a>
										</div>
									</div>
								</div>
							</div>
							<div class="text-center">
								<button type="submit" class="btn app-btn-primary w-100 theme-btn mx-auto">Log In</button>
							</div>
						</form>
						<div class="auth-option text-center pt-5">No Account? Sign up <a class="text-link" href="signup.html">here</a>.</div>
					</div>
				</div>
				<footer class="app-auth-footer">
					<div class="container text-center py-3">
						<small class="copyright">Designed with <span class="sr-only">love</span><i class="fas fa-heart" style="color: #fb866a;"></i> by <a class="app-link" href="http://themes.3rdwavemedia.com" target="_blank">Xiaoying Riley</a> for developers</small>
					</div>
				</footer>
			</div>
		</div>
		<div class="col-12 col-md-5 col-lg-6 h-100 auth-background-col">
			<div class="auth-background-holder">
			</div>
			<div class="1"></div>
			<div class="auth-background-overlay p-3 p-lg-5">
				<div class="d-flex flex-column align-content-end h-100">
					<div class="h-100"></div>
					<div class="overlay-content p-3 p-lg-4 rounded">
						<h5 class="mb-3 overlay-title">Explore Portal Admin Template</h5>
						<div>Portal is a free Bootstrap 5 admin dashboard template. You can download and view the template license
							<a href="https://themes.3rdwavemedia.com/bootstrap-templates/admin-dashboard/portal-free-bootstrap-admin-dashboard-template-for-developers/">here</a>.
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>

</html>