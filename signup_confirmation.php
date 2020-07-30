<?php

session_start();

$isUpdated = false;

if ( !isset($_POST['username']) || empty($_POST['username']) || !isset($_POST['password']) || empty($_POST['password']) || empty($_POST['repassword']) || !isset($_POST['repassword']) ) {
	$error = "Please fill out all required fields.";
} 

else {
	$username = $_POST['username'];
	$password = $_POST['password'];
	$repassword = $_POST['repassword'];

	$passwordSame = False;
	if($password == $repassword){
		$passwordSame = True;
	}

	if($passwordSame == True){
		$hashpass = hash("md5", $password);
	}

	$host = "303.itpwebdev.com";
	$user = "chenloui_db_user";
	$cpanelpassword = "uscitp12345!";
	$db = "chenloui_final_project_stocks_db";

	// DB Connection
	$mysqli = new mysqli($host, $user, $cpanelpassword, $db);
	if ( $mysqli->connect_errno ) {
		echo $mysqli->connect_error;
		exit();
	}

	$sql_username = "SELECT * FROM user where user = ?";
	
	$statement_username = $mysqli->prepare($sql_username);


	$statement_username->bind_param('s',$username);


	$executed = $statement_username->execute();

	if(!$executed) {
		echo $mysqli->error;
	}

	$result = $statement_username->get_result();
    $row = $result -> fetch_assoc(); 

	$usernameExists = false;
	if($result->num_rows > 0) {
		$usernameExists = true;
	}

	$statement_username->close();

	//Put all this in an if statement
	//prepared statement to create new users
	if($passwordSame == True && $usernameExists == False){
		$sql_prepared = "INSERT INTO user (user,password) 
		VALUES (?,?);";

		$statement = $mysqli->prepare($sql_prepared);

		$statement->bind_param("ss", $username, $hashpass);

		$executed = $statement->execute();

		if(!$executed) {
			echo $mysqli->error;
		}

		if( $statement->affected_rows == 1 ) {
			$isUpdated = true;
		}
		$statement->close();

		$mysqli->close();
	}
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

		<h2>Sign Up Confirmation</h2>

		<?php if ( isset($error) && !empty($error) ) : ?>
			<div class="text-danger">
				<?php echo $error; ?>
			</div>
		<?php endif; ?>

		<?php if ( $passwordSame == False ) : ?>
			<div class="text-danger">
				Passwords did not match.
			</div>
		<?php endif; ?>

		<?php if ( $usernameExists == True ) : ?>
			<div class="text-danger">
				Username is alreadly in use.
			</div>
		<?php endif; ?>

		<?php if ($isUpdated) : ?>
			<div class="text-success">
				<span class="font-italic"><?php echo $_POST['username']; ?></span> was successfully added.
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