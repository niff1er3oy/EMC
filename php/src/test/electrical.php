<link rel="stylesheet" href="css/electrical.css">

<div class="device" id="device-1">
     <div class="grid-title item-title-1">
          <div class="title">Device Name</div>
          <span class="value">Water pump <i class="fa-solid fa-water"></i></span>
     </div>
     <div class="grid-item" data-type="voltage" data-device="1">
          <div class="title">Voltage</div>
          <div class="value-container">
               <span class="value">0</span>
               <span class="unit">V</span>
          </div>
     </div>
     <div class="grid-item" data-type="current" data-device="1">
          <div class="title">Current</div>
          <div class="value-container">
               <span class="value">0</span>
               <span class="unit">A</span>
          </div>
     </div>
     <div class="grid-item" data-type="a-power" data-device="1">
          <div class="title">Active Power</div>
          <div class="value-container">
               <span class="value">0</span>
               <span class="unit">kW</span>
          </div>
     </div> 
     <div class="grid-item" data-type="r-power" data-device="1">
          <div class="title">Reactive Power</div>
          <div class="value-container">
               <span class="value">0</span>
               <span class="unit">kVAR</span>
          </div>
     </div>
     <div class="grid-item" data-type="app-power" data-device="1">
          <div class="title">Apparent Power</div>
          <div class="value-container">
               <span class="value">0</span>
               <span class="unit">kVA</span>
          </div>
     </div>
     <div class="grid-item" data-type="power-f" data-device="1">
          <div class="title">Power Factor</div>
          <div class="value-container">
               <span class="value">0</span>
          </div>
     </div>  
     <div class="grid-item" data-type="f" data-device="1">
          <div class="title">Frequency</div>
          <div class="value-container">
               <span class="value">0</span>
               <span class="unit">Hz</span>
          </div>
     </div>
     <div class="grid-item" data-type="t-a-e" data-device="1">
          <div class="title">Total Active Energy</div>
          <div class="value-container">
               <span class="value">0</span>
               <span class="unit">kWh</span>
          </div>
     </div>
     <div class="grid-chart item-3">
          <div class="mode-buttons" data-device="1">
               <div class="sort-label">Sort by</div>
               <div class="button-group">
                    <button data-mode="latest" class="active"><i class="fas fa-clock"></i> Latest</button>
                    <button data-mode="week"><i class="fas fa-calendar-week"></i> Week</button>
                    <button data-mode="month"><i class="fas fa-calendar-alt"></i> Month</button>
                    <button data-mode="year"><i class="fas fa-calendar"></i> Year</button>
               </div>
               <button class="download" data-device="1">Download</button>
          </div>
          <div class="mycanvas">
               <canvas id="myChart1"></canvas>
          </div>
     </div>
</div>
<div class="device" id="device-2">
     <div class="grid-title item-title-2">
          <div class="title">Device Name</div>
          <span class="value">Air pump <i class="fa-solid fa-wind"></i></span>
     </div>
     <div class="grid-item" data-type="voltage" data-device="2">
          <div class="title">Voltage</div>
          <div class="value-container">
               <span class="value">0</span>
               <span class="unit">V</span>
          </div>
     </div>
     <div class="grid-item" data-type="current" data-device="2">
          <div class="title">Current</div>
          <div class="value-container">
               <span class="value">0</span>
               <span class="unit">A</span>
          </div>
     </div>
     <div class="grid-item" data-type="a-power" data-device="2">
          <div class="title">Active Power</div>
          <div class="value-container">
               <span class="value">0</span>
               <span class="unit">kW</span>
          </div>
     </div> 
     <div class="grid-item" data-type="r-power" data-device="2">
          <div class="title">Reactive Power</div>
          <div class="value-container">
               <span class="value">0</span>
               <span class="unit">kVAR</span>
          </div>
     </div>
     <div class="grid-item" data-type="app-power" data-device="2">
          <div class="title">Apparent Power</div>
          <div class="value-container">
               <span class="value">0</span>
               <span class="unit">kVA</span>
          </div>
     </div>
     <div class="grid-item" data-type="power-f" data-device="2">
          <div class="title">Power Factor</div>
          <div class="value-container">
               <span class="value">0</span>
          </div>
     </div>  
     <div class="grid-item" data-type="f" data-device="2">
          <div class="title">Frequency</div>
          <div class="value-container">
               <span class="value">0</span>
               <span class="unit">Hz</span>
          </div>
     </div>
     <div class="grid-item" data-type="t-a-e" data-device="2">
          <div class="title">Total Active Energy</div>
          <div class="value-container">
               <span class="value">0</span>
               <span class="unit">kWh</span>
          </div>
     </div>
     <div class="grid-chart item-7">
          <div class="mode-buttons" data-device="2">
               <div class="sort-label">Sort by</div>
               <div class="button-group">
                    <button data-mode="latest" class="active"><i class="fas fa-clock"></i> Latest</button>
                    <button data-mode="week"><i class="fas fa-calendar-week"></i> Week</button>
                    <button data-mode="month"><i class="fas fa-calendar-alt"></i> Month</button>
                    <button data-mode="year"><i class="fas fa-calendar"></i> Year</button>
               </div>
               <button class="download" data-device="2">Download</button>
          </div>
          <div class="mycanvas">
               <canvas id="myChart2"></canvas>
          </div>
     </div>  
</div>