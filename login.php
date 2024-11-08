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
	<meta name="description">
	<link rel="shortcut icon" href="favicon.ico">
	<script defer src="assets/plugins/fontawesome/js/all.min.js"></script>
	<link id="theme-style" rel="stylesheet" href="assets/css/portal.css">
</head>

<body class="app app-login p-0">
	<div class="row g-0 app-auth-wrapper">
		<div class="col-12 col-md-7 col-lg-6 auth-main-col text-center p-5">
			<div class="d-flex flex-column align-content-end">
				<div class="app-auth-body mx-auto">
					<div class="app-auth-branding mb-4"><a class="app-logo" href="index.html"><img class="logo-icon me-2" src="assets/images/app-logo.jpg" alt="logo"></a></div>
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
											<a href="reset-password.html"></a>
										</div>
									</div>
								</div>
							</div>
							<div class="text-center">
								<button type="submit" class="btn app-btn-primary w-100 theme-btn mx-auto">Log In</button>
							</div>
						</form>
						<div class="auth-option text-center pt-5"><a class="text-link" href="signup.html"></a>.</div>
					</div>
				</div>
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
						<h5 class="mb-3 overlay-title">KasKlinik - Andina Karawang</h5>
						<div>Aplikasi kami dirancang untuk memudahkan Anda dalam mengelola kas klinik. Dengan aplikasi ini, Anda dapat mencatat transaksi, mengelola akun, dan mengakses informasi penting dengan lebih efisien.
							<a href="https://themes.3rdwavemedia.com/bootstrap-templates/admin-dashboard/portal-free-bootstrap-admin-dashboard-template-for-developers/">here</a>.
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>

</html>