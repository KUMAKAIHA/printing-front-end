<?php
$filename = $_POST['filename'];
$cleanedFilename = preg_replace('/[^\p{Han}a-zA-Z0-9_.-]/u', '', $filename); // 去除文件名中的非法字符并保留中文
$filePath = '/home/ubuntu/downloads/cache/' . urldecode($cleanedFilename); // 对文件名进行解码

// 允许的文件类型
$allowedExtensions = array('pdf');
$fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);

// 允许的文件大小（以字节为单位）
$allowedFileSize = 10485760; // 10MB

if (!in_array(strtolower($fileExtension), $allowedExtensions)) {
    echo "仅支持打印后缀为 .pdf 的文件。";
} elseif (filesize($filePath) > $allowedFileSize) {
    echo "文件大小超出限制。请打印不超过 10MB 的文件。";
} else {
    // 执行打印操作
    exec('lp -o fit-to-page -o media=A4 ' . escapeshellarg($filePath), $output, $return_var); // 使用 escapeshellarg 函数确保文件路径中的特殊字符被正确处理

    // 检查打印命令执行结果
    if ($return_var === 0) {
        // 获取PDF文件页数
        if ($fileExtension === 'pdf') {
            $pageCount = exec('pdfinfo ' . escapeshellarg($filePath) . ' | grep "Pages:" | cut -c8-'); // 使用 escapeshellarg 函数确保文件路径中的特殊字符被正确处理
            // 将页数转换为整数形式
            $pageCount = intval($pageCount);
            
            echo "文件已经成功打印并删除，共 " . $pageCount . " 页。";
            echo "打印费用为：" . ($pageCount * 0.1) . "元。"; // 将页数乘以 0.1，显示打印费用
            
            // 将页数写入pagecache.txt文件
            $pageCacheFile = '/home/ubuntu/cacheandlog/pagecache.txt';
            if (!file_exists($pageCacheFile)) {
                // 创建pagecache.txt文件
                file_put_contents($pageCacheFile, $pageCount);
            } else {
                // 覆盖写入页数
                file_put_contents($pageCacheFile, $pageCount);
            }
        }
        // 打印成功后删除文件
        if (file_exists($filePath)) {
            unlink($filePath); // 删除文件
        }
    } else {
        echo "执行打印命令时出错，请尝试重新上传。";
    }
}
?>
