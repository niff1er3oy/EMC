<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>กราฟแสดงค่า f</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    body {
      font-family: 'Prompt', sans-serif;
      padding: 20px;
      background: #f7f7f7;
    }

    .controls {
      margin-bottom: 20px;
    }

    select, button {
      padding: 8px 12px;
      margin-right: 10px;
      font-size: 16px;
    }

    canvas {
      background: white;
      border: 1px solid #ccc;
      padding: 10px;
    }
  </style>
</head>
<body>

  <h2>กราฟแสดงค่า Frequency (Hz)</h2>

  <div class="controls">
    <!-- HTML Select ใน index.php -->
    <label>ช่วงเวลา:</label>
    <select id="rangeSelect">
      <option value="24h">24 ชั่วโมงล่าสุด</option>
      <option value="7d">7 วันล่าสุด</option>
      <option value="30d">30 วันล่าสุด</option>
    </select>

    <label>เฉลี่ยเป็น:</label>
    <select id="groupBySelect">
      <option value="hour">รายชั่วโมง</option>
      <option value="day">รายวัน</option>
      <option value="month">รายเดือน</option>
    </select>

    <button onclick="loadChart()">แสดงกราฟ</button>
    <button onclick="downloadExcel()">ดาวน์โหลด Excel</button>
  </div>

  <canvas id="myChart" width="1000" height="400"></canvas>

  <script>
    const ctx = document.getElementById('myChart').getContext('2d');
    const myChart = new Chart(ctx, {
      type: 'line',
      data: {
        labels: [],
        datasets: [{
          label: 'Frequency (Hz)',
          data: [],
          backgroundColor: 'rgba(54, 162, 235, 0.3)',
          borderColor: 'rgba(54, 162, 235, 1)',
          borderWidth: 2,
          tension: 0.3,
          fill: true,
          pointRadius: 2
        }]
      },
      options: {
        responsive: true,
        scales: {
          y: {
            title: { display: true, text: 'Hz' },
            beginAtZero: false
          },
          x: {
            title: { display: true, text: 'ช่วงเวลา' }
          }
        }
      }
    });

    function loadChart() {
      const range = document.getElementById('rangeSelect').value;
      const groupBy = document.getElementById('groupBySelect').value;

      fetch(`get_data.php?range=${range}&groupby=${groupBy}`)
        .then(res => res.json())
        .then(data => {
          const labels = data.map(item => item.label);
          const values = data.map(item => item.value);

          myChart.data.labels = labels;
          myChart.data.datasets[0].data = values;
          myChart.update();
        });
    }

    function downloadExcel() {
      const range = document.getElementById('rangeSelect').value;
      const groupBy = document.getElementById('groupBySelect').value;

      window.location.href = `export_excel.php?range=${range}&groupby=${groupBy}`;
    }

    // โหลดตอนเริ่ม
    loadChart();
  </script>
</body>
</html>
