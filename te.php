<canvas id="myCanvas" width="300" height="150" style="border:1px solid #000;"></canvas>

<script>
const canvas = document.getElementById("myCanvas");
const ctx = canvas.getContext("2d");

// พารามิเตอร์ของครึ่งวงกลม
const centerX = 150;  // จุดศูนย์กลาง X
const centerY = 75;   // จุดศูนย์กลาง Y
const radius = 50;    // รัศมี
const startAngle = 0;           // มุมเริ่มต้น (0 เรเดียน)
const endAngle = Math.PI;       // มุมสิ้นสุด (PI เรเดียน = 180 องศา)

ctx.beginPath();
ctx.arc(centerX, centerY, radius, startAngle, endAngle, false); // false = วาดตามเข็มนาฬิกา
ctx.lineTo(centerX, centerY);  // วาดเส้นกลับไปยังจุดศูนย์กลาง
ctx.closePath();

ctx.fillStyle = "#4CAF50";
ctx.fill();
ctx.stroke();
</script>
