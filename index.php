<?php
require_once('Product.php');
require_once('Cart.php');
require_once('CartItem.php');

session_start();

if( !isset($_SESSION['SHOPPING_CART']) ) {
    $_SESSION['SHOPPING_CART'] = new Cart();
}

// ###################################################
// ############### ALL PRODUCTS ######################

$products = [
    [ "name" => "Sledgehammer", "price" => 125.75 ],
    [ "name" => "Axe", "price" => 190.50 ],
    [ "name" => "Bandsaw", "price" => 562.131 ],
    [ "name" => "Chisel", "price" => 12.9 ],
    [ "name" => "Hacksaw", "price" => 18.45 ],
];

// ############### ALL PRODUCTS ######################
// ###################################################

$all_products = array();
foreach($products as $product) {
    $all_products[$product['name']] = new Product($product['name'],$product['name'],$product['price']);
}

if( isset($_GET['mode']) && trim($_GET['mode'] === 'add') ) {
    $id = ( isset($_GET['id']) && trim($_GET['id']) != '' ) ? trim($_GET['id']) : null;

    if( $id === null || !isset($all_products[$id]) ) {
        header("location: index.php?msg=1");
        exit;
    } else {

        try {
            $_SESSION['SHOPPING_CART']->addItem($all_products[$id],1);
        }
        catch( Exception $e) {
            print_r($e->getMessage());
            exit;
        }

        header("location: index.php?msg=2");
        exit;
    }
}

if( isset($_GET['mode']) && trim($_GET['mode'] === 'remove') ) {
    $id = ( isset($_GET['id']) && trim($_GET['id']) != '' ) ? trim($_GET['id']) : null;

    if( $id === null || !isset($all_products[$id]) ) {
        header("location: index.php?msg=1");
        exit;
    } else {

        try {
            $_SESSION['SHOPPING_CART']->deleteItem($all_products[$id]);
        }
        catch( Exception $e) {
            print_r($e->getMessage());
            exit;
        }

        header("location: index.php?msg=3");
        exit;
    }
}

$items = $_SESSION['SHOPPING_CART']->getItems();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple PHP Shopping Cart</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
</head>
<body>    
    <div class="container mb-5">
        <h1 class="my-3">Simple PHP Shopping Cart</h1>
        <div class="row my-2">
            <div class="col-12">
                <h2>Products</h2>
            </div>
            <div class="col-12">
            <?php if( count($all_products) > 0 ) { ?>
                
                <table class="table table-sm table-bordered">
                    <thead>
                        <tr>
                            <th>Product name</th>
                            <th>Price</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach( $all_products as $product ) { ?>
                        <tr>
                            <th><?= $product->getName(); ?></th>
                            <th><?= number_format($product->getPrice(),2); ?></th>
                            <th><a href="index.php?mode=add&id=<?= $product->getId(); ?>" class="btn btn-sm btn-primary">Add to Cart</a></th>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>

            <?php } else { ?>
                <div class="alert alert-warning">No products available.</div>
            <?php } ?>

            </div>
        </div>

        <?php if( isset($_GET['msg']) && intval($_GET['msg']) != 0 ) { ?>
            <div class="row my-2">
                <div class="col-12">                    
                    <?php if( intval($_GET['msg']) == 1 ) { ?>
                        <div class="alert alert-warning text-center">
                            Invalid Product Detected
                        </div>
                    <?php } else if( intval($_GET['msg']) == 2 ) { ?>
                        <div class="alert alert-success text-center">
                            Cart Updated
                        </div>
                    <?php } else if( intval($_GET['msg']) == 3 ) { ?>
                        <div class="alert alert-success text-center">
                            Item Removed
                        </div>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
        
        <div class="row my-2">
            <div class="col-12">
                <h2>My Cart</h2>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                    <?php if(count($items) > 0) { ?>
                        <table class="table table-sm table-bordered">
                        <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach( $items as $item ) { ?>
                            <tr>
                                <td><?= $item->getProduct()->getName(); ?></td>
                                <td><?= number_format($item->getProduct()->getPrice(),2); ?></td>
                                <td><?= $item->getQuantity(); ?></td>
                                <td><?= number_format($item->getProduct()->getPrice() * $item->getQuantity(),2); ?></td>
                                <td>
                                    <a href="index.php?mode=remove&id=<?= $item->getProduct()->getId(); ?>" class="btn btn-sm btn-danger">Remove</a>
                                </td>
                            </tr>       
                        <?php } ?>               
                        </tbody>
                        </table>
                        <h3>Cart Total: <?= number_format($_SESSION['SHOPPING_CART']->getTotalAmount(),2); ?></h3>
                    <?php } else { ?>
                    <div class="alert alert-warning">Cart is Empty</div>
                    <?php } ?>
                    </div>
                </div>
            </div>
            
        </div>

    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
</body>
</html>