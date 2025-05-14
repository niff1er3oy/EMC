<style>
    h2 { margin-bottom: 10px; }
    .info-box { background: #fff; padding: 15px; border-left: 5px solid #007bff; margin-bottom: 20px; }
    canvas { margin-top: 20px; background: #fff; padding: 10px; border-radius: 8px; }
    select, button { margin-left: 10px; padding: 5px 10px; }
  </style>
<h2>🌡️ Water Temperature Dashboard</h2>

  <div id="latest" class="info-box">Loading...</div>

  <div style="margin-bottom: 15px;">
    <label>เลือกช่วงเวลา: </label>
    <select id="modeSelector">
      <option value="latest">ล่าสุด</option>
      <option value="day">วัน</option>
      <option value="month">เดือน</option>
      <option value="year">ปี</option>
    </select>
    <button onclick="downloadCSV()">📥 ดาวน์โหลดข้อมูลทั้งหมด</button>
  </div>

  <canvas id="chart1" width="800" height="300"></canvas>
  <canvas id="chart2" width="800" height="300"></canvas>
