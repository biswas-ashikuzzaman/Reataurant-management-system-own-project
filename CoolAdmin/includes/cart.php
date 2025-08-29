<?php
</div>
</div>
<div class="card-body">
<?php if (!empty($_SESSION['flash_success'])): ?>
<div class="alert alert-success"><?= $_SESSION['flash_success']; unset($_SESSION['flash_success']); ?></div>
<?php endif; ?>


<?php $items = cart_items(); if ($items): ?>
<form method="post">
<div class="table-responsive">
<table class="table align-items-center table-flush">
<thead class="thead-light">
<tr>
<th>Product</th>
<th>Price</th>
<th style="width:140px">Qty</th>
<th>Total</th>
<th>Action</th>
</tr>
</thead>
<tbody>
<?php foreach ($items as $it):
$line = ((float)$it['prod_price']) * ((int)$it['prod_qty']);
?>
<tr>
<td><?= htmlspecialchars($it['prod_name']) ?></td>
<td>$<?= number_format((float)$it['prod_price'], 2) ?></td>
<td>
<input type="number" min="0" name="qty[<?= htmlspecialchars($it['prod_id']) ?>]" value="<?= (int)$it['prod_qty'] ?>" class="form-control form-control-sm" style="max-width:100px;">
<small class="text-muted">0 to remove</small>
</td>
<td>$<?= number_format($line, 2) ?></td>
<td>
<a class="btn btn-sm btn-danger" href="cart.php?remove=<?= urlencode($it['prod_id']) ?>">Remove</a>
</td>
</tr>
<?php endforeach; ?>
<tr>
<th colspan="3" class="text-right">Grand Total</th>
<th>$<?= number_format(cart_grand_total(), 2) ?></th>
<th></th>
</tr>
</tbody>
</table>
</div>
<div class="d-flex justify-content-between mt-3">
<button type="submit" name="update_qty" class="btn btn-primary">Update Cart</button>
<a href="checkout.php" class="btn btn-success">Proceed to Checkout</a>
</div>
</form>
<?php else: ?>
<div class="alert alert-info">Your cart is empty.</div>
<a href="products.php" class="btn btn-primary">Browse Products</a>
<?php endif; ?>


</div>
</div>
</div>
</div>
<?php require_once('partials/_footer.php'); ?>
</div>
</div>
<?php require_once('partials/_scripts.php'); ?>
</body>
</html>