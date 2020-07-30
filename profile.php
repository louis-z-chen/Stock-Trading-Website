<?php

	session_start();

	$isLoggedin = false;
	// Check if the user is already logged in, 
	if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
		$isLoggedin = true;
		$user_id = $_SESSION["id"];
	}
	else{
		header("location: login.php");
	}

	$host = "303.itpwebdev.com";
	$user = "chenloui_db_user";
	$cpanelpassword = "uscitp12345!";
	$db = "chenloui_final_project_stocks_db";

	$mysqli = new mysqli($host, $user, $cpanelpassword, $db);
	if ( $mysqli->connect_errno ) {
		echo $mysqli->connect_error;
		exit();
	}
	$mysqli->set_charset('utf8');

	$isUpdated = false;
	$nothingUpdated = false;
	if ( isset($_POST['user']) || !empty($_POST['user']) ) {

		$sql_prepared = "UPDATE user SET user = ?
			WHERE id = ?;";

		$statement = $mysqli->prepare($sql_prepared);
		$statement->bind_param("si", $_POST['user'], $user_id);
		$executed = $statement->execute();

		if(!$executed) {
			echo $mysqli->error;
		}

		if( $statement->affected_rows == 1 ) {
			$isUpdated = true;
			$_SESSION["username"] = $_POST['user'];
		}
		else{
			$nothingUpdated = true;
		}

		$statement->close();
	}



	$sql = "SELECT user FROM user
			WHERE id = " . $_SESSION['id'] . ";";

	$results = $mysqli->query($sql);
	if ( !$results ) {
		echo $mysqli->error;
		exit();
	}
	$row = $results->fetch_assoc();
	$mysqli->close();

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
				<li class="nav-item"><a class="nav-link active" href="profile.php">My Profile</a></li>
				<button class="btn btn-danger navbar-btn btn-dark" id="darkmode">Dark Mode</button>
			</ul>
		</div>
	</nav>

	<div class="container-fluid container container_custom">
		
		<div class="container-fluid">
			<div class="row">
				<h2 class="col-12 mt-4">My Profile</h2>
			</div> <!-- .row -->
		</div> <!-- .container-fluid -->

		<?php if ($isUpdated) : ?>
			<div class="text-success">
				<span class="font-italic"> Profile </span> was successfully edited.
			</div>
		<?php endif; ?>

		<?php if ($nothingUpdated) : ?>
			<div class="text-warning">
				<span class="font-italic"> Profile </span> was not edited.
			</div>
		<?php endif; ?>

		<div>
			<b>Username:</b> <?php echo $row['user']; ?>
		</div>

		<div class="row mt-4 mb-4">
			<div class="col-12">
				<a href="edit.php?user_id=<?php echo $_SESSION['id']?>" class="btn btn-success">Change Username</a>
			</div>
		</div>

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