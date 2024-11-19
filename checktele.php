<?php
// Cấu hình múi giờ để ghi thời gian chính xác
date_default_timezone_set("Asia/Ho_Chi_Minh");

// Lấy thông tin từ người dùng
$ip_public = $_SERVER['REMOTE_ADDR']; // IP Public qua CGNAT
$port_public = $_SERVER['REMOTE_PORT']; // Port Public do CGNAT cấp
$forwarded_ip = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? 'Không xác định'; // IP nếu qua proxy
$user_agent = $_SERVER['HTTP_USER_AGENT']; // Thông tin thiết bị/người dùng
$request_time = date("Y-m-d H:i:s"); // Thời gian yêu cầu

// Dữ liệu gửi qua Telegram Bot
$message = "Thông tin truy cập:\n";
$message .= "Thời gian: $request_time\n";
$message .= "IP Public: $ip_public\n";
$message .= "Port Public: $port_public\n";
$message .= "IP Chuyển tiếp (Forwarded IP): $forwarded_ip\n";
$message .= "Thiết bị (User Agent): $user_agent\n";

// Token của bot và chat ID của bạn
$token = "7901950113:AAFKtJA56Vk2ZMfYPTLYVfHaOtdB5reMFE0";  // Thay thế bằng token bot của bạn
$chat_id = "5673670917";  // Thay thế bằng chat ID của bạn

// URL gửi yêu cầu GET đến Telegram Bot API
$telegram_api_url = "https://api.telegram.org/bot$token/sendMessage?chat_id=$chat_id&text=" . urlencode($message);

// Gửi yêu cầu GET tới Telegram Bot API và nhận phản hồi
$response = file_get_contents($telegram_api_url);

// Kiểm tra phản hồi từ Telegram API
if ($response) {
    // Nếu có phản hồi, kiểm tra xem có thành công không
    $response_data = json_decode($response, true);
    if ($response_data['ok']) {
        // Gửi thành công
        echo "Dữ liệu đã được gửi thành công đến bot Telegram!";
    } else {
        // Lỗi gửi
        echo "Lỗi khi gửi dữ liệu: " . $response_data['description'];
    }
} else {
    echo "Không thể kết nối đến Telegram API.";
}

// Ghi log vào file (optional)
$log = "Thời gian: $request_time\n";
$log .= "IP Public: $ip_public\n";
$log .= "Port Public: $port_public\n";
$log .= "IP chuyển tiếp (Forwarded IP): $forwarded_ip\n";
$log .= "Thiết bị (User Agent): $user_agent\n";
$log .= "--------------------------------------\n";

// Lưu log vào file
file_put_contents("access_logs.txt", $log, FILE_APPEND);

// Hiển thị thông tin ngay trên trình duyệt
echo "<h2>Thông tin truy cập</h2>";
echo "<strong>Thời gian:</strong> $request_time<br>";
echo "<strong>IP Public:</strong> $ip_public<br>";
echo "<strong>Port Public:</strong> $port_public<br>";
echo "<strong>IP Chuyển tiếp (Forwarded IP):</strong> $forwarded_ip<br>";
echo "<strong>Thiết bị:</strong> $user_agent<br>";

echo "<hr>";
echo "<p>Dữ liệu đã được ghi vào file <strong>access_logs.txt</strong> trên server.</p>";

?>
