function togglePrintingOptions() {
    var printingOptions = document.getElementById("printingOptions");
    var fileUploadArea = document.getElementById("fileUploadArea");
    var button = document.querySelector("#functionArea button");
    var container = document.querySelector('.container');
    container.classList.toggle('flip');
    
    if (printingOptions.style.display === "none") {
        printingOptions.style.display = "block";
        fileUploadArea.style.display = "none";
        button.innerHTML = "只需要上传文件点这切换";
    } else {
        printingOptions.style.display = "none";
        fileUploadArea.style.display = "block";
        button.innerHTML = "需要打印PDF点这切换";
    }
}

var helpButton = document.getElementById("helpButton");
var helpDialog = document.getElementById("helpDialog");
var closeHelpButton = document.getElementById("closeHelpButton");

helpButton.addEventListener("click", function() {
    helpDialog.style.display = "block";
});

closeHelpButton.addEventListener("click", function() {
    helpDialog.style.display = "none";
});

var paymentButton = document.getElementById("paymentButton");
var paymentDialog = document.getElementById("paymentDialog");
var closePaymentButton = document.getElementById("closePaymentButton");

paymentButton.addEventListener("click", function() {
    paymentDialog.style.display = "block";
});

closePaymentButton.addEventListener("click", function() {
    paymentDialog.style.display = "none";
});
    
    
function handleFileSelect(event) {
    var file = event.target.files[0];
    var allowedExtensions = /(\.pdf|\.txt|\.jpg|\.jpeg|\.png|\.doc|\.docx|\.xls|\.xlsx|\.ppt|\.pptx)$/i;
    var maxFileSize = 100 * 1024 * 1024; // 100MB

    if (!allowedExtensions.exec(file.name)) {
        alert('仅支持上传后缀为 .pdf, .txt, .jpg, .jpeg, .png, .doc, .docx, .xls, .xlsx, .ppt, .pptx 的文件');
        event.target.value = '';
        return false;
    }

    if (file.name.split('.').length - 1 > 1) {
        alert('文件名中不应包含多个"."');
        event.target.value = '';
        return false;
    }

    var fileSize = file.size;
    if (fileSize > maxFileSize) {
        alert('文件大小不能超过 20MB');
        event.target.value = '';
        return false;
    }
}

function uploadFile() {
    var fileInput = document.getElementById('fileInput2'); // 修改这里的id为fileInput2
    var file = fileInput.files[0];

    if (!file) {
        alert('请先选择仅需上传的文件');
        return false;
    }

    // 执行上传
    alert('正在上传请稍等\n外网速度仅为128KB/s');
    var formData = new FormData();
    formData.append('file', file);

    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'upload2.php', true);
    xhr.onload = function () {
        if (xhr.status !== 500) {
            console.log(xhr.responseText);
            if (xhr.responseText.includes('成功')) {
                var filename = file.name; 
                logDeviceInfo2(filename);

                var currentDate = new Date();
                var currentHour = currentDate.getHours();

                var message;
                if (currentHour >= 0 && currentHour < 4) {
                    message = '文件上传成功，当日凌晨4点前有效，请及时联系人工进行打印';
                } else {
                    message = '文件上传成功，次日凌晨4点前有效，请及时联系人工进行打印';
                }

                alert(message);
            } else {
                alert('文件上传失败\n请检查文件内容及格式后重新上传');
            }
        } else {
            alert('缓存耗尽！\n请联系管理员!');
        }
    };
    xhr.send(formData);

}
    
function validateFileAndUpload() {
    var fileInput = document.getElementById('fileInput');
    var fileName = fileInput.value.trim().replace(/\s/g, ''); // 删除空格
    var allowedExtensions = /(.pdf)$/i;
    var maxFileSize = 10 * 1024 * 1024; // 10MB

    if (!allowedExtensions.exec(fileName)) {
        alert('仅支持上传后缀为 .pdf 的文件');
        fileInput.value = '';
        return false;
    }

    var fileSize = fileInput.files[0].size;
    if (fileSize > maxFileSize) {
        alert('文件大小不能超过 10MB');
        fileInput.value = '';
        return false;
    }

    // 文件验证通过，执行上传
    var formData = new FormData();
    formData.append('file', fileInput.files[0], fileName); // 传入处理后的文件名

    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'upload.php', true);
    alert('正在上传请稍等\n外网速度仅为128KB/s');
    xhr.onload = function() {
        if (xhr.status !== 500) {
            console.log(xhr.responseText);
            if (xhr.responseText.includes('成功')) {
            // 读取并解析PDF文件，获取页数
            var file = fileInput.files[0];
            if (file.type === 'application/pdf') {
                var fileReader = new FileReader();
                fileReader.onload = function() {
                    var typedarray = new Uint8Array(this.result);
                };
                fileReader.readAsArrayBuffer(file);
            }

            // 提示用户只有五分钟用于打印
            alert('您上传的文件将在五分钟后自动删除，请及时打印！');

            // 设置定时器，在五分钟后提示文件已失效
            setTimeout(function() {
                alert('文件已失效，无法继续打印！');
                window.location.reload(); // 刷新页面
                }, 300000); // 五分钟后提示文件已失效
            } else {
                alert('文件上传失败\n请检查文件内容及格式后重新上传');
            }
        } else {
            alert('缓存耗尽！\n请联系管理员!');
        }
    };
    xhr.send(formData);
        /*alert('寒假下班了');*/
}

