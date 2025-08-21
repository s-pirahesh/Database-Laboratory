<?php
session_start();
require_once '../includes/database.php';
require_once '../includes/header.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: products.php");
    exit();
}
$product_id = $_GET['id'];

try {
    $sql = "SELECT ProductName FROM product_tbl WHERE productID = :productID";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':productID', $product_id);
    $stmt->execute();
    $product = $stmt->fetch();

    if (!$product) {
        die("محصولی با این شناسه یافت نشد.");
    }
} catch (PDOException $e) {
    die("خطا در دریافت اطلاعات محصول: " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $sql = "DELETE FROM product_tbl WHERE productID = :productID";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':productID', $product_id);
        $stmt->execute();

        $_SESSION['success_message'] = "محصول '" . htmlspecialchars($product['ProductName']) . "' با موفقیت حذف شد.";
        header("Location: products.php");
        exit();

    } catch (PDOException $e) {
        die("خطا در حذف محصول: " . $e->getMessage());
    }
}
?>

<h1>تایید حذف محصول</h1>
<p>
    آیا شما از حذف محصول زیر اطمینان دارید؟
</p>
<p>
    <strong>نام محصول:</strong> <?php echo htmlspecialchars($product['ProductName']); ?>
</p>

<div style="border: 1px solid red; padding: 1rem; color: #b91c1c; background-color: #fee2e2; border-radius: 8px;">
    <strong>هشدار:</strong> این عملیات غیرقابل بازگشت است!
</div>

<form action="delete_product.php?id=<?php echo $product_id; ?>" method="post" style="margin-top: 1.5rem;">
    <input type="submit" value="بله، حذف کن" style="background-color: #dc2626; color: white; padding: 0.5rem 1rem; border: none; border-radius: 5px; cursor: pointer;">
    <a href="products.php" style="margin-right: 1rem;">انصراف</a>
</form>

<?php
require_once '../includes/footer.php';
?>
