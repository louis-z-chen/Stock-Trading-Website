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

$user_id = $_SESSION["id"];
$isUpdated = false;

if (!isset($_POST) || empty($_POST) ) {
	//echo "no input";
} 
else if (!isset($_POST["quantity"]) || empty($_POST["quantity"]) ) {
	//echo "no quantity";
} 
else{
	$input_quantity = $_POST["quantity"];
	$input_datetime = $_POST["hidden_datetime"];
	$input_symbol = $_POST["hidden_symbol"];
	$input_company_name = $_POST["hidden_company"];
	$input_price = $_POST["hidden_price"];
	$input_currency_type = $_POST["hidden_type"];
	$input_user_id = $_POST["hidden_id"];

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

	// Set character set 
	$mysqli->set_charset('utf8');

	$sql_company = "SELECT * FROM currency where symbol = ?";
	$statement_company = $mysqli->prepare($sql_company);
	$statement_company->bind_param('s',$input_symbol);
	$executed = $statement_company->execute();
	if(!$executed) {
		echo $mysqli->error;
	}
	$result = $statement_company->get_result();
    $row = $result -> fetch_assoc(); 

	$companyExists = false;
	if($statement_company->affected_rows > 0) {
		$companyExists = true;
	}

	$statement_company->close();

	if(!$companyExists){
		$sql_prepared = "INSERT INTO currency (symbol, name, type) 
		VALUES (?,?,?);";
		$statement = $mysqli->prepare($sql_prepared);
		$statement->bind_param("ssi", $input_symbol, $input_company_name, $input_currency_type);
		$executed = $statement->execute();

		if(!$executed) {
			echo $mysqli->error;
		}
		$statement->close();	
	}

	$sql_getid = "SELECT * FROM currency where symbol = ?";
	$statement_getid = $mysqli->prepare($sql_getid);
	$statement_getid->bind_param('s',$input_symbol);
	$executed = $statement_getid->execute();

	if(!$executed) {
		echo $mysqli->error;
	}

	$result = $statement_getid->get_result();
	$row = $result -> fetch_assoc();
	$input_company_id = $row["id"];
    $statement_getid->close();

    //var_dump($input_user_id);

    $sql_prepared = "INSERT INTO transactions (datetime,currency_id,user_id,quantity,price) 
	VALUES (?,?,?,?,?);";

	$statement = $mysqli->prepare($sql_prepared);
	$statement->bind_param("siiis", $input_datetime, $input_company_id, $input_user_id, $input_quantity, $input_price);
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
				<li class="nav-item"><a class="nav-link active" href="dashboard.php">Dashboard</a></li>
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
				<h2 class="col-12 mt-4">Dashboard</h2>
			</div> <!-- .row -->
		</div> <!-- .container-fluid -->

		<div class="container-fluid">

			<div>
				<?php if ($isUpdated) : ?>
					<div class="text-success">
						<?php echo $input_quantity;?> shares of <span class="font-italic"><?php echo $input_company_name; ?></span> were successfully bought.
					</div>
				<?php endif; ?>
			</div>

			<div class="row">

				<div class="col-12">
					<table class="table table-hover table-responsive mt-4 dashboard" id="stock-table">
						<thead>
							<tr>
								<th>Symbol</th>
								<th>Company</th>
								<th>Price</th>
								<th>Change</th>
								<th>% Change</th>
								<th>52 Week High</th>
								<th>52 Week Low</th>
								<th>Primary Exchange</th>
								<th>Buy Stocks</th>
							</tr>
						</thead>
						<tbody>
							<tr>

							</tr>
						</tbody>
					</table>
				</div>

			</div>

		</div>
	</div>

	<script src="https://code.jquery.com/jquery-3.3.1.min.js" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

	<!-- external js -->
	<script src=dashboard.js></script>
	<script src=darkmode.js></script>

	<script>
		<?php if($_SESSION['darkmode'] == true) : ?>
				acd()
		<?php endif; ?>
	</script>

<!-- 	<script>

		function checkQuantity(){
			event.preventDefault();

			console.log("hi");


			let quantity = document.forms.quantity_form.quantity;
			console.log(quantity);

		}

	</script> -->

</body>
</html>