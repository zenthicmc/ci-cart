<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8">
  <title><?= $title ?></title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
  <script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script><link rel="stylesheet" href="/css/style.css">
</head>
<body>
<!-- partial:index.partial.html -->
<!-- Nav -->
<nav class="navbar navbar-inverse bg-inverse fixed-top bg-faded">
    <div class="row d-flex justify-content-around">
        <div class="col text-left" style="max-width: 250px;">
          <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#cart">Cart (<?= count($carts) ?>)</button>
          &nbsp;<a href="/clearcart" class="clear-cart btn btn-danger">Clear Cart</a>
        </div>
        <div class="col d-flex text-right" style="max-width: 200px;">
          <h5 class="text-white m-2"><?= $username ?></h5>
          <p class="m-2"><a href="/logout" class="text-white">Logout</a></p>
        </div>
    </div>
</nav>

<!-- Main -->
<div class="container">
  <?php if(session()->getFlashdata('success')) : ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <?= session()->getFlashdata('success'); ?>
    </div>
  <?php endif; ?>
  <div class="mt-5 d-flex justify-content-around">
    <?php foreach($products as $product) : ?>
    <div class="col">
      <div class="card" style="width: 20rem;">
        <img class="card-img-top" src="/img/<?= $product['image']; ?>" alt="Card image cap">
        <div class="card-block">
          <h4 class="card-title"><?= $product['name']; ?></h4>
          <p class="card-text">Price: Rp <?= $product['price']; ?></p>
          <form method="post" action="/addcart">
          <?= csrf_field() ?>
            <input type="hidden" name="id_product" value="<?= $product['id_product']; ?>">
            <input type="number" class="form-control" name="quantity" value="1" min="1">
            <input type="submit" class="add-to-cart btn btn-primary mt-2" value="Add to cart">
          </form>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</div>

 <!-- Modal -->
<div class="modal fade" id="cart" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Cart</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <?php if(empty($carts)) : ?>
          <p>Your cart is empty</p>
        <?php else: ?>
          <table class="show-cart table">
          <tr>
            <th>Product</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Total</th>
            <th>Action</th>
          </tr>
          <?php $total = 0 ?>
          <?php foreach($carts as $cart) : ?>
          <tr>
            <td><?= $cart['name'] ?></td>
            <td><?= $cart['price'] ?></td>
            <td><?= $cart['quantity'] ?></td>
            <td><?= $cart['price'] *  $cart['quantity']?></td>
            <td>
              <form action="/removecart/<?= $cart['id_cart'] ?>" method="post">
                <input type="hidden" name="_method" value="DELETE">
                <button type="submit" class="btn btn-danger">X</button>
              </form>
            </td>
            <?php $total = $total + ($cart['price'] *  $cart['quantity'])?>
          </tr>
          <?php endforeach; ?>
          </table>
          <div>Total price: Rp <?= $total ?></div>
        <?php endif; ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Order now</button>
      </div>
    </div>
  </div>
</div>
<!-- partial -->
  <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js'></script>
  <script  src="/js/script.js"></script>
</body>
</html>
