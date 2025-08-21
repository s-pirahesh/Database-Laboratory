<?php
session_start();
require_once '../includes/database.php'; 
require_once '../includes/header.php';

$productName = '';
$minPrice = '';
$productColor = '';
$productGroupID = '';
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $productName = trim($_POST['productName']);
    $minPrice = trim($_POST['minPrice']);
    $productColor = trim($_POST['productColor']);
    $productGroupID = trim($_POST['productGroupID']);

    if (empty($productName)) { $errors[] = "نام محصول نمی‌تواند خالی باشد."; }
    if (empty($minPrice)) { $errors[] = "قیمت محصول نمی‌تواند خالی باشد."; } 
    elseif (!is_numeric($minPrice)) { $errors[] = "قیمت باید یک مقدار عددی باشد."; }
    if (empty($productGroupID) || !is_numeric($productGroupID)) { $errors[] = "گروه محصول باید انتخاب شود."; }

    if (empty($errors)) {
        try {
            $sql = "INSERT INTO product_tbl (ProductName, minPrice, ProductColor, ProductGroupID) VALUES (:productName, :minPrice, :productColor, :productGroupID)";
            $stmt = $pdo->prepare($sql);
            
            $stmt->bindParam(':productName', $productName);
            $stmt->bindParam(':minPrice', $minPrice);
            $stmt->bindParam(':productColor', $productColor);
            $stmt->bindParam(':productGroupID', $productGroupID);
            
            $stmt->execute();

            $_SESSION['success_message'] = "محصول '" . htmlspecialchars($productName) . "' با موفقیت اضافه شد.";
            header("Location: products.php");
            exit();
        } catch (PDOException $e) {
            $errors[] = "خطا در ذخیره محصول: " . $e->getMessage();
        }
    }
}
?>

<h1>افزودن محصول جدید</h1>

<?php
if (!empty($errors)) {
    echo '<div style="color: red; border: 1px solid red; padding: 10px; margin-bottom: 20px;"><ul>';
    foreach ($errors as $error) {
        echo '<li>' . $error . '</li>';
    }
    echo '</ul></div>';
}
?>

<form action="add_product.php" method="post">
    <p>
        <label for="productName">نام محصول:</label><br>
        <input type="text" id="productName" name="productName" value="<?php echo htmlspecialchars($productName); ?>" style="width: 50%;">
    </p>
    <p>
        <label for="minPrice">قیمت:</label><br>
        <input type="text" id="minPrice" name="minPrice" value="<?php echo htmlspecialchars($minPrice); ?>" style="width: 50%;">
    </p>
    <p>
        <label for="productColor">رنگ:</label><br>
        <input type="text" id="productColor" name="productColor" value="<?php echo htmlspecialchars($productColor); ?>" style="width: 50%;">
    </p>
    <p>
        <label for="productGroupID">کد گروه محصول:</label><br>
        <input type="text" id="productGroupID" name="productGroupID" value="<?php echo htmlspecialchars($productGroupID); ?>" style="width: 50%;">
    </p>
    <p>
        <input type="submit" value="ذخیره محصول">
    </p>
</form>

<?php
require_once '../includes/footer.php';
?>
