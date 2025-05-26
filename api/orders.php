<?php
require 'vendor/autoload.php';

use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;

$clientId = "ВАШ_CLIENT_ID";
$clientSecret = "ВАШ_SECRET";

$environment = new SandboxEnvironment($clientId, $clientSecret);
$client = new PayPalHttpClient($environment);

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($_GET['action'])) {
    $data = json_decode(file_get_contents('php://input'), true);
    $request = new OrdersCreateRequest();
    $request->prefer('return=representation');
    $request->body = [
        "intent" => "CAPTURE",
        "purchase_units" => [[
            "amount" => ["value" => $data['amount'], "currency_code" => "USD"]
        ]]
    ];

    try {
        $response = $client->execute($request);
        echo json_encode(['id' => $response->result->id]);
    } catch (Exception $ex) {
        http_response_code(500);
        echo json_encode(['error' => $ex->getMessage()]);
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'capture' && isset($_GET['orderID'])) {
    $orderId = $_GET['orderID'];
    $request = new OrdersCaptureRequest($orderId);
    $request->prefer('return=representation');

    try {
        $response = $client->execute($request);
        echo json_encode(['status' => $response->result->status]);
    } catch (Exception $ex) {
        http_response_code(500);
        echo json_encode(['error' => $ex->getMessage()]);
    }
    exit;
}

http_response_code(404);
echo json_encode(['error' => 'Invalid request']);
