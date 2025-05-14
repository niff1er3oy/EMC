function initQualityPage() {
     console.log('JS ทำงานแล้ว!');
     const ctx = document.getElementById('myChart').getContext('2d');

     const labels = ['January', 'February', 'March', 'April', 'May', 'June', 'July']; // หรือ Utils.months() ก็ได้ถ้ามี
     const data = {
         labels: labels,
         datasets: [{
             label: 'My First Dataset',
             data: [230, 231, 232, 233, 229, 228, 230],
             fill: false,
             hoverBackgroundColor: 'chartreuse',
             borderWidth: 2,
             borderColor: 'chartreuse',
             pointRadius: 7,
             pointHoverRadius: 12,
             backgroundColor: 'rgba(255, 255, 255)',
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

     let chartConfig = {
     type: 'gauge',
     globals: {
     fontSize: '15px',
     },
     plot: {
     tooltip: {
          borderRadius: '100px',
     },
     valueBox: {
          text: '%v',
          fontSize: '35px',
          placement: 'center',
          rules: [
               {
                    text: '<div style="color:#E53935; font-weight:bold">%v</div><br><span style="color:#E53935">Acidic</span>',
                    rule: '%v < 7'
               },
               {
                    text: '<div style="color:#43A047; font-weight:bold">%v</div><br><span style="color:#43A047">Neutral</span>',
                    rule: '%v == 7'
               },
               {
                    text: '<div style="color:#1E88E5; font-weight:bold">%v</div><br><span style="color:#1E88E5">Alkaline</span>',
                    rule: '%v > 7'
               }
          ],
     },
     size: '100%',
     },
     plotarea: {
     margin: '0px',
     },
     scaleR: {
     aperture: 270,
     center: { visible: false },
     item: {
          offsetR: 0,
     },
     labels: ['', '', '', '', '', '', '', '', '', '', '', '', '', '', ''],
     minValue: 0,
     maxValue: 14,
     ring: {
          rules: [
          { backgroundColor: '#E53935', rule: '%v < 7' }, // Acidic
          { backgroundColor: '#43A047', rule: '%v == 7' }, // Neutral
          { backgroundColor: '#1E88E5', rule: '%v > 7' }, // Alkaline
          ],
          size: '10px',
     },
     step: 1,
     tick: { visible: false },
     },
     refresh: {
     type: 'feed',
     url: 'feed()',
     interval: 1500,
     resetTimeout: 1000,
     transport: 'js',
     },
     series: [
     {
          values: [8], // ตัวอย่างค่า pH
          animation: {
          effect: 'ANIMATION_EXPAND_VERTICAL',
          method: 'ANIMATION_BACK_EASE_OUT',
          sequence: 'null',
          speed: 900,
          },
          backgroundColor: 'black',
          indicator: [0, 0, 5, 5, 0.9],
     },
     ],
     };

     new zingchart.render({
     id: 'myGauges',
     data: chartConfig,
     height: '100%',
     width: '100%',
     });
}