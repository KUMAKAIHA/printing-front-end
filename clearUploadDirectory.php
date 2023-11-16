<?php
$uploadDir = '/home/ubuntu/downloads/cache/';

// 清空上传目录内文件
$files = glob($uploadDir . '*'); // 获取目录内所有文件
foreach ($files as $file) {
    if (is_file($file)) {
        unlink($file); // 删除文件
    }
}
echo '上传目录内文件已清空';
?>