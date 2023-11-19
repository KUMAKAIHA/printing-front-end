# printing-front-end
基于CUPS命令行的网络自助打印前端，提供打印临时上传及自动打印和文件单独上传，大部分由ChatGPT3.5开发

A network self-service printing front-end based on the CUPS command line, providing temporary and automatic printing uploads, as well as individual file uploads, mostly developed by ChatGPT3.5

具有一定防止文件上传漏洞功能

Has certain functions to prevent file upload vulnerabilities

美观的前端

Beautiful front-end

可使用a.jpg与b.jpg进行二维码收款

Can use a.jpg and b. jpg for QR code payment

会自动删除已打印或者未打印的临时文件

Automatically delete temporary files that have been printed or have not been printed

具备PDF预览功能

Equipped with PDF preview function

你需要修改上传目录，默认为/home/ubuntu/downloads

You need to modify the upload directory, which defaults to/home/ubuntu/downloads

如果你需要，你需要修改位于printer.php的lp -o fit-to-page -o media=A4命令

If you need it, you need to modify the lp - o fit to page - o media=A4 command located in printer.php

环境基于神雕的海纳思系统

The Environment Based on the Divine Sculpture HiNAS System

可访问www.ecoo.top来获取关于海纳思的内容

You can visit www.ecoo.top to obtain information about HiNAS
