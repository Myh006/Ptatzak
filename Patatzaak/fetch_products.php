<?php
include 'database.php'; // Database connection

header('Content-Type: application/json');

try {
    // Check if a category is provided
    if (isset($_GET['category']) && $_GET['category'] !== 'all') {
        $category = $_GET['category'];
        $stmt = $pdo->prepare("SELECT product_name, product_price, product_img FROM products WHERE product_cat = :category");
        $stmt->bindParam(':category', $category, PDO::PARAM_STR);
        $stmt->execute();
    } else {
        // Fetch all products if 'all' is selected or no category provided
        $stmt = $pdo->query("SELECT product_name, product_price, product_img FROM products");
    }

    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($products);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
