<?php 
session_start();
$connect = mysqli_connect("localhost", "root1", "root1", "product_database");

if(isset($_POST["add_to_cart"]))
{
if(isset($_SESSION["shopping_cart"]))
{
	$item_array_id = array_column($_SESSION["shopping_cart"], "item_id");
	if(!in_array($_GET["id"], $item_array_id))
	{
		$count = count($_SESSION["shopping_cart"]);
		$item_array = array(
			'item_id'	=>	$_GET["id"],
			'item_name'	=>	$_POST["hidden_name"],
			'item_price'=>	$_POST["hidden_price"]
		);
		$_SESSION["shopping_cart"][$count] = $item_array;
	}
	else
	{
		echo '<script>alert("Item Already Added")</script>';
	}
} else {
		$item_array = array(
			'item_id'	=>	$_GET["id"],
			'item_name'	=>	$_POST["hidden_name"],
			'item_price'=>	$_POST["hidden_price"]
		);
		$_SESSION["shopping_cart"][0] = $item_array;
	}
}

if(isset($_GET["action"]))
{
	if($_GET["action"] == "delete")
	{
		foreach($_SESSION["shopping_cart"] as $keys => $values)
		{
			if($values["item_id"] == $_GET["id"])
			{
				unset($_SESSION["shopping_cart"][$keys]);
				echo '<script>alert("Item Removed")</script>';
				echo '<script>window.location="index.php"</script>';
			}
		}
	}
}

?>
<!DOCTYPE html>
<html>
	<head>
		<title>Vishal task</title>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
		<meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>


	</head>
	<body>
		<br />
		<div class="container">
			<br />
			<br />
			<br />
			<h3 align="center">Product List</h3><br />
			<br /><br />
			<?php
				$query = "SELECT * FROM tbl_product ORDER BY id ASC";
				$result = mysqli_query($connect, $query);
				if(mysqli_num_rows($result) > 0)
				{
					while($row = mysqli_fetch_array($result))
					{
				?>

			<div class="col-md-4">
				<form method="post" action="index.php?action=add&id=<?php echo $row["id"]; ?>">
					<div style="border:1px solid #333; border-radius:5px; " align="center">
						<img src="images/<?php echo $row["image"]; ?>" class="img-responsive" /> <br />

						<h4 class="text-info"><?php echo $row["product_name"]; ?></h4>

						<h4 class="text-danger">$ <?php echo $row["price"]; ?></h4>

						<input type="text" name="quantity" value="1" class="form-control" />

						<input type="hidden" name="hidden_name" value="<?php echo $row["product_name"]; ?>" />

						<input type="hidden" name="hidden_price" value="<?php echo $row["price"]; ?>" />
                        <label>Description</label>
						<input type="text" name="hidden_description" value="<?php echo $row["description"]; ?>" />

						<input type="submit" name="add_to_cart" style="margin-top:5px;" class="btn btn-success" value="Add to Cart" />

					</div>
				</form>
			</div>
			<?php
					}
				}
			?>
			<div style="clear:both"></div>
			<br />
			<h3>Checkout</h3>
			<div class="table-responsive">
				<table class="table table-bordered">
					<tr>
						<th width="25%">Product Name</th>
						<th width="25%">Price</th>
						<th width="25%">Action</th>
					</tr>
					<?php
					if(!empty($_SESSION["shopping_cart"]))
					{
						$total = 0;
						foreach($_SESSION["shopping_cart"] as $keys => $values)
						{
					?>
					<tr>
						<td><?php echo $values["item_name"]; ?></td>
						<td>$ <?php echo $values["item_price"]; ?></td>
						<td><a href="index.php?action=delete&id=<?php echo $values["item_id"]; ?>"><span class="text-danger">Remove</span></a></td>
						<td><button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">ADD Customer</button></td>
					</tr>
					<?php } ?>
					<?php } ?>
						
				</table>
			</div>
		</div>
	</div>
	<br />


<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Customer Details</h4>
        </div>
            <div class="modal-body">
          

                <form action="" method="post" enctype="multipart/form-data">
                    
                         <label>Customer Name</label>
                         <input type="text" name="customer_name" class="form-control">
                    
                         <label>Email</label>
                         <input type="text" name="email" class="form-control">
                    
                         <label>Phone Number</label>
                         <input type="text" name="phone_number" class="form-control">

                         <label>Address</label>
                         <input type="text" name="address" class="form-control">
                         
                         <input type="submit" name="submit_form" value="submit" class="btn btn-success">
                    
               </form>
            </div>
           <div class="modal-footer">
             <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
           </div>
      </div>
    </div>
  </div>
 </body>
</html>

   
<?php

 if(isset($_POST['submit_form'])) {

 $customer_name = $_POST['customer_name'];
 $email         = $_POST['email'];
 $phone_number  = $_POST['phone_number'];
 $address       = $_POST['address'];

 $conn = mysqli_connect('localhost', 'root1', 'root1', 'product_database') or die("connection error");
 $sql  = "INSERT INTO order_customer_table(customer_name,email,phone_number,address) VALUES('$customer_name','$email','$phone_number','$address')";
 $result= mysqli_query($conn, $sql) or die(mysqli_error($conn));

} 
?>