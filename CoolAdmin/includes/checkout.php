<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
include('config/code-generator.php'); // provides $orderid, $alpha, $beta
include('includes/session_cart.php');
check_login();


$items = cart_items();
if (!$items) {
header('Location: products.php');
exit;
}


// Confirm Order
if (isset($_POST['confirm_order'])) {
  //Prevent Posting Blank Values
  if (empty($_POST["order_code"]) || empty($_POST["customer_name"])) {
    $err = "Blank Values Not Accepted";
  } else {
    $order_id = $_POST['order_id'];
    $order_code  = $_POST['order_code'];
    $customer_id = $_POST['customer_id'];
    $customer_name = $_POST['customer_name'];

    //Insert Captured information to a database table
    foreach ($items as $it) {
      $prod_id  = $it['prod_id'];
      $prod_name = $it['prod_name'];
      $prod_price = $it['prod_price'];
      $prod_qty = $it['prod_qty'];

      $postQuery = "INSERT INTO rpos_orders (prod_qty, order_id, order_code, customer_id, customer_name, prod_id, prod_name, prod_price) VALUES(?,?,?,?,?,?,?,?)";
      $postStmt = $mysqli->prepare($postQuery);
      //bind paramaters
      $rc = $postStmt->bind_param('ssssssss', $prod_qty, $order_id, $order_code, $customer_id, $customer_name, $prod_id, $prod_name, $prod_price);
      $postStmt->execute();
    }
    //declare a varible which will be passed to alert function
    if ($postStmt) {
      cart_clear();
      $success = "Order Submitted" && header("refresh:1; url=payments.php");
    } else {
      $err = "Please Try Again Or Try Later";
    }
  }
}