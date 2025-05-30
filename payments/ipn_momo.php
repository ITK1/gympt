<?php
require_once '../includes/config.php';

$data = json_decode(file_get_contents("php://input"), true);

if ($data['resultCode'] == 0) {
    $extra = json_decode($data['extraData'], true);
    $schedule_id = $extra['schedule_id'];

    $stmt = $conn->prepare("UPDATE payments SET payment_status = 'paid' WHERE schedule_id = ?");
    $stmt->bind_param("i", $schedule_id);
    $stmt->execute();
    http_response_code(200);
    echo "IPN nhận thành công.";
} else {
    http_response_code(400);
    echo "IPN thất bại.";
}
?>
