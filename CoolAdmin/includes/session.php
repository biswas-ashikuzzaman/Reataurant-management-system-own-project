<?php


function cart_update_qty($prod_id, $qty)
{
    $qty = max(0, (int)$qty);
    foreach ($_SESSION['cart'] as $k => $it) {
        if ((string)$it['prod_id'] === (string)$prod_id) {
            if ($qty === 0) {
                unset($_SESSION['cart'][$k]);
            } else {
                $_SESSION['cart'][$k]['prod_qty'] = $qty;
            }
            $_SESSION['cart'] = array_values($_SESSION['cart']);
            return true;
        }
    }
    return false;
}
function cart_remove_item($prod_id)
{
    foreach ($_SESSION['cart'] as $k => $it) {
        if ((string)$it['prod_id'] === (string)$prod_id) {
            unset($_SESSION['cart'][$k]);
            $_SESSION['cart'] = array_values($_SESSION['cart']);
            return true;
        }
    }
    return false;
}

function cart_clear()
{
    $_SESSION['cart'] = [];
}


function cart_items()
{
    return $_SESSION['cart'];
}


function cart_grand_total()
{
    $sum = 0.0;
    foreach (cart_items() as $it) {
        $sum += ((float)$it['prod_price']) * ((int)$it['prod_qty']);
    }
    return $sum;
}
