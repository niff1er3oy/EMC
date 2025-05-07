<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Dashboard</title>
        <link rel="icon" type="image/x-icon" href="/images/icon.ico">
        <link rel="stylesheet" href="style.css">
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="https://kit.fontawesome.com/cefac98de9.js" crossorigin="anonymous"></script>
    </head>
    <body>
        <?php include('sidebar.php');?>
        <div class="main-content" id="mainContent">
            <?php
                if (isset($_GET['page'])) {
                    $page = basename($_GET['page']); // ป้องกัน path traversal
                    if (file_exists($page)) {
                        include($page);
                    } else {
                        echo "ไม่พบหน้า $page";
                    }
                } else {
                    echo "ไม่ได้ระบุหน้า";
                }
            ?>
        </div>
    </body>
</html>