function printFile() {
    var fileInput = document.getElementById('fileInput');
    if (fileInput.files.length === 0) {
        alert('请先选择要打印的文件');
        return;
    }

    var filename = fileInput.files[0].name;
    var encodedFilename = encodeURIComponent(filename); // 对文件名进行编码
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'print.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                logDeviceInfo(filename); // 记录设备信息和文件
                alert(xhr.responseText); // 显示打印结果
            } else {
                alert('打印请求出错，确保内容并重新上传。');
            }
            window.location.reload(); // 刷新页面
        }
    };
    xhr.send('filename=' + encodedFilename); // 发送编码后的文件名
    /*alert('寒假下班了');*/
}

function printFile2() {
    var fileInput = document.getElementById('fileInput');
    if (fileInput.files.length === 0) {
        alert('请先选择要打印的文件');
        return;
    }

    var filename = fileInput.files[0].name;
    var encodedFilename = encodeURIComponent(filename); // 对文件名进行编码
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'print2.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                logDeviceInfo3(filename); // 记录设备信息和文件
                alert(xhr.responseText); // 显示打印结果
            } else {
                alert('打印请求出错，确保内容并重新上传。');
            }
            window.location.reload(); // 刷新页面
        }
    };
    xhr.send('filename=' + encodedFilename); // 发送编码后的文件名*/
    /*alert('四月前仅可以全自动无人打印黑白内容！');*/
}

function printFile3() {
    var fileInput = document.getElementById('fileInput');
    if (fileInput.files.length === 0) {
        alert('请先选择要打印的文件');
        return;
    }

    var filename = fileInput.files[0].name;
    var encodedFilename = encodeURIComponent(filename); // 对文件名进行编码
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'print3.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                logDeviceInfo4(filename); // 记录设备信息和文件
                alert(xhr.responseText); // 显示打印结果
            } else {
                alert('打印请求出错，确保内容并重新上传。');
            }
            window.location.reload(); // 刷新页面
        }
    };
    xhr.send('filename=' + encodedFilename); // 发送编码后的文件名
    /*alert('寒假下班了');*/
}


function logDeviceInfo(filename) {
    var userAgent = navigator.userAgent;
    var deviceInfo = {
        userAgent: userAgent,
        filename: filename
    };
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'printer_log.php', true);
    xhr.setRequestHeader('Content-type', 'application/json');
    xhr.send(JSON.stringify(deviceInfo));
}

function logDeviceInfo2(filename) {
    var userAgent = navigator.userAgent;
    var deviceInfo = {
        userAgent: userAgent,
        filename: filename
    };
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'upload_log.php', true);
    xhr.setRequestHeader('Content-type', 'application/json');
    xhr.send(JSON.stringify(deviceInfo));
}

function logDeviceInfo3(filename) {
    var userAgent = navigator.userAgent;
    var deviceInfo = {
        userAgent: userAgent,
        filename: filename
    };
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'printer_log2.php', true);
    xhr.setRequestHeader('Content-type', 'application/json');
    xhr.send(JSON.stringify(deviceInfo));
}
  
function logDeviceInfo4(filename) {
    var userAgent = navigator.userAgent;
    var deviceInfo = {
        userAgent: userAgent,
        filename: filename
    };
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'printer_log3.php', true);
    xhr.setRequestHeader('Content-type', 'application/json');
    xhr.send(JSON.stringify(deviceInfo));
}
    
var closePreviewButton = document.getElementById('closePreviewButton');
closePreviewButton.addEventListener('click', function() {
    var previewDialog = document.getElementById('previewDialog');
    previewDialog.style.display = 'none';
});