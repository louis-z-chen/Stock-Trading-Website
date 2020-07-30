<?php

	session_start();

	$isLoggedin = false;
	// Check if the user is already logged in, 
	if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
		$isLoggedin = true;
	}
	else{
		header("Location: login.php");
	}

	$user_id = $_SESSION["id"];
	$isUpdated = false;

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
	$mysqli->set_charset('utf8');

	$isDeleted = false;
	if ( isset($_GET['id']) || !empty($_GET['id']) ) {
		$sql = "DELETE FROM transactions WHERE id = " . $_GET["id"] . ";";
		$results = $mysqli->query($sql);
		if(!$results) {
			echo $mysqli->error;
			exit();
		}
		if ($mysqli->affected_rows == 1) {
			$isDeleted = true;
		}
	}

	$sql = "SELECT transactions.id, transactions.datetime, currency.symbol, currency.name, currency.type, transactions.quantity, transactions.price
	FROM transactions
		LEFT JOIN user
			ON transactions.user_id = user.id
		LEFT JOIN currency
			ON transactions.currency_id = currency.id
	ORDER BY transactions.datetime DESC";

	$results = $mysqli->query($sql);
	if(!$results) {
		echo $mysqli->error;
		exit();
	}

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
				<li class="nav-item"><a class="nav-link active" href="portfolio.php">Portfolio</a></li>
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
				<h2 class="col-12 mt-4">Portfolio</h2>
			</div> <!-- .row -->
		</div> <!-- .container-fluid -->

			<?php if ( $isDeleted ) :?>
						<div class="text-success">Stocks were successfully sold</div>
					<?php endif; ?>

			<div class="container-fluid">

			<div>
				Total Profits: $<span id="total_profits">0.00</span>
			</div>

			<div class="row">
				<div class="col-12">
					<table class="table table-hover table-responsive mt-4 dashboard" id="results-table">
						<thead>
							<tr>
								<th>Date/Time Purchased</th>
								<th>Symbol</th>
								<th>Company</th>
								<th>Type</th>
								<th>Quantity</th>
								<th>Bought Price</th>
								<th>Current Price</th>
								<th>Change</th>
								<th>% Change</th>
								<th>Profit</th>
								<th> </th>
							</tr>
						</thead>
						<tbody>

							<?php while($row = $results->fetch_assoc() ) : ?>

								<tr id="transaction_<?php echo $row["id"]; ?>" class="transaction" data-symbol="<?php echo $row["symbol"] ?>" data-price="<?php echo $row["price"] ?>" data-quantity="<?php echo $row["quantity"] ?>">
									<td> <?php echo $row["datetime"] ?> </td>
									<td> <?php echo $row["symbol"] ?> </td>
									<td> <?php echo $row["name"] ?> </td>
									<td> 
										<?php 
											$temp_type = $row["type"];

											if($temp_type == 0){
												echo "Stock";
											}
											else if($temp_type == 1){
												echo "Cryptocurrency";
											}
										?> 
									</td>
									<td class="quantity" > <?php echo $row["quantity"] ?> </td>
									<td class="price"> <?php echo $row["price"] ?> </td>
									<td class="current_price"> </td>
									<td class="change"> </td>
									<td class="pchange"> </td>
									<td class="profit"> </td>
									<td>
										<a href="portfolio.php?id=<?php echo $row["id"]; ?>" class="btn btn-outline-danger delete-btn">
											Sell
										</a>
									</td>
								</tr>

							<?php endwhile; ?>
   
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
	<script src="portfolio.js" crossorigin="anonymous"></script>
	<script src=darkmode.js></script>

	<script>
		<?php if($_SESSION['darkmode'] == true) : ?>
				acd()
		<?php endif; ?>
	</script>

</body>
</html>