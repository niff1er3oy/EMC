<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Dashboard</title>
        <link rel="icon" href="images/icon.ico" type="image/x-icon">
        <link rel="stylesheet" href="css/style.css">
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="https://kit.fontawesome.com/cefac98de9.js" crossorigin="anonymous"></script>
        <script src="https://cdn.zingchart.com/zingchart.min.js"></script>
    </head>
    <body>
        <div class="content">
            <div class="sidebar" id="sidebar">
                <?php include('sidebar.php');?>
            </div>
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
        </div>
    </body>
</html>

<script>
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('toggleBtn');
    const content = document.querySelector('.content');

    toggleBtn.addEventListener('click', () => {
        sidebar.classList.toggle('collapsed');

        if (sidebar.classList.contains('collapsed')) {
            content.style.gridTemplateColumns = '65px 1fr';
        } else {
            content.style.gridTemplateColumns = '250px 1fr'; // หรือขนาด sidebar ปกติของคุณ
        }
    });
</script>