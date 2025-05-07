function initElectricalPage() {
     console.log('JS ทำงานแล้ว!');
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
               hoverBackgroundColor: 'rgba(255, 255, 255, 0.9)',
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
                              color: 'rgba(128, 255, 0, 0.5)',
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
                              color: 'rgba(128, 255, 0, 0.5)'
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
}