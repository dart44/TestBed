<?php
session_start();
include_once 'dbconnect.php';

$quantity = 0;
$user_id = $_SESSION['userSession'];

$check_cart = $MySQLi_CON->query(
    "SELECT orderNumber, status
    FROM orders
    WHERE user_id='$user_id' AND status='In Cart'"
  );
  
//this statement updates the quantity of a single item in the cart
if(isset($_POST['q']) && isset($_POST['iN'])){
$updatedQuantity = $MySQLi_CON->real_escape_string(trim($_POST['q']));
$item_id = $MySQLi_CON->real_escape_string(trim($_POST['iN']));
  
$row = mysqli_fetch_assoc($check_cart);
$orderNumber = $row['orderNumber'];
  
  //create a query for ALL items
  $totalOrderQuantity = $MySQLi_CON->query(
  "SELECT SUM(quantityOrdered) AS value_sum
  FROM orderDetails
  WHERE orderNumber='$orderNumber'"
  );
  
  //create query for only the ONE item 
  $singleOrderQuantity = $MySQLi_CON->query(
  "SELECT SUM(quantityOrdered) AS value_sum
  FROM orderdetails
  WHERE orderNumber='$orderNumber' AND item_id='$item_id'"
  );
  
  //subtract them and store them in a variable
  $total1 = mysqli_fetch_assoc($totalOrderQuantity);
  $total2 = mysqli_fetch_assoc($singleOrderQuantity);
  
  $var1 = $total1['value_sum'];
  $var2 = $total2['value_sum'];
  
  $newCart = ($var1 - $var2) + $updatedQuantity;
  //add the new quantity and use it to update the cart properly
$updateQuery = 
      "UPDATE orderDetails
      SET quantityOrdered = '$updatedQuantity'
      WHERE item_id = '$item_id' AND orderNumber = '$orderNumber'";
	  
	if($MySQLi_CON->query($updateQuery) === true){
		$_SESSION['cartCount'] = $newCart;
		header("Location: shoppingCart.php");
	}
}else{

$item_id = $_POST['item_id'];
$quantity = $_POST['quantity'];
$time = date("Y-m-d H:i:s");

$count=$check_cart->num_rows;

//if 'quantity' == 0, we know that we want to completely remove 
//an EXISTING item in an order
if($quantity==0){

  $row = mysqli_fetch_assoc($check_cart);
  $orderNumber = $row['orderNumber'];
  
    //create a query for ALL items
  $totalOrderQuantity = $MySQLi_CON->query(
  "SELECT SUM(quantityOrdered) AS value_sum
  FROM orderDetails
  WHERE orderNumber='$orderNumber'"
  );
  
  //create query for only the ONE item 
  $singleOrderQuantity = $MySQLi_CON->query(
  "SELECT SUM(quantityOrdered) AS value_sum
  FROM orderdetails
  WHERE orderNumber='$orderNumber' AND item_id='$item_id'"
  );
  
  //subtract them and store them in a variable
  $total1 = mysqli_fetch_assoc($totalOrderQuantity);
  $total2 = mysqli_fetch_assoc($singleOrderQuantity);
  
  $var1 = $total1['value_sum'];
  $var2 = $total2['value_sum'];
  $newCart = ($var1 - $var2) + $updatedQuantity;

    $deleteQuery = "DELETE FROM orderDetails
	WHERE orderNumber='$orderNumber' AND item_id='$item_id'";
	
	if($MySQLi_CON->query($deleteQuery) === TRUE){
		$_SESSION['cartCount'] = $newCart;
		header("Location: shoppingCart.php");
	}else{
	}//end deleteQuery end/else
	
}else if($count==0){
	// If the cart is empty, create a new order with status 'In Cart'

  $query = "INSERT INTO orders(user_id,orderDate,status)
  VALUES('$user_id','$time','In Cart')";
 
  $MySQLi_CON->query($query);
 
  // Fetch the new order's orderNumber
  $order = $MySQLi_CON->query(
    "SELECT *
    FROM orders
    WHERE user_id='$user_id' AND status='In Cart' AND orderDate='$time'"
  );
  
  $row = mysqli_fetch_assoc($order);
  $orderNumber = $row['orderNumber'];
  
  // add orderDetails for the added item to the newly created order
  $query = "INSERT INTO orderDetails(orderNumber,item_id,quantityOrdered)
  VALUES('$orderNumber','$item_id','$quantity')";
  
  $MySQLi_CON->query($query);
  mysqli_free_result($order);
}

// If the cart is not empty, check order with status 'In Cart' for duplicate item_id
else{
  // Check 'In Cart' order for duplicate item
  $row = mysqli_fetch_assoc($check_cart); // $check_cart only has one row, the 'In Cart' order
  $orderNumber = $row['orderNumber'];
  
  $check_duplicate = $MySQLi_CON->query(
    "SELECT *
    FROM orderDetails
    WHERE item_id = '$item_id' AND orderNumber = '$orderNumber'"
  );
  $count=$check_duplicate->num_rows;
  
  // If duplicate exists, increment quantityOrdered accordingly
  if(!$count==0){
    $row = mysqli_fetch_assoc($check_duplicate);
    $quantityInCart = $row['quantityOrdered'];

    $quantityTotal = $quantityInCart + $quantity;

    $query = 
      "UPDATE orderDetails
      SET quantityOrdered = '$quantityTotal'
      WHERE item_id = '$item_id' AND orderNumber = '$orderNumber'";

    $MySQLi_CON->query($query);
  }
	// If duplicate does not exist, insert new orderDetails
  else{
    $query = "INSERT INTO orderDetails(orderNumber,item_id,quantityOrdered)
    VALUES('$orderNumber','$item_id','$quantity')";
    
    $MySQLi_CON->query($query);
  }
  mysqli_free_result($check_duplicate);
}

mysqli_free_result($check_cart);
}//outer most if/very first if

$_SESSION['cartCount'] = $_SESSION['cartCount'] + $quantity;

$MySQLi_CON->close();

echo $_SESSION['cartCount'];
?>