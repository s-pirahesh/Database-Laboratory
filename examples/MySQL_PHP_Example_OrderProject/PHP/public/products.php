<?php
session_start();
require_once '../includes/database.php'; 
require_once '../includes/header.php';     
?>

<h1>لیست محصولات</h1>

<?php
// نمایش پیام موفقیت از Session
if (isset($_SESSION['success_message'])) {
    echo '<div style="color: green; border: 1px solid green; padding: 10px; margin-bottom: 20px;">';
    echo htmlspecialchars($_SESSION['success_message']);
    echo '</div>';
    unset($_SESSION['success_message']);
}

try {
    $sql = "SELECT productID, ProductName, minPrice, ProductColor FROM product_tbl ORDER BY productID DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $products = $stmt->fetchAll();

    if ($products) {
        echo '<table style="width: 100%; border-collapse: collapse; text-align: right;">';
        echo '<tr>
                <th style="border: 1px solid #ddd; padding: 8px; background-color: #f2f2f2;">کد</th>
                <th style="border: 1px solid #ddd; padding: 8px; background-color: #f2f2f2;">نام محصول</th>
                <th style="border: 1px solid #ddd; padding: 8px; background-color: #f2f2f2;">قیمت</th>
                <th style="border: 1px solid #ddd; padding: 8px; background-color: #f2f2f2;">عملیات</th>
              </tr>';
        
        foreach ($products as $product) {
            echo '<tr>';
            echo '<td style="border: 1px solid #ddd; padding: 8px;">' . htmlspecialchars($product['productID']) . '</td>';
            echo '<td style="border: 1px solid #ddd; padding: 8px;">' . htmlspecialchars($product['ProductName']) . '</td>';
            echo '<td style="border: 1px solid #ddd; padding: 8px;">' . number_format($product['minPrice']) . ' تومان</td>';
            echo '<td style="border: 1px solid #ddd; padding: 8px;">
                    <a href="edit_product.php?id=' . htmlspecialchars($product['productID']) . '">ویرایش</a> |
                    <a href="delete_product.php?id=' . htmlspecialchars($product['productID']) . '" style="color: red;">حذف</a>
                  </td>';
            echo '</tr>';
        }
        
        echo '</table>';
    } else {
        echo '<p>هیچ محصولی برای نمایش وجود ندارد.</p>';
    }

} catch (PDOException $e) {
    die("خطا: امکان دریافت اطلاعات محصولات وجود ندارد. " . $e->getMessage());
}

require_once '../includes/footer.php';
?>
