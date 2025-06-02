function initTemperaturePage() {
    console.log('JS TemperaturePage!');

     function loadDeviceData() {
     fetch('api/temperature.php?action=get_latest_data')
          .then(response => response.json())
          .then(data => {
               console.log('loadDeviceData ทำงานแล้ว!');
               
               // สมมติ data เป็นอาเรย์ของ object ที่มี temp, hum, pond1, pond2, pond3
               // ถ้าอยากแสดงข้อมูลล่าสุดอันแรก ก็ใช้ data[0]
               if (data.length > 0) {
                    const latest = data[0];  // ข้อมูลล่าสุด
                    
                    document.querySelector(`[data-type="temp"] .value`).textContent = latest.temp;
                    document.querySelector(`[data-type="hum"] .value`).textContent = latest.hum;
                    document.querySelector(`[data-type="pond1"] .value`).textContent = latest.Pond1_Temp;
                    document.querySelector(`[data-type="pond2"] .value`).textContent = latest.Pond2_Temp;
                    document.querySelector(`[data-type="pond3"] .value`).textContent = latest.Pond3_Temp;
               }
          })
          .catch(error => {
               console.error("โหลดข้อมูลล้มเหลว", error);
          });
     }

     // เรียกเมื่อโหลดหน้า
     loadDeviceData();

     // โหลดข้อมูลทุก 5 วินาที
     setInterval(loadDeviceData, 5000);

    const ctx1 = document.getElementById('ChartTemp').getContext('2d');
    const ctx2 = document.getElementById('ChartHum').getContext('2d');
    const ctx3 = document.getElementById('ChartPond').getContext('2d');

    // Chart 1 - Temperature
    const chartTemp = new Chart(ctx1, {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                label: 'Temperature',
                data: [],
                fill: false,
                backgroundColor: 'rgb(255, 99, 132)',
                borderColor: 'rgba(255, 99, 132, 0.8)',
                pointRadius: 5,
                borderWidth: 3,
                tension: 0.3
            }]
        },
        options: chartOptions('Temperature')
    });

    // Chart 2 - Humidity
    const chartHum = new Chart(ctx2, {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                label: 'Humidity',
                data: [],
                fill: false,
                backgroundColor: 'rgb(54, 162, 235)',
                borderColor: 'rgba(54, 162, 235, 0.8)',
                pointRadius: 5,
                borderWidth: 3,
                tension: 0.3
            }]
        },
        options: chartOptions('Humidity')
    });

    // Chart 3 - Pond Temperatures
    const chartPond = new Chart(ctx3, {
        type: 'line',
        data: {
            labels: [],
            datasets: [
                {
                    label: 'Pond 1',
                    data: [],
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 1)',
                    tension: 0.3,
                    pointRadius: 4,
                    borderWidth: 3
                },
                {
                    label: 'Pond 2',
                    data: [],
                    borderColor: 'rgba(153, 102, 255, 1)',
                    backgroundColor: 'rgba(153, 102, 255, 1)',
                    tension: 0.3,
                    pointRadius: 4,
                    borderWidth: 3
                },
                {
                    label: 'Pond 3',
                    data: [],
                    borderColor: 'rgba(255, 159, 64, 1)',
                    backgroundColor: 'rgba(255, 159, 64, 1)',
                    tension: 0.3,
                    pointRadius: 4,
                    borderWidth: 3
                }
            ]
        },
        options: chartOptions('Pond Temperatures Comparison', true)
    });

    let selectedMode = 'latest'; // ค่าเริ่มต้น

    function updateAllCharts() {
        fetch(`api/temperature.php?action=get_device_data&mode=${selectedMode}`)
            .then(res => res.json())
            .then(data => {
                const labels = data.map(entry => entry.label);

                chartTemp.data.labels = labels;
                chartTemp.data.datasets[0].data = data.map(entry => entry.temp);
                chartTemp.update();

                chartHum.data.labels = labels;
                chartHum.data.datasets[0].data = data.map(entry => entry.hum);
                chartHum.update();

                chartPond.data.labels = labels;
                chartPond.data.datasets[0].data = data.map(entry => entry.pond1);
                chartPond.data.datasets[1].data = data.map(entry => entry.pond2);
                chartPond.data.datasets[2].data = data.map(entry => entry.pond3);
                chartPond.update();

                console.log(`อัปเดตกราฟทั้งหมด mode=${selectedMode}`);
            })
            .catch(error => {
                console.error('เกิดข้อผิดพลาดในการโหลดข้อมูล:', error);
            });
    }

    // ตั้งค่าคลิกเลือกโหมด
    document.querySelectorAll('.mode-buttons button[data-mode]').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.mode-buttons button').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            selectedMode = btn.dataset.mode;
            updateAllCharts();
        });
    });

    updateAllCharts(); // โหลดข้อมูลครั้งแรก
    setInterval(() => updateAllCharts(), 5000);
    // ฟังก์ชันสร้าง options ของ chart
    function chartOptions(title, showLegend = false) {
        return {
            responsive: true,
            maintainAspectRatio: false,
            layout: {
                padding: 0
            },
            plugins: {
                title: { display: true, text: title, font:{size: 24} },
                legend: { display: showLegend }
            },
            scales: {
                x: {
                    ticks: {
                        color: 'rgb(44, 44, 44)',
                        font: { size: 14 }
                    },
                    grid: {
                        color: 'rgba(44, 44, 44, 0.2)',
                        lineWidth: 1
                    }
                },
                y: {
                    ticks: {
                        color: 'rgb(44, 44, 44)',
                        font: { size: 14 }
                    },
                    grid: {
                        color: 'rgba(44, 44, 44, 0.2)'
                    }
                }
            }
        };
    }

    document.querySelectorAll('.download').forEach(btn => {
     btn.addEventListener('click', () => {
          const mode = selectedMode || 'latest'; // fallback ถ้าไม่เจอ
          window.open(`api/temperature.php?action=download&mode=${mode}`, '_blank');
     });
     });
}
