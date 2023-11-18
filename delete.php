<?php
$uploadDir = '/home/ubuntu/downloads/cache/';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['filename'])) {
    $filename = str_replace(' ', '', $_POST['filename']); // 删除文件名中的空格
    $filePath = $uploadDir . $filename;

    if (file_exists($filePath)) {
        if (isset($_POST['printed']) && $_POST['printed'] === 'true') {
            if (unlink($filePath)) {
                echo '文件删除成功';
            } else {
                echo '文件删除失败';
            }
        } else {
            if (time() - filemtime($filePath) >= 120) {
                if (unlink($filePath)) {
                    echo '文件删除成功';
                } else {
                    echo '文件删除失败';
                }
            } else {
                echo '文件将在两分钟后自动删除';
            }
        }
    } else {
        echo '文件不存在';
    }
} else {
    echo '未接收到文件名';
}
?>
