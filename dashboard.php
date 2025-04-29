<div class="grid-container">
     <div class="grid-item">
          <div class="title">Voltage <i class="fa-solid fa-chevron-down" style="color: #e66565;"></i></div>
          <span class="value">232</span> V
     </div>
     <div class="grid-item">
          <div class="title">Current <i class="fa-solid fa-chevron-up" style="color: #63E6BE;"></i></div>
          <span class="value">9.065</span> A
     </div>
     <div class="grid-item active">
          <div class="title">Active Power <i class="fa-solid fa-equals"></i></div>
          <span class="value">1.389</span> W
     </div> 
     <div class="grid-item">
          <div class="title">Reactive Power <i class="fa-solid fa-equals"></i></div>
          <span class="value">1.559</span> VAR
     </div>
     <div class="grid-item">
          <div class="title">Apparent Power <i class="fa-solid fa-chevron-down" style="color: #e66565;"></i></div>
          <span class="value">2.089</span> VA
     </div>
     <div class="grid-item">
          <div class="title">Power Factor <i class="fa-solid fa-chevron-up" style="color: #63E6BE;"></i></div>
          <span class="value">9.065</span>
     </div>  
     <div class="grid-item">
          <div class="title">Frequency <i class="fa-solid fa-chevron-up" style="color: #63E6BE;"></i></div>
          <span class="value">49.999</span> Hz
     </div>
     <div class="grid-item">
          <div class="title">Total Active Energy <i class="fa-solid fa-chevron-down" style="color: #e66565;"></i></div>
          <span class="value">6160</span> kWh
     </div>
     <div class="grid-item item-title">
          <div class="title">Device Name <i class="fa-solid fa-download"></i></div>
          <span class="value">jjjjjjjjj</span>
     </div>
     <div class="grid-item item-3">
     <canvas id="myChart"></canvas>
     </div>
     <div class="grid-item item-7">
     </div>
     <div class="grid-item item-8">
     </div>
</div>

<script>
const ctx = document.getElementById('myChart').getContext('2d');

const labels = ['January', 'February', 'March', 'April', 'May', 'June', 'July']; // หรือ Utils.months() ก็ได้ถ้ามี
const data = {
     labels: labels,
     datasets: [{
          label: 'My First Dataset',
          data: [65, 59, 80, 81, 56, 55, 40],
          fill: false,
          backgroundColor: 'chartreuse',
          borderColor: 'chartreuse',
          tension: 0.1,
          borderWidth: 5
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
                         color: 'rgba(255, 255, 255, 0.7)' // สีเส้นแกน x
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
                         color: 'rgba(255, 255, 255, 0.7)' // สีเส้นแกน y
                    }
               }
          }
     }
};

// สร้างกราฟ
new Chart(ctx, config);
</script>
