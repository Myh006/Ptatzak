<?php
session_start();
include 'database.php';

if (!isset($_SESSION['basket'])) {
    $_SESSION['basket'] = [];
}

// Add an item to the basket
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'add') {
    $productName = $_POST['productName'];
    $extraChoice = $_POST['extraChoice'];
    $quantity = (int)$_POST['quantity'];
    $price = (float)$_POST['price'];

    foreach ($_SESSION['basket'] as &$item) {
        if ($item['productName'] === $productName && $item['extraChoice'] === $extraChoice) {
            $item['quantity'] += $quantity;
            $item['totalPrice'] = $item['quantity'] * $item['price'];
            echo json_encode(['basket' => $_SESSION['basket']]);
            exit;
        }
    }

    $_SESSION['basket'][] = [
        'productName' => $productName,
        'extraChoice' => $extraChoice,
        'quantity' => $quantity,
        'price' => $price,
        'totalPrice' => $quantity * $price,
    ];
    echo json_encode(['basket' => $_SESSION['basket']]);
    exit;
}

// Remove an item from the basket
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'remove') {
    $productName = $_POST['productName'];
    $extraChoice = $_POST['extraChoice'];
    $_SESSION['basket'] = array_filter($_SESSION['basket'], function ($item) use ($productName, $extraChoice) {
        return !($item['productName'] === $productName && $item['extraChoice'] === $extraChoice);
    });
    echo json_encode(['basket' => $_SESSION['basket']]);
    exit;
}

// Calculate total price
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'calculate') {
    $totalPrice = array_reduce($_SESSION['basket'], function ($total, $item) {
        return $total + $item['totalPrice'];
    }, 0);
    echo json_encode(['totalPrice' => $totalPrice]);
    exit;
}
?>
