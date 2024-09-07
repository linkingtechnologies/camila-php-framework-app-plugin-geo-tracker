<?php
$logDir = __DIR__ . '../../../var/log';
if (!file_exists($logDir)) {
    mkdir($logDir, 0777, true);
}
$logFile = $logDir . '/geotracker.txt';
$getParams = $_GET;
$postParams = $_POST;
$rawPayload = file_get_contents('php://input');
$data = [];
if (!empty($getParams)) {
    $data['GET'] = $getParams;
}
if (!empty($postParams)) {
    $data['POST'] = $postParams;
}
if (!empty($rawPayload)) {
    $data['RAW_PAYLOAD'] = $rawPayload;
}
$logEntry = date('Y-m-d H:i:s') . " - " . print_r($data, true) . PHP_EOL;
file_put_contents($logFile, $logEntry, FILE_APPEND);
echo "Dati e payload salvati nel log.";
?>
