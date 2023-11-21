<?php
$logFilePath = '/home/ubuntu/cacheandlog/upload_log.txt';

// 读取请求的设备信息
$requestBody = file_get_contents('php://input');

if ($requestBody === false) {
    die('无法读取请求的设备信息。');
}

$deviceInfo = json_decode($requestBody, true);

if ($deviceInfo === null) {
    die('无法解析设备信息的 JSON 数据。');
}

// 获取用户代理字符串和文件名
$userAgent = $deviceInfo['userAgent'];
$filename = $deviceInfo['filename'];

// 提取 User Agent 中的系统信息
preg_match('/\((.*?)\)/', $userAgent, $matches);
$systemInfo = isset($matches[1]) ? $matches[1] : '';

// 提取系统信息中的关键部分
preg_match('/[a-zA-Z\s\d\.]+(?:; [a-zA-Z\d]+)+/', $systemInfo, $systemMatches);
$userAgent = isset($systemMatches[0]) ? $systemMatches[0] : '';

// 读取当前序数
$currentSequence = file_get_contents('/home/ubuntu/cacheandlog/sequence2.txt');
$currentSequence = intval($currentSequence); // 将序数转换为整数

if ($currentSequence === false || $currentSequence === 0) {
    // 如果序数文件不存在或内容为空，则默认序数为0
    $currentSequence = 0;
}

// 增加序数
$nextSequence = $currentSequence + 1;

// 更新序数文件
$result = file_put_contents('/home/ubuntu/cacheandlog/sequence2.txt', $nextSequence);

if ($result === false) {
    die('无法更新序数文件。');
}


// 格式化日志行
$logLine = $nextSequence . ' | ' . date('Y-m-d H:i:s') . ' | 系统信息: ' . $userAgent . ' | 文件名称: ' . $filename . PHP_EOL;

// 将日志行追加到日志文件
$result = file_put_contents($logFilePath, $logLine, FILE_APPEND | LOCK_EX);

if ($result === false) {
    die('无法写入日志文件。');
}
else{
    die('写入日志文件。');
}
?>
