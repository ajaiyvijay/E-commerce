<?php
 include 'header.php';
 include 'lib/connection.php';
 $message = array();
 if (isset($_POST['search'])) {
   $name = $_POST['name'];
   //prevent SQL injection by using prepared statement
   $stmt = $conn->prepare("SELECT * FROM product WHERE name = ? OR category = ?");
   $stmt->bind_param("ss", $name, $name);
   $stmt->execute();
   $result = $stmt->get_result();
 }
 if (isset($_POST['add_to_cart'])) {

   if (isset($_SESSION['auth'])) {
     if ($_SESSION['auth'] != 1) {
       header("location:login.php");
       exit();
     }
   } else {
     header("location:login.php");
     exit();
   }

   $user_id = $_POST['user_id'];
   $product_name = $_POST['product_name'];
   $product_price = $_POST['product_price'];
   $product_id = $_POST['product_id'];
   $product_quantity = 1;

   $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE productid = '$product_id' && userid='$user_id'");

   if (mysqli_num_rows($select_cart) > 0) {
     $message[] = 'Product already added to cart';
   } else {
     $insert_product = mysqli_query($conn, "INSERT INTO `cart`(userid, productid, name, quantity, price) VALUES('$user_id', '$product_id', '$product_name', '$product_quantity', '$product_price')");
     $message[] = 'Product added to cart successfully';
   }
 }

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Search Results</title>
  <link rel="stylesheet" href="css/pending_orders.css">
</head>

<body>

  <div class="container pendingbody">
    <h5>Search Result</h5>
    <div class="container">
      <div class="row">
        <?php
        if (isset($result) && mysqli_num_rows($result) > 0) {
          // output data of each row
          while ($row = mysqli_fetch_assoc($result)) {
        ?>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
              <div class="col-md-3 col-sm-6 col-6">
                <div>
                  <img src="admin/product_img/<?php echo $row['imgname']; ?>" width="" height="300" style="vertical-align:left">
                </div>
                <div>
                  <div>
                    <h6><?php echo $row["name"] ?></h6>
                    <span><?php echo $row["Price"] ?></span>
                    <input type="hidden" name="user_id" value="<?php echo $_SESSION['userid']; ?>">
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
        } else {
 
            echo "0 results";
        ?>

            
          </div>
  </div>
</div>
    
</body>
</html>