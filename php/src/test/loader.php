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