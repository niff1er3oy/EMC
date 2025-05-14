function initElectricalPage() {
     function loadDeviceData() {
     fetch('get-latest-data.php')
          .then(response => response.json())
          .then(data => {
               console.log('loadDeviceData ทำงานแล้ว!');
               data.forEach(device => {
                    const id = device.Dv_ID;
                    document.querySelector(`#device-${id} [data-type="voltage"] .value`).textContent = device.Van;
                    document.querySelector(`#device-${id} [data-type="current"] .value`).textContent = device.Ia;
                    document.querySelector(`#device-${id} [data-type="a-power"] .value`).textContent = device.Pa;
                    document.querySelector(`#device-${id} [data-type="r-power"] .value`).textContent = device.Qa;
                    document.querySelector(`#device-${id} [data-type="app-power"] .value`).textContent = device.Sa;
                    document.querySelector(`#device-${id} [data-type="power-f"] .value`).textContent = device.Pfa;
                    document.querySelector(`#device-${id} [data-type="f"] .value`).textContent = device.f;
                    document.querySelector(`#device-${id} [data-type="t-a-e"] .value`).textContent = device.pkWh_all;
               });
          })
          .catch(error => {
               console.error("โหลดข้อมูลล้มเหลว", error);
          });
     }

     // เรียกเมื่อโหลดหน้า
     new loadDeviceData;
     setInterval(loadDeviceData, 50000); // โหลดข้อมูลทุก 5 วินาที

     console.log('JS ทำงานแล้ว!');
     
     const ctx1 = document.getElementById('myChart1').getContext('2d');
     const ctx2 = document.getElementById('myChart2').getContext('2d');
     
     const labels = []; // หรือ Utils.months() ก็ได้ถ้ามี
     const data = {
          labels: labels,
          datasets: [{
               label: 'My First Dataset',
               data: [],
               fill: false,
               backgroundColor: 'chartreuse',
               hoverBackgroundColor: 'rgba(255, 255, 255, 0.9)',
               tension: 0.3
          }]
     };
     
     // แล้วค่อยประกาศ config
     const config = {
          type: 'bar',
          data: data,
          options: {
               responsive: true,
               maintainAspectRatio: false,
               layout: {
                    padding: 0 // หลีกเลี่ยง padding ส่วนเกิน
               },
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
     
// state เก็บ type ที่เลือกของแต่ละ device
const selectedTypeByDevice = { '1': null, '2': null };
const selectedModeByDevice = { '1': 'latest', '2': 'latest' }; // ค่าเริ่มต้น

function updateChart(device) {
     const type = selectedTypeByDevice[device];
     const mode = selectedModeByDevice[device];
     if (!type) return;

     fetch(`get_device_data.php?device=${device}&mode=${mode}`)
          .then(res => res.json())
          .then(data => {
               const chart = device === '1' ? chart1 : chart2;
               console.log(`updateChart device=${device} &mode=${mode} &type=${type}`);
               chart.data.labels = data.map(entry => entry.label);
               chart.data.datasets[0].data = data.map(entry => entry[type]);
               chart.data.datasets[0].label = `${type.toUpperCase()} (Device ${device} - ${mode})`;
               chart.update();
          });
}

// เมื่อคลิกเลือกประเภท
document.querySelectorAll('.grid-item[data-type][data-device]').forEach(el => {
     el.style.cursor = 'pointer';
     el.addEventListener('click', () => {
          const type = el.dataset.type;
          const device = el.dataset.device;
          // ลบคลาส active จากปุ่มอื่น ๆ ที่อยู่ในกลุ่ม device เดียวกัน
          document.querySelectorAll(`.grid-item[data-device="${device}"]`).forEach(item => {
               item.classList.remove('active');
          });

          // เพิ่มคลาส active ให้กับปุ่มที่ถูกคลิก
          el.classList.add('active');

          // อัปเดตข้อความของ <div class="type" id="type-name-1">
          const typeNameEl = document.getElementById(`type-name-${device}`);
          if (typeNameEl) {
               typeNameEl.textContent = type; // หรือ el.textContent หากต้องการข้อความที่แสดงบนปุ่ม
          }

          selectedTypeByDevice[device] = type;
          updateChart(device);
          console.log(`querySelectorAll device=${device} &type=${type}`);
     });
});

// เมื่อคลิกปุ่มเลือก mode
document.querySelectorAll('.mode-buttons button').forEach(btn => {
     btn.addEventListener('click', () => {
          const device = btn.closest('.mode-buttons').dataset.device;
          const mode = btn.dataset.mode;

          selectedModeByDevice[device] = mode;
          updateChart(device);
          console.log(`querySelectorAll device=${device} &mode=${mode}`);
     });
});

// อัปเดตทุก 5 วินาที
setInterval(() => updateChart('1'), 50000);
setInterval(() => updateChart('2'), 50000);

document.querySelectorAll('.download').forEach(btn => {
    btn.addEventListener('click', () => {
        const device = btn.dataset.device;
        window.open(`export_excel.php?device=${device}`, '_blank');
    });
});

}