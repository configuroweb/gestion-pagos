<!DOCTYPE html>
<html lang="en">
<?php
session_start();
include('./db_connect.php');
ob_start();
// if(!isset($_SESSION['system'])){
$system = $conn->query("SELECT * FROM system_settings limit 1")->fetch_array();
foreach ($system as $k => $v) {
	$_SESSION['system'][$k] = $v;
}
// }
ob_end_flush();
?>

<head>
	<meta charset="utf-8">
	<meta content="width=device-width, initial-scale=1.0" name="viewport">
	<link rel="icon" type="image/x-icon" href="assets/uploads/favicon.png">


	<?php include('./header.php');
	include('./footer.php'); ?>
	<?php
	if (isset($_SESSION['login_id']))
		header("location:index.php?page=payments");

	?>

</head>
<style>
	body {
		width: 100%;
		height: calc(100%);
		position: fixed;
		top: 0;
		left: 0
			/*background: #007bff;*/
	}

	main#main {
		width: 100%;
		height: calc(100%);
		display: flex;
		align-items: center;
		background-image: url(assets/uploads/background.jpg);
		background-size: cover;
	}
</style>

<body class="bg-dark">


	<main id="main">

		<div class="align-self-center w-100">

			<div id="login-center" align="center">
				<div class="card col-md-3 ">
					<div class="card-body">
						<h1 class="text-center mb-5"><img src="assets/uploads/logo.jpg" width="249px"></h1>

						<form id="login-form">
							<div class="form-group">
								<label for="username" class="control-label">Correo</label>
								<input type="text" id="username" name="username" class="form-control">
							</div>
							<div class="form-group">
								<label for="password" class="control-label">Contraseña</label>
								<input type="password" id="password" name="password" class="form-control">
							</div>
							<br>
							<center><button class="btn btn-primary">Ingresar</button></center>
							<br>
							<br>
							<br>
						</form>
					</div>
				</div>
			</div>
		</div>
	</main>

	<a href="#" class="back-to-top"><i class="icofont-simple-up"></i></a>


</body>

<script>
	$('#login-form').submit(function(e) {
		e.preventDefault()
		$('#login-form button[type="button"]').attr('disabled', true).html('Logging in...');
		if ($(this).find('.alert-danger').length > 0)
			$(this).find('.alert-danger').remove();
		$.ajax({
			url: 'ajax.php?action=login',
			method: 'POST',
			data: $(this).serialize(),
			error: err => {
				console.log(err)
				$('#login-form button[type="button"]').removeAttr('disabled').html('Login');

			},
			success: function(resp) {
				if (resp == 1) {
					location.href = 'index.php?page=payments';
				} else {
					$('#login-form').prepend('<div class="alert alert-danger">Username or password is incorrect.</div>')
					$('#login-form button[type="button"]').removeAttr('disabled').html('Login');
				}
			}
		})
	})
</script>


</html>