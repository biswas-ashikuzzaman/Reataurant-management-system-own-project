<?php
// api/orders.php
session_start();
header('Content-Type: application/json');

// DB config include
require_once __DIR__ . '/../config/db.php';

// Check request method
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
    // JSON body থেকে ডাটা নিন
    $data = json_decode(file_get_contents('php://input'), true);

    $items = $data['items'] ?? [];
    $discount = floatval($data['discount'] ?? 0);
    $tax = floatval($data['tax'] ?? 0);
    $payment_method = $data['payment_method'] ?? 'cash';
    $user_id = $_SESSION['user']['id'] ?? null;

    if (empty($items)) {
        http_response_code(400);
        echo json_encode(['error' => 'No items in order']);
        exit;
    }

    // subtotal হিসাব
    $subtotal = 0;
    foreach ($items as $it) {
        $subtotal += ($it['price'] * $it['qty']);
    }

    $discountVal = ($subtotal * ($discount/100));
    $taxVal = (($subtotal - $discountVal) * ($tax/100));
    $total = $subtotal - $discountVal + $taxVal;

    try {
        $pdo->beginTransaction();

        // orders টেবিলে insert
        $stmt = $pdo->prepare("INSERT INTO orders (user_id, total, tax, discount, payment_method, status) 
                               VALUES (?,?,?,?,?,?)");
        $stmt->execute([$user_id, $total, $taxVal, $discountVal, $payment_method, 'paid']);
        $orderId = $pdo->lastInsertId();

        // order_items টেবিলে insert
        $stmtItem = $pdo->prepare("INSERT INTO order_items (order_id, menu_item_id, title, qty, price) 
                                   VALUES (?,?,?,?,?)");
        foreach ($items as $it) {
            $stmtItem->execute([
                $orderId,
                $it['id'] ?? null,
                $it['title'],
                $it['qty'],
                $it['price']
            ]);
        }

        $pdo->commit();

        echo json_encode([
            'ok' => true,
            'order_id' => $orderId,
            'subtotal' => $subtotal,
            'discount' => $discountVal,
            'tax' => $taxVal,
            'total' => $total
        ]);

    } catch (Exception $e) {
        $pdo->rollBack();
        http_response_code(500);
        echo json_encode(['error' => 'Database error', 'details' => $e->getMessage()]);
    }
    exit;
}

if ($method === 'GET') {
    // সাম্প্রতিক অর্ডার লিস্ট দেখানোর জন্য
    $stmt = $pdo->query("SELECT o.*, u.name as user_name 
                         FROM orders o 
                         LEFT JOIN users u ON u.id=o.user_id 
                         ORDER BY o.created_at DESC LIMIT 20");
    echo json_encode($stmt->fetchAll());
    exit;
}

http_response_code(405);
echo json_encode(['error' => 'Method not allowed']);
