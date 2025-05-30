<?php
require './includes/config.php'; // kết nối DB
require './vendor/autoload.php'; // thư viện QR code

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;

// Lấy ID từ URL
$requestId = isset($_GET['request_id']) ? intval($_GET['request_id']) : 0;
if ($requestId <= 0) {
    die("ID yêu cầu không hợp lệ.");
}

// Lấy thông tin đơn hàng
$stmt = $conn->prepare("SELECT amount FROM package_requests WHERE id = ?");
$stmt->bind_param("i", $requestId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Không tìm thấy yêu cầu. ID: $requestId");
}

$order = $result->fetch_assoc();
$amount = $order['amount'];

// Thông tin đơn Momo (giả lập)
$partnerCode = "MOMO123456"; // thay bằng mã đối tác thật
$orderInfo = "Thanh toán gói tập #" . $requestId;
$redirectUrl = "http://localhost/ptgym/payment_callback.php?request_id=" . $requestId;

// Tạo URL thanh toán Momo (giả lập)
$momoPayUrl = "momo://pay?partnerCode=$partnerCode&requestId=$requestId&amount=$amount&orderInfo=" . urlencode($orderInfo) . "&redirectUrl=" . urlencode($redirectUrl);

// Tạo mã QR từ URL
$qrImage = Builder::create()
    ->data($momoPayUrl)
    ->encoding(new Encoding('UTF-8'))
    ->errorCorrectionLevel(new ErrorCorrectionLevelLow())//lỗilỗi
    ->size(300)
    ->margin(10)
    ->build();

// Xuất hình ảnh QR
header('Content-Type: image/png');
echo $qrImage->getString();
?>
