function initQualityPage() {
     const ctx = document.getElementById('myChart').getContext('2d');

     const labels = ['January', 'February', 'March', 'April', 'May', 'June', 'July']; // หรือ Utils.months() ก็ได้ถ้ามี
     const data = {
         labels: labels,
         datasets: [{
             label: 'My First Dataset',
             data: [230, 231, 232, 233, 229, 228, 230],
             fill: false,
             backgroundColor: 'chartreuse',
             borderWidth: 6,
             borderColor: 'chartreuse',
             pointRadius: 6,
             pointHoverRadius: 10,
             hoverBackgroundColor: 'rgba(255, 255, 255, 0.9)',
             tension: 0.3
             
         }]
     };
     
     // แล้วค่อยประกาศ config
     const config = {
         type: 'line',
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
     new Chart(ctx, config);
}