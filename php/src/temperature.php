<link rel="stylesheet" href="css/temperature.css">

<div class="cont">
    <div class="data">
        <div class="grid-item item" data-type="temp"><div class="label"><i class="fas fa-thermometer-half"></i> Temperature</div><div class="value"></div></div>
        <div class="grid-item item" data-type="hum"><div class="label"><i class="fas fa-tint"></i> Humidity</div><div class="value"></div></div>
        <div class="grid-item item" data-type="pond1"><div class="label"><i class="fas fa-water"></i> Pond1</div><div class="value"></div></div>
        <div class="grid-item item" data-type="pond2"><div class="label"><i class="fas fa-water"></i> Pond2</div><div class="value"></div></div>
        <div class="grid-item item" data-type="pond3"><div class="label"><i class="fas fa-water"></i> Pond3</div><div class="value"></div></div>
    </div>
    <div class="chat grid-item">
        <div class="mode-buttons">
            <div class="sort-label">Sort by</div>
            <div class="button-group">
                <button data-mode="latest" class="active"><i class="fas fa-clock"></i> Latest</button>
                <button data-mode="week"><i class="fas fa-calendar-week"></i> Week</button>
                <button data-mode="month"><i class="fas fa-calendar-alt"></i> Month</button>
                <button data-mode="year"><i class="fas fa-calendar"></i> Year</button>
            </div>
                <button class="download">Download</button>
        </div>
        <div class="chat-1">
            <div class="chat-L"><canvas id="ChartTemp"></canvas></div>
            <div class="chat-R"><canvas id="ChartHum"></canvas></div>
        </div>
        <div class="chat-2">
            <canvas id="ChartPond"></canvas>
        </div>
    </div>
</div>