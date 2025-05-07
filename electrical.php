
<div class="device" id="device-1">
     <div class="grid-title item-title-1">
          <div class="title">Device Name <i class="fa-solid fa-server"></i></div>
          <span class="value">Device 1</span>
     </div>
     <div class="grid-item" data-type="voltage" data-device="1">
          <div class="title">Voltage <i class="fa-solid fa-chevron-down" style="color: #e66565;"></i></div>
          <span class="value">232</span> V
     </div>
     <div class="grid-item" data-type="current" data-device="1">
          <div class="title">Current <i class="fa-solid fa-chevron-up" style="color: #63E6BE;"></i></div>
          <span class="value">9.065</span> A
     </div>
     <div class="grid-item" data-type="a-power" data-device="1">
          <div class="title">Active Power <i class="fa-solid fa-equals"></i></div>
          <span class="value">1.389</span> W
     </div> 
     <div class="grid-item" data-type="r-power" data-device="1">
          <div class="title">Reactive Power <i class="fa-solid fa-equals"></i></div>
          <span class="value">1.559</span> VAR
     </div>
     <div class="grid-item" data-type="app-power" data-device="1">
          <div class="title">Apparent Power <i class="fa-solid fa-chevron-down" style="color: #e66565;"></i></div>
          <span class="value">2.089</span> VA
     </div>
     <div class="grid-item" data-type="power-f" data-device="1">
          <div class="title">Power Factor <i class="fa-solid fa-chevron-up" style="color: #63E6BE;"></i></div>
          <span class="value">9.065</span>
     </div>  
     <div class="grid-item" data-type="f" data-device="1">
          <div class="title">Frequency <i class="fa-solid fa-chevron-up" style="color: #63E6BE;"></i></div>
          <span class="value">49.999</span> Hz
     </div>
     <div class="grid-item" data-type="t-a-e" data-device="1">
          <div class="title">Total Active Energy <i class="fa-solid fa-chevron-down" style="color: #e66565;"></i></div>
          <span class="value">6160</span> kWh
     </div>
     <div class="grid-canvas item-3">
          <canvas id="myChart1"></canvas>
     </div>
</div>
<div class="device" id="device-2">
     <div class="grid-title item-title-2">
          <div class="title">Device Name <i class="fa-solid fa-server"></i></div>
          <span class="value">Device 2</span>
     </div>
     <div class="grid-item" data-type="voltage" data-device="2">
          <div class="title">Voltage <i class="fa-solid fa-chevron-down" style="color: #e66565;"></i></div>
          <span class="value">232</span> V
     </div>
     <div class="grid-item" data-type="current" data-device="2">
          <div class="title">Current <i class="fa-solid fa-chevron-up" style="color: #63E6BE;"></i></div>
          <span class="value">9.065</span> A
     </div>
     <div class="grid-item" data-type="a-power" data-device="2">
          <div class="title">Active Power <i class="fa-solid fa-equals"></i></div>
          <span class="value">1.389</span> W
     </div> 
     <div class="grid-item" data-type="r-power" data-device="2">
          <div class="title">Reactive Power <i class="fa-solid fa-equals"></i></div>
          <span class="value">1.559</span> VAR
     </div>
     <div class="grid-item" data-type="app-power" data-device="2">
          <div class="title">Apparent Power <i class="fa-solid fa-chevron-down" style="color: #e66565;"></i></div>
          <span class="value">2.089</span> VA
     </div>
     <div class="grid-item" data-type="power-f" data-device="2">
          <div class="title">Power Factor <i class="fa-solid fa-chevron-up" style="color: #63E6BE;"></i></div>
          <span class="value">9.065</span>
     </div>  
     <div class="grid-item" data-type="f" data-device="2">
          <div class="title">Frequency <i class="fa-solid fa-chevron-up" style="color: #63E6BE;"></i></div>
          <span class="value">49.999</span> Hz
     </div>
     <div class="grid-item" data-type="t-a-e" data-device="2">
          <div class="title">Total Active Energy <i class="fa-solid fa-chevron-down" style="color: #e66565;"></i></div>
          <span class="value">6160</span> kWh
     </div>
     <div class="grid-canvas item-7">
          <canvas id="myChart2"></canvas>
     </div>  
</div>
  

<script>
     const ctx1 = document.getElementById('myChart1').getContext('2d');
     const ctx2 = document.getElementById('myChart2').getContext('2d');

     // ตัวอย่างข้อมูลจำลองแยกตามอุปกรณ์
     const sampleData = {
          1: { // Device 1
               voltage: [230, 231, 232, 233, 229, 228, 230],
               current: [10, 9.5, 8.8, 9, 9.2, 9.3, 9.1],
               power:   [1.2, 1.3, 1.4, 1.5, 1.1, 1.0, 1.2]
          },
          2: { // Device 2
               voltage: [220, 219, 222, 221, 223, 224, 222],
               current: [8.5, 8.6, 8.8, 9.0, 9.1, 8.9, 9.0],
               power:   [1.0, 1.1, 1.2, 1.3, 1.0, 1.2, 1.1]
          }
     };

     const labels = ['January', 'February', 'March', 'April', 'May', 'June', 'July']; // หรือ Utils.months() ก็ได้ถ้ามี
     const data = {
          labels: labels,
          datasets: [{
               label: 'My First Dataset',
               data: [],
               fill: false,
               backgroundColor: 'chartreuse',
               tension: 0.1
          }]
     };

     // แล้วค่อยประกาศ config
     const config = {
          type: 'bar',
          data: data,
          options: {
               maintainAspectRatio: false,
               plugins: {
                    legend: {
                         display: false // << ปิด legend
                    }
               },
               scales: {
                    x: {
                         ticks: { // ← ต้องเป็น ticks
                              color: 'chartreuse', // สีตัวอักษรแกน x
                              font: {
                                   size: 17
                              }
                         },
                         grid: {
                              color: 'rgba(255, 255, 255, 0.7)',
                              lineWidth: 3
                         }
                    },
                    y: {
                         ticks: { // ← ต้องเป็น ticks
                              color: 'chartreuse', // สีตัวอักษรแกน y
                              font: {
                                   size: 17
                              }
                         },
                         grid: {
                              color: 'rgba(255, 255, 255, 0.7)'
                         }
                    }
               }
          }
     };

     // สร้างกราฟ
     const chart1 = new Chart(ctx1, config);
     const chart2 = new Chart(ctx2, config);

     document.querySelectorAll('.grid-item[data-type][data-device]').forEach(el => {
          el.style.cursor = 'pointer';
          el.addEventListener('click', () => {
               const type = el.dataset.type;
               const device = el.dataset.device;

               const data = sampleData[device]?.[type] || [];

               // อัปเดตกราฟแยกตามอุปกรณ์
               const chart = device === '1' ? chart1 : chart2;
               chart.data.datasets[0].data = data;
               chart.data.datasets[0].label = `${type.toUpperCase()} (Device ${device})`;
               chart.update();

               // ลบคลาส active เฉพาะในกลุ่ม device นั้น
               const deviceContainer = el.closest('.device');
               deviceContainer.querySelectorAll('.grid-item').forEach(item => {
                    item.classList.remove('active');
               });

               // เพิ่ม active ให้ item ที่คลิก
               const parentItem = el.closest('.grid-item');
               if (parentItem) {
                    parentItem.classList.add('active');
               }
          });
     });
</script>