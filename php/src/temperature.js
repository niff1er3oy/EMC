function initTemperaturePage() {
    let chart1, chart2;
    let autoRefreshInterval = null;

    function createCharts(labels, temp, hum, pond1, pond2, pond3) {
      const ctx1 = document.getElementById('chart1').getContext('2d');
      const ctx2 = document.getElementById('chart2').getContext('2d');

      chart1 = new Chart(ctx1, {
        type: 'line',
        data: {
          labels: labels,
          datasets: [
            { label: 'Temp (°C)', data: temp, borderColor: 'red', fill: false },
            { label: 'Humidity (%)', data: hum, borderColor: 'blue', fill: false }
          ]
        },
        options: {
          plugins: { title: { display: true, text: 'Temperature & Humidity Over Time' } },
          scales: {
            y: { title: { display: true, text: 'Value' } }
          }
        }
      });

      chart2 = new Chart(ctx2, {
        type: 'line',
        data: {
          labels: labels,
          datasets: [
            { label: 'Pond1 Temp (°C)', data: pond1, borderColor: 'green', fill: false },
            { label: 'Pond2 Temp (°C)', data: pond2, borderColor: 'orange', fill: false },
            { label: 'Pond3 Temp (°C)', data: pond3, borderColor: 'purple', fill: false }
          ]
        },
        options: {
          plugins: { title: { display: true, text: 'Pond Temperatures Comparison' } },
          scales: {
            y: { title: { display: true, text: 'Temperature (°C)' } }
          }
        }
      });
    }

    function updateCharts(labels, temp, hum, pond1, pond2, pond3) {
      chart1.data.labels = labels;
      chart1.data.datasets[0].data = temp;
      chart1.data.datasets[1].data = hum;
      chart1.update();

      chart2.data.labels = labels;
      chart2.data.datasets[0].data = pond1;
      chart2.data.datasets[1].data = pond2;
      chart2.data.datasets[2].data = pond3;
      chart2.update();
    }

function fetchData(mode = 'latest') {
  fetch('temp_get_data.php?mode=' + mode)
    .then(res => res.json())
    .then(data => {
      const labels = data.map(d => d.label);
      const temp = data.map(d => parseFloat(d.temp));
      const hum = data.map(d => parseFloat(d.hum));
      const pond1 = data.map(d => parseFloat(d.Pond1_Temp));
      const pond2 = data.map(d => parseFloat(d.Pond2_Temp));
      const pond3 = data.map(d => parseFloat(d.Pond3_Temp));

      if (!chart1 || !chart2) {
        createCharts(labels, temp, hum, pond1, pond2, pond3);
      } else {
        updateCharts(labels, temp, hum, pond1, pond2, pond3);
      }

      // แสดงค่าใหม่ล่าสุด พร้อมตรวจสอบว่าเป็นตัวเลขหรือไม่
      const latest = data[data.length - 1];
      document.getElementById('latest').innerHTML = `
  <strong>ช่วงเวลา:</strong> ${latest.label}<br>
  🌡️ Temp: ${isNaN(parseFloat(latest.temp)) ? 'N/A' : parseFloat(latest.temp).toFixed(2)}°C | 
  💧 Hum: ${isNaN(parseFloat(latest.hum)) ? 'N/A' : parseFloat(latest.hum).toFixed(2)}%<br>
  🐟 Pond1: ${isNaN(parseFloat(latest.Pond1_Temp)) ? 'N/A' : parseFloat(latest.Pond1_Temp).toFixed(2)}°C | 
  Pond2: ${isNaN(parseFloat(latest.Pond2_Temp)) ? 'N/A' : parseFloat(latest.Pond2_Temp).toFixed(2)}°C | 
  Pond3: ${isNaN(parseFloat(latest.Pond3_Temp)) ? 'N/A' : parseFloat(latest.Pond3_Temp).toFixed(2)}°C
`;

    });
}


    document.getElementById('modeSelector').addEventListener('change', (e) => {
      const mode = e.target.value;
      fetchData(mode);
      if (autoRefreshInterval) clearInterval(autoRefreshInterval);
      if (mode === 'latest') {
        autoRefreshInterval = setInterval(() => fetchData('latest'), 5000);
      }
    });

    function downloadCSV() {
      window.location.href = 'temp_download_all.php';
    }

    // เริ่มต้นโหลดล่าสุด
    fetchData('latest');
    autoRefreshInterval = setInterval(() => fetchData('latest'), 5000);
}