<?php
session_start();
include_once 'navbar.php';
include_once 'dbconnect.php';

if(!isset($_SESSION['userSession']))
{
 header("Location: index.php");
}

$query = "SELECT item_id, image, itemName, price, description ";
$query .= "FROM items ";
$result = mysqli_query($MySQLi_CON, $query);

//test if the query failed
if (!$result){
	die("Database query failed.");
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">


<html lang="en">
<head>
		
<title>Shop</title>
</head>

<body>
  <ul>
    
    <p  style='display: block; padding-top: 50px;'></p>
    
	<!-- Add search bar here -->
	<h2 style="display: block; padding-top: 40px;"> Search by product name or product id </h2>
    <input type='text' id='search_bar'>
        <input type='button' id='search_string' value='Search' onClick="search()">	
		
		<p id ="finalResult"></p>
		<div id="debug"> </div>
		
    <!-- return items table entries -->
	<div id="display">
    <?php while($row = mysqli_fetch_assoc($result)) : ?>
    

	<div id="" class='container'>
		<h1><?php echo $row["itemName"]; ?></h1>
		<div class="row">
			<div id="firstCol" class="col-lg-2 col-md-4 col-sm-4">
			<p><a href="item-page.php?item_id=<?php echo $row["item_id"]; ?>">
					<img class="img-responsive" width="150" height="150"  src=<?php echo $row["image"];?> 
				id='image'align="top" style="float:left"/></a></p>
			</div>
		
			<div id="secondCol" class="col-lg-8 col-md-6 col-sm-5">
				<p id='price' style="font-size:20px; float:middle;">$<?php echo $row["price"]; ?></p>
				<p id='desc' style="font-size:20px; float:middle;"><?php echo $row["description"]; ?></p>
			</div>
		
			<div id="thirdCol" class="col-lg-2 col-md-2 col-sm-3">
				<a class='btn btn-lg btn-primary' href='#' role='button' onClick='addToCart(<?php echo $row["item_id"]; ?>)'>Add To Cart</a>
			</div>
		</div>
	</div>
    <hr>
    <?php endwhile; ?>
    <?php mysqli_free_result($result); ?>
	</div>
  </ul>
  
  <div id="footer"><?php include_once 'footer.php'; ?></div>
  
<!-- the passed array is what we use in shopList.js for using the data from array $row-->
<script src="shopList.js"></script>
<script src="itemSearch.js"></script>
</body>

</html>