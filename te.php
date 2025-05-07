<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Device Chart Viewer</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    body {
      background-color: #111;
      color: white;
      font-family: sans-serif;
      padding: 20px;
    }
    select, input[type="date"] {
      padding: 6px 10px;
      margin: 10px 5px;
      font-size: 16px;
    }
    .controls {
      margin-bottom: 20px;
    }
    canvas {
      background: #222;
      border-radius: 10px;
      padding: 10px;
    }
  </style>
</head>
<body>

  <h2>Device Chart Viewer</h2>

  <div class="controls">
    <label>Device:</label>
    <select id="deviceSelect">
      <option value="device1">Device 1</option>
      <option value="device2">Device 2</option>
    </select>

    <label>Parameter:</label>
    <select id="paramSelect">
      <option value="voltage">Voltage (V)</option>
      <option value="current">Current (A)</option>
      <option value="power">Active Power (W)</option>
    </select>

    <label>Date:</label>
    <input type="date" id="dateInput" value="2025-05-01">
  </div>

  <canvas id="chartCanvas"></canvas>

  <script>
    const chartCtx = document.getElementById('chartCanvas').getContext('2d');

    // Dummy data
    const dummyData = {
      device1: {
        '2025-05-01': {
          voltage: [220, 222, 224, 221, 223, 225],
          current: [8.5, 8.6, 8.4, 8.7, 8.8, 8.6],
          power: [1800, 1820, 1790, 1830, 1850, 1825]
        }
      },
      device2: {
        '2025-05-01': {
          voltage: [210, 212, 213, 215, 216, 217],
          current: [7.5, 7.6, 7.7, 7.8, 7.6, 7.5],
          power: [1600, 1610, 1620, 1630, 1625, 1615]
        }
      }
    };

    const chartLabels = ['00:00', '04:00', '08:00', '12:00', '16:00', '20:00'];

    let chartInstance;

    function updateChart() {
      const device = document.getElementById('deviceSelect').value;
      const param = document.getElementById('paramSelect').value;
      const date = document.getElementById('dateInput').value;

      const values = dummyData[device]?.[date]?.[param] || [0, 0, 0, 0, 0, 0];
      const paramLabel = document.getElementById('paramSelect').selectedOptions[0].text;

      const chartData = {
        labels: chartLabels,
        datasets: [{
          label: `${paramLabel} (${device})`,
          data: values,
          fill: false,
          borderColor: 'chartreuse',
          backgroundColor: 'chartreuse',
          borderWidth: 3,
          tension: 0.2
        }]
      };

      const chartConfig = {
        type: 'line',
        data: chartData,
        options: {
          responsive: true,
          plugins: {
            legend: { labels: { color: 'white' } }
          },
          scales: {
            x: {
              ticks: { color: 'white' },
              grid: { color: 'rgba(255,255,255,0.2)' }
            },
            y: {
              ticks: { color: 'white' },
              grid: { color: 'rgba(255,255,255,0.2)' }
            }
          }
        }
      };

      if (chartInstance) chartInstance.destroy();
      chartInstance = new Chart(chartCtx, chartConfig);
    }

    document.getElementById('deviceSelect').addEventListener('change', updateChart);
    document.getElementById('paramSelect').addEventListener('change', updateChart);
    document.getElementById('dateInput').addEventListener('change', updateChart);

    updateChart(); // initial load
  </script>

</body>
</html>
