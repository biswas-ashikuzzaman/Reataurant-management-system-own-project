<?php


<div class="container-fluid mt--8">
<div class="row">
<div class="col">
<div class="card shadow">
<div class="card-header border-0 d-flex align-items-center justify-content-between">
<h3 class="mb-0">Products</h3>
<a href="cart.php" class="btn btn-sm btn-primary">View Cart</a>
</div>
<div class="card-body">
<?php if (!empty($_SESSION['flash_success'])): ?>
<div class="alert alert-success"><?= $_SESSION['flash_success']; unset($_SESSION['flash_success']); ?></div>
<?php endif; if (!empty($_SESSION['flash_error'])): ?>
<div class="alert alert-danger"><?= $_SESSION['flash_error']; unset($_SESSION['flash_error']); ?></div>
<?php endif; ?>


<div class="table-responsive">
<table class="table align-items-center table-flush">
<thead class="thead-light">
<tr>
<th>Name</th>
<th>Price</th>
<th style="width:220px">Qty</th>
<th style="width:160px">Action</th>
</tr>
</thead>
<tbody>
<?php
$ret = "SELECT prod_id, prod_name, prod_price FROM rpos_products ORDER BY prod_name";
$stmt = $mysqli->prepare($ret);
$stmt->execute();
$res = $stmt->get_result();
while ($p = $res->fetch_object()):
?>
<tr>
<td><?= htmlspecialchars($p->prod_name) ?></td>
<td>$<?= number_format((float)$p->prod_price, 2) ?></td>
<td>
<form class="d-flex" method="post">
<input type="hidden" name="prod_id" value="<?= htmlspecialchars($p->prod_id) ?>">
<input type="number" min="1" value="1" name="prod_qty" class="form-control form-control-sm mr-2" style="max-width:100px;">
<button type="submit" name="add_to_cart" class="btn btn-sm btn-success">Add</button>
</form>
</td>
<td>
<a href="cart.php" class="btn btn-sm btn-outline-primary">Go to Cart</a>
</td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
</div>


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