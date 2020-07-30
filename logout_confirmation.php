<?php
	session_start();

	$loggedOut = False;

	$username = $_SESSION["username"];

	if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
		session_destroy();
		$loggedOut = True;
	}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Stock Watch</title>

	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link href= "styles.css" rel="stylesheet">

</head>
<body>
	<nav class="navbar navbar-expand-md bg-light navbar-light">

		<a class="navbar-brand" href="home.php"><h3><strong>Stock Watch</strong></h3></a>

		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
			<span class="navbar-toggler-icon"></span>
		</button>

		<div class="collapse navbar-collapse" id="collapsibleNavbar">
			<ul class="navbar-nav mr-auto">
				<li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
				<li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
				<li class="nav-item"><a class="nav-link" href="portfolio.php">Portfolio</a></li>
			</ul>
			<ul class="navbar-nav ml-auto">
				<li class="nav-item"><a class="nav-link" href="login.php">Login/Logout</a></li>
				<li class="nav-item"><a class="nav-link" href="signup.php">Sign Up</a></li>
				<li class="nav-item"><a class="nav-link" href="profile.php">My Profile</a></li>
				<button class="btn btn-danger navbar-btn btn-dark" id="darkmode">Dark Mode</button>
			</ul>
		</div>
	</nav>

	<div class="container-fluid container container_custom">

		<h2>Logout Confirmation</h2>

		<?php if ($loggedOut) : ?>
			<div class="text-success">
				<span class="font-italic"><?php echo $username; ?></span> was successfully logged out.
			</div>
		<?php endif; ?>

	</div>


	<script src="https://code.jquery.com/jquery-3.3.1.min.js" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

	<script src=darkmode.js></script>

	<script>
		<?php if($_SESSION['darkmode'] == true) : ?>
				acd()
		<?php endif; ?>
	</script>

</body>
</html>
