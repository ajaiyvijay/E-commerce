<?php
 include 'header.php';
 include 'lib/connection.php';
 
 if(isset($_POST['name'])) {
    $name = strip_tags($_POST['name']);

    $sql = "SELECT * FROM product WHERE name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $result = $stmt->get_result();
 }
 
 if(isset($_POST['add_to_cart'])){

  if(isset($_SESSION['auth']))
  {
     if($_SESSION['auth']!=1)
     {
         header("location:login.php");
     }
  }
  else
  {
     header("location:login.php");
  }

    $user_id = strip_tags($_POST['user_id']);
    $product_name = strip_tags($_POST['product_name']);
    $product_price = strip_tags($_POST['product_price']);
    $product_id = strip_tags($_POST['product_id']);
    $product_quantity = 1;

    $select_cart = $conn->prepare("SELECT * FROM cart WHERE productid = ? && userid = ?");
    $select_cart->bind_param("ss", $product_id, $user_id);
    $select_cart->execute();
    $select_cart_result = $select_cart->get_result();

    if($select_cart_result->num_rows > 0){
      echo $message[] = 'Product already added to cart';
    }
    else{
       $insert_product = $conn->prepare("INSERT INTO cart (userid, productid, name, quantity, price) VALUES (?, ?, ?, ?, ?)");
       $insert_product->bind_param("sssss", $user_id, $product_id, $product_name, $product_quantity, $product_price);
       $insert_product->execute();
       echo $message[] = 'Product added to cart successfully';
    }
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/pending_orders.css">
</head>
<body>

<div class="container pendingbody">
  <h5>Search Result</h5>
  <div class="container">
   <div class="row">
   <?php
          if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {
              ?>
          <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <div class="col-md-3 col-sm-6 col-6">
              <div>
                <img src="admin/product_img/<?php echo $row['imgname']; ?>"  width="" height="300" style="vertical-align:left" >
              </div>
              <div>
              <div>
                <h6><?php echo $row["name"] ?></h6> 
                <span><?php echo $row["Price"] ?></span> 
                <input type="hidden" name="user_id" value="<?php echo $_SESSION['userid'];?>" >
                <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>"> 
                <input type="hidden" name="product_name" value="<?php echo $row['name']; ?>">
                <input type="hidden" name="product_price" value="<?php echo $row['Price']; ?>">              
              </div>
              <input type="submit" class="btn btn btn-primary" value="Add to Cart" name="add_to_cart">
              </div>
              
            </div>
            </form>
            <?php 
    }
        } 
        else 
            echo "0 results";
        ?>

            
          </div>
  </div>
</div>
    
</body>
</html>                 