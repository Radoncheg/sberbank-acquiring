<?php
require_once 'inc/db.php';
require_once 'inc/functions.php';
$products = R::findAll('product');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="css/main.css">
    <script src="js/main.js"></script>
    <title>Payment</title>
</head>
<body>
<form class="payment-form" method="post">
    <div class="mb-3">
        <label for="inputName" class="form-label">Покупатель</label>
        <input type="name" placeholder="Фамилия Имя Отчество" class="form-control" name="name" id="inputName"
               aria-describedby="nameHelp" required>
    </div>
    <label class="form-label">Товар:</label>
    <select class="form-select" id="selectProduct" name="product_id" aria-label="inputProducts" onchange="getPrice(this)" required>
        <option value="" selected>Выберете товар</option>
        <?php foreach ($products as $product): ?>
            <option value="<?php echo $product['id'] ?>"
                    data-price="<?php echo $product['price'] ?>"><?php echo $product['title'] ?></option>
        <?php endforeach; ?>
    </select>
    <p>Введите количество товара:</p>
    <div class="payment-form__cart">
        <input type="number" name="quantity" class="quantity" id="productCount" value="1" min="1" max="999"
               onchange="getPrice(this)">
        <div>
            <p>Цена за 1 ед.: <span class="price">0.00</span>₽<br/>Итоговая цена: <span class="price">0.00</span>₽</p>
        </div>
    </div>
    <button type="submit" class="btn btn-success">Купить</button>
</form>
</body>
</html>