<div class="qua">
    <div class="grid card">

    </div>
    <div class="grid card">

    </div>
    <div class="grid canva">
        <canvas id="myChart"></canvas>
    </div>
</div>

<script>
    const ctx = document.getElementById('myChart').getContext('2d');

    const labels = ['January', 'February', 'March', 'April', 'May', 'June', 'July']; // หรือ Utils.months() ก็ได้ถ้ามี
    const data = {
        labels: labels,
        datasets: [{
            label: 'My First Dataset',
            data: [230, 231, 232, 233, 229, 228, 230],
            fill: false,
            backgroundColor: 'rgb(44, 44, 44)',
            borderWidth: 5,
            borderColor: 'rgb(44, 44, 44)',
            tension: 0.1
            
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
                            color: 'rgb(44, 44, 44)', // สีตัวอักษรแกน x
                            font: {
                                size: 30
                            }
                        },
                        grid: {
                            color: 'chartreuse',
                            lineWidth: 3
                        }
                },
                y: {
                        ticks: { // ← ต้องเป็น ticks
                            color: 'rgb(44, 44, 44)', // สีตัวอักษรแกน y
                            font: {
                                size: 30
                            }
                        },
                        grid: {
                            color: 'chartreuse'
                        }
                }
            }
        }
    };

    // สร้างกราฟ
    new Chart(ctx, config);
</script>