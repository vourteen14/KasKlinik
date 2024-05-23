<div class="row g-4 mb-4">
  <div class="col-12 col-lg-6">
    <div class="app-card app-card-chart h-100 shadow-sm">
      <div class="app-card-header p-3">
        <div class="row justify-content-between align-items-center">
          <div class="col-auto">
            <h4 class="app-card-title">Line Chart Example</h4>
          </div>
          <div class="col-auto">
            <div class="card-header-action">
              <a href="charts.html">More charts</a>
            </div>
          </div>
        </div>
      </div>
      <div class="app-card-body p-3 p-lg-4">
        <div class="mb-3 d-flex">
          <select class="form-select form-select-sm ms-auto d-inline-flex w-auto">
            <option value="1" selected>This week</option>
            <option value="2">Today</option>
            <option value="3">This Month</option>
            <option value="3">This Year</option>
          </select>
        </div>
        <div class="chart-container">
          <canvas id="canvas-linechart"></canvas>
        </div>
      </div>
    </div>
  </div>
  <div class="col-12 col-lg-6">
    <div class="app-card app-card-chart h-100 shadow-sm">
      <div class="app-card-header p-3">
        <div class="row justify-content-between align-items-center">
          <div class="col-auto">
            <h4 class="app-card-title">Bar Chart Example</h4>
          </div>
          <div class="col-auto">
            <div class="card-header-action">
              <a href="charts.html">More charts</a>
            </div>
          </div>
        </div>
      </div>
      <div class="app-card-body p-3 p-lg-4">
        <div class="mb-3 d-flex">
          <select class="form-select form-select-sm ms-auto d-inline-flex w-auto">
            <option value="1" selected>This week</option>
            <option value="2">Today</option>
            <option value="3">This Month</option>
            <option value="3">This Year</option>
          </select>
        </div>
        <div class="chart-container">
          <canvas id="canvas-barchart"></canvas>
        </div>
      </div>
    </div>
  </div>
</div>