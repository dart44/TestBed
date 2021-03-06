<?php 
session_start();
include_once 'dbconnect.php';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">

<html lang="en">



<head>	
<title>Admin Order Search Page</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Welcome - <?php echo $userRow['email']; ?></title>

	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/footer.css">
	<link rel="stylesheet" type="text/css" href="css/admin.css">
	<!-- <link rel="stylesheet" href="css/style.css" type="text/css" /> -->

<?php include ('navbar.php'); ?>
</head>

<body>

<p style="display: block; padding-top: 50px;"></p>

	<div id="adminContainer" class='container'>
		<h2>  </h2>
			<div>
				<form id="myForm" method="post">
					<fieldset>
						<h2>Search by user_id, email, or username</h2>
						<br>
						<input type="radio" name="radioGroup" id="userid" 
						value="0" checked="checked"/>
						<label for="userid" >user_id</label>
					
						<input type="radio" name="radioGroup"
						id="email" value="1"/>
						<label for="email">email</label>
					
						<input type="radio"name="radioGroup"
						id="username" value="2"/>
						<label for="username">username</label>
					
						<input type="radio"name="radioGroup"
						id="ordernumber" value="3"/>
						<label for="ordernumber">ordernumber</label>
			
						<br><br>
						<?php 
						if(isset($_POST['search-bar']))
						{?>
							<input type='text' name="search-bar" id='search-bar' value=<?php echo $_POST['search-bar'];?>>
							<?php }
							else
							{?>
							<input type='text' name="search-bar" id='search-bar'>
						<?php };?>
						<input type="submit" name="search-orders" 
						class='btn btn-default' id="search-orders" value='Search'>

						<div id="orderButton">
							<input type="button" class="btn btn-default" onclick="location.href='admin-home.php';" 
							value="Return Home"/>	
						</div>
					</fieldset>
					<div id="debug"> </div>
				</form>
			</div>
		<p id ="finalResult">

<?php
if(isset($_POST['search-orders']))
{
	if(isset($_POST['search-bar']))
	{
		if(isset($_POST['radioGroup']))
		{
		//	echo "<br><br><br><br>search completed: " . $_POST['radioGroup'] . " " . $_POST['search-bar'];
		}else{
			echo "invalid search for radio. value is " . $_POST['radioVal'];
		}
	}else{
		echo "invalid search for searchTerm";
	}

	$searchTerm = $MySQLi_CON->real_escape_string(trim($_POST['search-bar']));
	$radioVal = $MySQLi_CON->real_escape_string(trim($_POST['radioGroup']));
  
  switch ($radioVal) {
    case 0:
      $query =
      "SELECT users.user_id, users.username, users.email, 
      orders.orderNumber, orders.orderDate, orders.shippedDate, 
      orders.status, orders.comments
      FROM orders 
      LEFT JOIN users
      ON orders.user_id = users.user_id
      WHERE users.user_id = '$searchTerm' 
      ";
      break;
    case 1:
    	$query =
      "SELECT users.user_id, users.username, users.email, 
      orders.orderNumber, orders.orderDate, orders.shippedDate, 
      orders.status, orders.comments
      FROM orders 
      LEFT JOIN users
      ON orders.user_id = users.user_id
      WHERE users.email LIKE '%$searchTerm%'
      ";
      break;
    case 2:
    	$query =
      "SELECT users.user_id, users.username, users.email, 
      orders.orderNumber, orders.orderDate, orders.shippedDate, 
      orders.status, orders.comments
      FROM orders 
      LEFT JOIN users
      ON orders.user_id = users.user_id
      WHERE users.username = '$searchTerm'
      ";
      break;
    case 3:
      $query =
      "SELECT users.user_id, users.username, users.email, 
      orders.orderNumber, orders.orderDate, orders.shippedDate, 
      orders.status, orders.comments
      FROM orders 
      LEFT JOIN users
      ON orders.user_id = users.user_id
      WHERE orders.orderNumber = '$searchTerm'
      ";
      break;
    default:
    	$query =
      "SELECT users.user_id, users.username, users.email, 
      orders.orderNumber, orders.orderDate, orders.shippedDate, 
      orders.status, orders.comments
      FROM orders 
      LEFT JOIN users
      ON orders.user_id = users.user_id
      WHERE users.user_id = '%$searchTerm%' 
      ";
      break;
  }

	$result=mysqli_query($MySQLi_CON, $query);
	//test if the query failed
	if (!$result){
		die("Database query failed.");
	}else{
		?>
		<br>
	
	<table id='equalDivide' width='100%'>
		<tr class="row">
			<th>Order No. </th> 
			<th>User Id </th>
			<th>Email </th>
			<th>Order Date </th>
			<th>Shipped Date </th>
			<th>Status </th>
			<th>Comments</th>
			<th>Modify </th>
		</tr>
	
		<?php while($row = mysqli_fetch_assoc($result)) :?>
		<tr class="row" id="row<?php echo $row["orderNumber"]; ?>">
			<td>
				<?php echo $row["orderNumber"]; ?>
			</td>
			<td id="user_id_val<?php echo $row["orderNumber"]; ?>">
				<?php echo $row["user_id"] ; ?>
			</td>
			<td id="email_val<?php echo $row["orderNumber"]; ?>">
				<?php echo $row["email"] ; ?>
			</td>
			<td id="orderDate_val<?php echo $row["orderNumber"]; ?>" >
				<?php echo $row["orderDate"] ; ?>
			</td>
			<td id="shippedDate_val<?php echo $row["orderNumber"]; ?>">
				<?php echo $row["shippedDate"] ; ?>
			</td>
			
			<td id="status_val<?php echo $row["orderNumber"]; ?>">
				<?php echo $row["status"] ; ?>
			</td>
			
			<td id="comments_val<?php echo $row["orderNumber"]; ?>" >
				<?php echo $row["comments"] ; ?>
			</td>
			
			<td>
				<input 
					class="hidden-sm hidden-xs" 
					type='button' 
					class="edit_button" 
					id="edit_button<?php echo $row["orderNumber"]; ?>" 
					value="edit" 
					onclick="edit_row('<?php echo $row["orderNumber"]; ?>');"
				>       
				<input 
					class="hidden-sm hidden-xs" 
					type='button' 
					class="save_button" 
					id="save_button<?php echo $row["orderNumber"]; ?>" 
					value="save" 
					onclick="save_row('<?php echo $row["orderNumber"]; ?>');"
				>       
				<input 
					class="hidden-sm hidden-xs" 
					type='button' 
					class="delete_button" 
					id="delete_button<?php echo $row["orderNumber"];?>" 
					value="delete" 
					onclick="delete_row('<?php echo $row["orderNumber"];?>');"
				>
			</td>
		</tr>
		<?php endwhile; ?>
		
	</table> 
	
	<p  style='display: block; padding-bottom: 10px;'></p>
	<?php }; ?>
<?php };//end if #1 
?>
		</p>
	</div>
	<p style="display: block; padding-top: 1px;"></p>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="js/vendor/jquery-3.1.1.min.js"><\/script>')</script>
<script src="js/vendor/bootstrap.min.js"></script>

<script src="js/adminsearch.js"></script>
<script src="js/modify_records.js"></script>
<hr>
<div id="footer"><?php include_once 'footer.php'; ?></div>
</body>
<?php $MySQLi_CON->close();?>
</html>