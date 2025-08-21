<?php
session_start();
require_once '../includes/database.php';
require_once '../includes/header.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: products.php");
    exit();
}
$product_id = $_GET['id'];
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
            $sql = "UPDATE product_tbl SET ProductName = :productName, minPrice = :minPrice, ProductColor = :productColor, ProductGroupID = :productGroupID WHERE productID = :productID";
            $stmt = $pdo->prepare($sql);
            
            $stmt->bindParam(':productName', $productName);
            $stmt->bindParam(':minPrice', $minPrice);
            $stmt->bindParam(':productColor', $productColor);
            $stmt->bindParam(':productGroupID', $productGroupID);
            $stmt->bindParam(':productID', $product_id);
            
            $stmt->execute();

            $_SESSION['success_message'] = "محصول با موفقیت ویرایش شد.";
            header("Location: products.php");
            exit();
        } catch (PDOException $e) {
            $errors[] = "خطا در بروزرسانی محصول: " . $e->getMessage();
        }
    }
}

try {
    $sql = "SELECT * FROM product_tbl WHERE productID = :productID";
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
?>

<h1>ویرایش محصول: <?php echo htmlspecialchars($product['ProductName']); ?></h1>

<?php
if (!empty($errors)) {
    echo '<div style="color: red; border: 1px solid red; padding: 10px; margin-bottom: 20px;"><ul>';
    foreach ($errors as $error) {
        echo '<li>' . $error . '</li>';
    }
    echo '</ul></div>';
}
?>

<form action="edit_product.php?id=<?php echo $product_id; ?>" method="post">
    <p>
        <label for="productName">نام محصول:</label><br>
        <input type="text" id="productName" name="productName" value="<?php echo htmlspecialchars($product['ProductName']); ?>" style="width: 50%;">
    </p>
    <p>
        <label for="minPrice">قیمت:</label><br>
        <input type="text" id="minPrice" name="minPrice" value="<?php echo htmlspecialchars($product['minPrice']); ?>" style="width: 50%;">
    </p>
    <p>
        <label for="productColor">رنگ:</label><br>
        <input type="text" id="productColor" name="productColor" value="<?php echo htmlspecialchars($product['ProductColor']); ?>" style="width: 50%;">
    </p>
    <p>
        <label for="productGroupID">کد گروه محصول:</label><br>
        <input type="text" id="productGroupID" name="productGroupID" value="<?php echo htmlspecialchars($product['ProductGroupID']); ?>" style="width: 50%;">
    </p>
    <p>
        <input type="submit" value="ذخیره تغییرات">
        <a href="products.php">انصراف</a>
    </p>
</form>

<?php
require_once '../includes/footer.php';
?>
