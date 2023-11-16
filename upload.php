<?php
$uploadDir = '/home/ubuntu/downloads/cache/';

// 如果目录不存在，则创建目录
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $fileName = $_FILES['file']['name'];
    $fileName = preg_replace('/[^\p{Han}a-zA-Z0-9_.-]/u', '', $fileName); // 去除文件名中的非法字符并保留中文
    $uploadFile = $uploadDir . basename($fileName);

    // 允许的文件类型
    $allowedExtensions = array('pdf', 'jpg', 'jpeg', 'png');
    $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

    // 允许的文件大小（以字节为单位）
    $allowedFileSize = 10485760; // 10MB

    // 获取文件头信息
    $fileHeader = '';
    if (function_exists('finfo_open')) {
        $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
        $fileHeader = finfo_file($fileInfo, $_FILES['file']['tmp_name']);
        finfo_close($fileInfo);
    } elseif (function_exists('mime_content_type')) {
        $fileHeader = mime_content_type($_FILES['file']['tmp_name']);
    }

    if (!in_array(strtolower($fileExtension), $allowedExtensions)) {
        echo "仅支持上传后缀为 .pdf, .txt, .jpg, .jpeg, .png 的文件。";
    } elseif ($_FILES['file']['size'] > $allowedFileSize) {
        echo "文件大小超出限制。请上传不超过 10MB 的文件。";
    } elseif (strpos($fileHeader, 'image') !== false || strpos($fileHeader, 'pdf') !== false) {
        // 文件头包含'image'或'pdf'时才允许上传
        if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile)) {
            echo "文件上传成功。";
        } else {
            echo "文件上传失败。";
        }
    } else {
        echo "文件格式不支持。请上传图片或 PDF 文件。";
    }
}
?>
