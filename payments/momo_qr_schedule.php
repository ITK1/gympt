<?php
session_start();
require_once '../includes/config.php';

if (!isset($_GET['schedule_id'], $_GET['amount'])) die("Thiếu thông tin.");

$schedule_id = intval($_GET['schedule_id']);
$amount = floatval($_GET['amount']);
$user_id = $_SESSION['user_id'] ?? 0;

$endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";
$partnerCode = "MOMOXXXXXX";
$accessKey = "F8BBA842ECF85";
$secretKey = "K951B6PE1waDMi640xX08PD3vg6EkVlz";

$orderId = time();
$requestId = time() . "";
$orderInfo = "Thanh toán lịch tập PT Gym";
$redirectUrl = "http://yourdomain.com/payment_success.php";
$ipnUrl = "http://yourdomain.com/ipn_momo.php";
$extraData = json_encode(['schedule_id' => $schedule_id, 'user_id' => $user_id]);

$rawHash = "accessKey=$accessKey&amount=$amount&extraData=$extraData&ipnUrl=$ipnUrl&orderId=$orderId&orderInfo=$orderInfo&partnerCode=$partnerCode&redirectUrl=$redirectUrl&requestId=$requestId&requestType=captureWallet";
$signature = hash_hmac("sha256", $rawHash, $secretKey);

$data = [
    'partnerCode' => $partnerCode,
    'partnerName' => "PT GYM",
    'storeId' => "PT_GYM_001",
    'requestId' => $requestId,
    'amount' => $amount,
    'orderId' => $orderId,
    'orderInfo' => $orderInfo,
    'redirectUrl' => $redirectUrl . "?schedule_id=$schedule_id",
    'ipnUrl' => $ipnUrl,
    'lang' => 'vi',
    'extraData' => $extraData,
    'requestType' => 'captureWallet',
    'signature' => $signature
];

$ch = curl_init($endpoint);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
$response = json_decode(curl_exec($ch), true);
curl_close($ch);

if (isset($response['payUrl'])) {
    header("Location: " . $response['payUrl']);
    exit;
} else {
    echo "Lỗi tạo QR:";
    print_r($response);
}
?>
