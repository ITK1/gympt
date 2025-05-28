<?php
// Thông tin secret key
$secretKey = 'MOMO_SECRET_KEY';

$data = file_get_contents('php://input');
$json = json_decode($data, true);

// Kiểm tra chữ ký
$partnerCode = $json['partnerCode'];
$orderId = $json['orderId'];
$requestId = $json['requestId'];
$amount = $json['amount'];
$orderInfo = $json['orderInfo'];
$orderType = $json['orderType'];
$transId = $json['transId'];
$resultCode = $json['resultCode'];
$message = $json['message'];
$responseTime = $json['responseTime'];
$extraData = $json['extraData'];
$signature = $json['signature'];

// Tạo chữ ký kiểm tra
$rawHash = "partnerCode=$partnerCode&accessKey=YOUR_ACCESS_KEY&requestId=$requestId&amount=$amount&orderId=$orderId&orderInfo=$orderInfo&orderType=$orderType&transId=$transId&resultCode=$resultCode&message=$message&responseTime=$responseTime&extraData=$extraData";
$checkSignature = hash_hmac("sha256", $rawHash, $secretKey);

if ($signature === $checkSignature) {
    if ($resultCode == 0) {
        // Thanh toán thành công
        // TODO: Cập nhật trạng thái đơn hàng, ghi log vào DB
        http_response_code(200);
        echo json_encode(['status' => 'success']);
    } else {
        // Thanh toán thất bại
        http_response_code(200);
        echo json_encode(['status' => 'fail']);
    }
} else {
    // Chữ ký không hợp lệ
    http_response_code(400);
    echo json_encode(['status' => 'invalid signature']);
}
