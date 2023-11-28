<?php
session_start();

if (!empty($_POST)) {
    require_once 'db.php';
    $orderId = save('orders', $_POST);;
}

if (isset($_GET['mdOrder']) && isset($_GET['checksum']) && isset($_GET['amount']) && isset($_GET['status']) && isset($_GET['orderNumber'])) {
    $status = checkCallbackHash($_GET['orderNumber']);
    http_response_code($status);
}

function checkCallbackHash(string $orderNumber): string
{
    $dataString = 'amount;' . $_GET['amount'] . ';mdOrder;' . $_GET['mdOrder'] . ';operation;deposited;orderNumber;' . $_GET['orderNumber'] . ';status;' . $_GET['status'] . ';';
    $key = 'mySecretToken';
    $hmac = hash_hmac('sha256', $dataString, $key);
    $checksum = trim($_GET['checksum']);

    if (hash_equals($hmac, $checksum)) {
        $status = $_GET['status'] === '1' ? 4 : 2;
        R::exec('UPDATE orders set status_id=' . $status . ' WHERE id=' . $orderNumber );
        return 200;
    } else {
        return 500;
    }
}

function save($table, $data): int|string|null
{
    $tbl = R::dispense($table);
    foreach ($data as $key => $value) {
        $tbl->$key = $value;
    }
    $orderId = R::store($tbl);
    R::exec('UPDATE orders SET status_id=3 WHERE id=' . $orderId);
    sendRequest();
    return $orderId;

}

function sendRequest(): void
{
    $currentOrderData = R::getAll("SELECT orders.id, product.id as productId, orders.quantity, product.title, product.price, orders.status_id FROM orders LEFT JOIN product ON orders.product_id = product.id ORDER BY orders.id DESC LIMIT 1")[0];
    $vars = array();

    $vars['userName'] = 'login';
    $vars['password'] = 'password';
    $vars['orderNumber'] = $currentOrderData['id'];
    $cart = array(
        array(
            'positionId' => $currentOrderData['productId'],
            'name' => $currentOrderData['title'],
            'quantity' => array(
                'value' => $currentOrderData['quantity'],
                'measure' => 'шт'
            ),
            'itemAmount' => $currentOrderData['quantity'],
            'itemCode' => $currentOrderData['productId'],
            'tax' => array(
                'taxType' => 0,
                'taxSum' => 0
            ),
            'itemPrice' => $currentOrderData['price']
        )
    );

    $vars['orderBundle'] = json_encode(
        array(
            'cartItems' => array(
                'items' => $cart
            )
        ),
        JSON_UNESCAPED_UNICODE
    );

    $vars['amount'] = $currentOrderData['quantity'] * $currentOrderData['price'];
    $vars['returnUrl'] = 'http://localhost/success/';
    $vars['failUrl'] = 'http://localhost/error/';
    $vars['description'] = 'Заказ №' . $currentOrderData['id'] . ' на localhost';

    echo "<pre>";
    print_r($vars);
    echo "</pre>";

    $ch = curl_init('https://3dsec.sberbank.ru/payment/rest/register.do?' . http_build_query($vars));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HEADER, false);
    $res = curl_exec($ch);
    curl_close($ch);

    echo "<pre>";
    print_r($res);
    echo "</pre>";
}
