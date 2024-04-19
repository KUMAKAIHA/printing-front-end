<?php
$uploadDir = '/home/ubuntu/downloads/';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $targetDir = realpath($uploadDir); // 获取目标目录的绝对路径
    $fileName = $_FILES['file']['name'];
    $cleanFileName = preg_replace("/[^\p{Han}a-zA-Z0-9_.]/u", "", $fileName); // 删除非法字符
    $uploadFile = $targetDir . '/' . $cleanFileName;

    // 允许的文件类型
    $allowedExtensions = array(
        'pdf',
        'txt',
        'jpg',
        'jpeg',
        'png',
        'doc',
        'docx',
        'xls',
        'xlsx',
        'ppt',
        'pptx'
    );

    // 允许的文件大小（以字节为单位）
    $allowedFileSize = 104857600; // 100MB

    $fileExtension = strtolower(pathinfo($cleanFileName, PATHINFO_EXTENSION));
    $fileHeader = $_FILES['file']['tmp_name'];
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $fileMimeType = finfo_file($finfo, $fileHeader);

    if (!in_array($fileExtension, $allowedExtensions)) {
        echo "仅支持上传 .pdf, .txt, .jpg, .jpeg, .png, .doc, .docx, .xls, .xlsx, .ppt, .pptx 格式的文件。";
    } elseif ($_FILES['file']['size'] > $allowedFileSize) {
        echo "文件大小超出限制。请上传不超过 100MB 的文件。";
    } elseif (!in_array($fileMimeType, array(
        'application/pdf',
        'text/plain',
        'image/jpeg',
        'image/png',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/vnd.ms-powerpoint',
        'application/vnd.openxmlformats-officedocument.presentationml.presentation'
    ))) {
        echo "仅支持上传 .pdf, .txt, .jpg, .jpeg, .png, .doc, .docx, .xls, .xlsx, .ppt, .pptx 格式的文件。";
    } elseif (file_exists($uploadFile) && !is_writable($uploadFile)) {
        echo "目标文件已存在且不可写。";
    } elseif (move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile)) {
        chmod($uploadFile, 0666); // 设置上传的文件为可读可写但不可执行权限
        echo "文件上传成功。";
    } else {
        echo "文件上传失败。";
    }
}
?>
