
<div class="head-side" id="toggleBtn">
     <span class="icon">
          <i class="fa-solid fa-microchip"></i>
     </span>
     <span>DashBoard</span>
</div>
<div class="list-side">
     <div class="nav-link" onclick="navigateToPage(this, 'electrical.php')">
          <span class="icon"> 
               <i class="fa-solid fa-bolt-lightning"></i>
          </span>
          <span>Electrical Values</span>
     </div>
     <div class="nav-link" onclick="navigateToPage(this, 'quality.php')">
          <span class="icon">
               <i class="fa-solid fa-droplet"></i>
          </span>
          <span>Water Quality</span>
     </div>
     <div class="nav-link">
          <span class="icon">
               <i class="fa-solid fa-temperature-low"></i>
          </span>
          <span>Water Temperature</span>
     </div>
</div>
<div class="foot-side">
     <span class="icon"    >
          <i class="fa-solid fa-right-from-bracket"></i>
     </span>
     <span>Log out</span>
</div>

<script>
     function navigateToPage(element, page) {
          // ลบ active ออกจากทุก nav-link
          document.querySelectorAll('.nav-link').forEach(el => {
               el.classList.remove('active');
          });

          // ใส่ active ให้ element ที่คลิก
          element.classList.add('active');

          // โหลดเนื้อหาใหม่
          fetch('loader.php?page=' + encodeURIComponent(page))
               .then(response => response.text())
               .then(data => {
                    const container = document.getElementById('mainContent');
                    container.innerHTML = data;

                    // ลบ script เดิมก่อน
                    const oldScript = document.getElementById('dynamicScript');
                    if (oldScript) {
                         oldScript.remove();
                    }

                    // สร้าง script ใหม่พร้อม timestamp ป้องกัน cache
                    const script = document.createElement('script');
                    script.src = page.replace('.php', '.js') + '?t=' + Date.now();
                    script.id = 'dynamicScript';

                    script.onload = function () {
                         // เรียกฟังก์ชัน init ถ้ามี เช่น electrical.js → initElectricalPage()
                         const initFuncName = 'init' + page.replace('.php', '').replace(/^\w/, c => c.toUpperCase()) + 'Page';
                         if (typeof window[initFuncName] === 'function') {
                              window[initFuncName]();
                         } else {
                              console.warn('ไม่พบฟังก์ชัน', initFuncName);
                         }
                    };

                    document.body.appendChild(script);
               }
          );
     }

</script>