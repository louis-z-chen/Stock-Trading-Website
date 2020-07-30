<?php

	session_start();

	$isLoggedin = false;
	// Check if the user is already logged in, 
	if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
		$isLoggedin = true;
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

	<script>
    	let user_id = "<?php echo"$user_id"?>"; 
	</script>

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
		
		<div class="container-fluid">
			<div class="row">
				<h2 class="col-12 mt-4">Change Username</h2>
			</div> <!-- .row -->
		</div> <!-- .container-fluid -->

		<div class="container">

			<?php if ( isset($error) && !empty($error) ) : ?>

				<div class="text-danger font-italic">
					<?php echo $error; ?>
				</div>

			<?php else : ?>

			<form action="profile.php" method="POST" id="edit_form">

				<div class="form-group row">
					<label for="user" class="col-sm-3 col-form-label text-sm-right">Username: </label>
					<div class="col-sm-7">
						<input type="text" class="form-control" name="user" id="edit_field" value="<?php echo $row['user']?>">
					</div>
				</div> <!-- .form-group -->

				<div class="form-group row">
					<div class="col-lg-12">
						<button type="submit" class="btn btn-success">Submit</button>
					</div>
				</div> <!-- .form-group -->

			</form>

			<?php endif; ?>

		</div> <!-- .container -->
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

	<script>
		document.querySelector("#edit_form").onsubmit = function(event){

			let username = document.querySelector("#edit_field").value.trim();

			if(username <= 0){
				alert("Username must have atleast one character.")
				event.preventDefault();
			}
		}

	</script>

</body>
</html>