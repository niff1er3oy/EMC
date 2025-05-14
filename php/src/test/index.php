<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>กราฟข้อมูลกระแส (Ia)</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f2f4f8;
            color: #333;
        }

        .container {
            max-width: 900px;
            margin: 50px auto;
            background: white;
            border-radius: 16px;
            box-shadow: 0 0 20px rgba(0,0,0,0.05);
            padding: 40px;
        }

        h2 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 30px;
        }

        .controls {
            display: flex;
            justify-content: center;
            gap: 15px;
            flex-wrap: wrap;
            margin-bottom: 25px;
        }

        select, button {
            padding: 10px 15px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 16px;
            transition: 0.3s;
        }

        select:focus, button:focus {
            outline: none;
            border-color: #007bff;
        }

        button {
            background-color: #007bff;
            color: white;
            cursor: pointer;
            font-weight: bold;
        }

        button:hover {
            background-color: #0056b3;
        }

        canvas {
            max-width: 100%;
            margin-top: 30px;
        }

        footer {
            text-align: center;
            margin-top: 40px;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>กราฟแสดงข้อมูลกระแส (Ia)</h2>

        <div class="controls">
            <div>
                <label>ช่วงเวลา: </label>
                <select id="range">
                    <option value="24h">24 ชั่วโมง</option>
                    <option value="7d">7 วัน</option>
                    <option value="30d">30 วัน</option>
                    <option value="3m">3 เดือน</option>
                </select>
            </div>

            <div>
                <label>การเฉลี่ย: </label>
                <select id="avg">
                    <option value="none">ไม่เฉลี่ย</option>
                    <option value="1h">เฉลี่ยรายชั่วโมง</option>
                    <option value="1d">เฉลี่ยรายวัน</option>
                    <option value="1m">เฉลี่ยรายเดือน</option>
                </select>
            </div>

            <div>
                <button onclick="loadData()">📊 โหลดข้อมูล</button>
            </div>
        </div>

        <canvas id="chart" height="100"></canvas>
    </div>

    <footer>แสดงผลด้วย PHP + Chart.js © 2025</footer>

    <script>
        let chart;

        async function loadData() {
            const range = document.getElementById('range').value;
            const avg = document.getElementById('avg').value;

            const response = await fetch(`get_data.php?range=${range}&avg=${avg}`);
            const data = await response.json();

            const labels = data.map(item => item.datetime);
            const values = data.map(item => item.Ia ?? item.avg_Ia);

            const ctx = document.getElementById('chart').getContext('2d');
            if (chart) chart.destroy();

            chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'กระแส (Ia)',
                        data: values,
                        borderColor: '#007bff',
                        backgroundColor: 'rgba(0, 123, 255, 0.1)',
                        borderWidth: 2,
                        pointRadius: 3,
                        pointHoverRadius: 6,
                        tension: 0.3,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: true,
                            labels: {
                                color: '#333'
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: (ctx) => `Ia: ${ctx.raw.toFixed(2)}`
                            }
                        }
                    },
                    scales: {
                        x: {
                            ticks: {
                                autoSkip: true,
                                maxRotation: 45,
                                minRotation: 0,
                                color: '#666'
                            },
                            title: {
                                display: true,
                                text: 'เวลา',
                                color: '#333'
                            }
                        },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                color: '#666'
                            },
                            title: {
                                display: true,
                                text: 'Ia (A)',
                                color: '#333'
                            }
                        }
                    }
                }
            });
        }
    </script>
</body>
</html>
