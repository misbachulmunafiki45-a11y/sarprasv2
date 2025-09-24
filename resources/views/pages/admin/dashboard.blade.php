@extends ('layouts.admin')

@section('content')

<h1>Dashboard Admin</h1>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title">Total Kategori Laporan</h6>
                <div class="d-flex align-items-center flex-column flex-md-row" style="gap:4px;">
                  <div class="legend-right mt-3 mt-md-0" style="margin-right:0;">
                    <div id="legendCategories" class="custom-legend"></div>
                  </div>
                  <div class="chart-wrap-lg" style="height:240px; flex: 0 0 auto;">
                    <canvas id="chartCategories"></canvas>
                  </div>
                 </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-12 col-md-6">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title">Total Laporan</h6>
                <div class="dark-chart-box" style="height:220px">
                  <canvas id="chartReports"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-6">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title">Total Pelapor</h6>
                <div class="dark-chart-box residents-chart-flex" style="display:flex;align-items:center;gap:0;">
                  <div id="residentsLegend" style="color:#ffffff;font-size:12px;margin-right:0;line-height:1.3;"></div>
                  <div class="chart-wrap-lg" style="height:240px; flex: 0 0 auto;">
                    <canvas id="chartResidents"></canvas>
                  </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<style>
/* Custom legend style mirip contoh */
.custom-legend ul { list-style: none; margin: 0; padding: 0; }
.custom-legend li { display: flex; align-items: center; margin-bottom: 8px; color: #4a4a4a; font-size: 0.9rem; }
.custom-legend .swatch { width: 14px; height: 14px; border-radius: 50%; margin-right: 8px; display: inline-block; }
/* Batasi ukuran canvas donut & legend agar layout rapi */
.chart-wrap { width: 100%; max-width: 220px; margin: 0 auto; }
.chart-wrap-lg { width: 100%; max-width: 240px; margin: 0 auto; }
.legend-right { max-height: 240px; overflow-y: auto; }
@media (max-width: 768px){
  .legend-right { width: 100%; }
}
/* Theme gelap untuk chart Total Laporan */
.dark-chart-box{ background: radial-gradient(ellipse at center, #2b2b2b 0%, #0f0f0f 70%); border-radius: 8px; padding: 12px; }
.dark-chart-caption .dark-title{ color:#fff; font-weight:700; text-transform:uppercase; }
.dark-chart-caption .dark-year{ color:#ddd; font-weight:600; }
.dark-chart-caption .dark-source{ color:#bbb; font-size:0.9rem; }
</style>
<script>
(function(){
  var categoryCount = {{ \App\Models\ReportCategory::count() }};
  var reportCount   = {{ \App\Models\Report::count() }};
  var residentCount = {{ \App\Models\Resident::count() }};

  function makeSingleBarChart(canvasId, value, color){
    var ctx = document.getElementById(canvasId).getContext('2d');
    return new Chart(ctx, {
      type: 'bar',
      data: {
        labels: ['Jumlah'],
        datasets: [{
          data: [value],
          backgroundColor: [color],
          borderColor: [color],
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          yAxes: [{
            display: false,
            ticks: { beginAtZero: true, suggestedMax: Math.max(value, 5), precision: 0 }
          }],
          xAxes: [{ display: false }]
        },
        legend: { display: false },
        tooltips: { enabled: true }
      }
    });
  }

  // Donut chart untuk Total Kategori Laporan (distribusi laporan per kategori) dengan custom legend
  @php
    $categories = \App\Models\ReportCategory::withCount('reports')->get();
    $categoryLabels = $categories->pluck('name');
    $categoryCounts = $categories->pluck('reports_count');
  @endphp
  var pieLabels = {!! json_encode($categoryLabels) !!};
  var pieData   = {!! json_encode($categoryCounts) !!};
  var pieColors = ['#16a7ff','#0bd3d3','#36b9cc','#f6c23e','#e74a3b','#858796','#20c997','#6610f2','#fd7e14','#6f42c1'];
  var pieBg     = pieLabels.map(function(_, i){ return pieColors[i % pieColors.length]; });

  // Fallback jika semua nilai 0
  var pieTotal = pieData.reduce(function(a,b){return a+Number(b)},0);
  if(pieTotal === 0){
    pieLabels = ['Belum ada data'];
    pieData = [1];
    pieBg = ['#e0e0e0'];
  }

  var ctxCat = document.getElementById('chartCategories').getContext('2d');
  var categoryChart = new Chart(ctxCat, {
    type: 'doughnut',
    data: {
      labels: pieLabels,
      datasets: [{
        data: pieData,
        backgroundColor: pieBg,
        borderWidth: 0
      }]
    },
    options: {
      cutoutPercentage: 70, // buat ring tebal (donut)
      responsive: true,
      maintainAspectRatio: false,
      legend: { display: false },
      tooltips: {
        callbacks: {
          label: function(tooltipItem, data){
            var label = data.labels[tooltipItem.index] || '';
            var val = data.datasets[0].data[tooltipItem.index] || 0;
            var total = data.datasets[0].data.reduce(function(a,b){return a+Number(b)},0);
            var pct = total ? (val/total*100).toFixed(1) : 0;
            return label + ': ' + val + ' (' + pct + '%)';
          }
        }
      }
    }
  });

  // Render custom legend di sisi kanan
  var legendHtml = '<ul class="legend-list">' + pieLabels.map(function(label, i){
    return '<li><span class="swatch" style="background:'+ pieBg[i] +'"></span>'+ label +'</li>'; 
  }).join('') + '</ul>';
  document.getElementById('legendCategories').innerHTML = legendHtml;

  // Chart Total Laporan: bar gelap sesuai contoh (persis warna dan label persen)
  var valueLabelPlugin = {
    afterDatasetsDraw: function(chart){
      var ctx = chart.ctx;
      var dataset = chart.data.datasets[0];
      var meta = chart.getDatasetMeta(0);
      ctx.save();
      ctx.fillStyle = '#ffffff';
      ctx.textAlign = 'center';
      ctx.font = 'bold 12px sans-serif';
      meta.data.forEach(function(bar, i){
        var val = dataset.data[i];
        var x = bar._model.x;
        var y = bar._model.y - 10;
        ctx.fillText(val, x, y);
      });
      ctx.restore();
    }
  };
  @php
    $start4 = \Carbon\Carbon::now()->subMonths(3)->startOfMonth();
    $months4 = [];
    for ($i = 0; $i < 4; $i++) {
      $dt = \Carbon\Carbon::now()->subMonths(3 - $i)->startOfMonth();
      $months4[] = ['key' => $dt->format('Y-m'), 'label' => $dt->format('M Y')];
    }
    $countsByMonth4 = \App\Models\Report::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as ym, COUNT(*) as c')
      ->where('created_at', '>=', $start4)
      ->groupBy('ym')
      ->pluck('c', 'ym')
      ->toArray();
    $labels4 = array_column($months4, 'label');
    $counts4 = array_map(function($m) use ($countsByMonth4){ return $countsByMonth4[$m['key']] ?? 0; }, $months4);
    $total4 = array_sum($counts4);
    $perc4 = $total4 > 0 ? array_map(function($v) use ($total4){ return round($v / $total4 * 100); }, $counts4) : [0,0,0,0];
  @endphp
  var reportLabels = {!! json_encode($labels4) !!};
  var reportData   = {!! json_encode($counts4) !!};
  var maxCount = reportData.length ? Math.max.apply(null, reportData) : 0;
  var suggestedMax = Math.max(5, maxCount + Math.ceil(maxCount * 0.1));

  var ctxRep = document.getElementById('chartReports').getContext('2d');
  var chartReports = new Chart(ctxRep, {
    type: 'bar',
    data: {
      labels: reportLabels,
      datasets: [{
        data: reportData,
        backgroundColor: ['#29b6f6','#1565c0','#76ff03','#2e7d32'],
        borderWidth: 0,
        categoryPercentage: 0.6,
        barPercentage: 0.8
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      legend: { display: false },
      tooltips: {
        callbacks: {
          label: function(tooltipItem){ return tooltipItem.yLabel; }
        }
      },
      scales: {
        xAxes: [{
          gridLines: { display: false },
          ticks: { fontColor: '#ffffff' }
        }],
        yAxes: [{
          ticks: {
            beginAtZero: true,
            min: 0,
            max: 50,
            stepSize: 10,
            callback: function(value){ return value === 0 ? '' : value; },
            fontColor: '#ffffff'
           },
           gridLines: { color: 'rgba(255,255,255,0.15)' }
         }]
      }
    },
    plugins: [valueLabelPlugin]
  });
  // Chart Pelapor: pie chart berdasarkan Top 10 Pelapor (jumlah laporan per pelapor)
  @php
    $topResidents = \App\Models\Resident::with('user')
      ->withCount('reports')
      ->orderBy('reports_count','desc')
      ->limit(10)
      ->get();
    $residentLabels = $topResidents->map(function($r){
      return optional($r->user)->name ?: ('Pelapor #' . $r->id);
    });
    $residentCounts = $topResidents->pluck('reports_count');
  @endphp
  var resLabels = {!! json_encode($residentLabels) !!};
  var resData   = {!! json_encode($residentCounts) !!};
  var resColors = ['#29b6f6','#1565c0','#76ff03','#2e7d32','#f6c23e','#e74a3b','#858796','#20c997','#6610f2','#fd7e14'];
  var resBg     = resLabels.map(function(_, i){ return resColors[i % resColors.length]; });

  // Fallback jika tidak ada data (semua nol)
  var resTotal = resData.reduce(function(a,b){return a+Number(b)},0);
  if(resTotal === 0){
    resLabels = ['Belum ada pelapor dengan laporan'];
    resData = [1];
    resBg = ['#e0e0e0'];
  }

  // Plugin untuk menampilkan nilai pada irisan pie (Total Pelapor)
  var residentLabelPlugin = {
    afterDatasetsDraw: function(chart){
      var ctx = chart.ctx;
      var dataset = chart.data.datasets[0];
      var meta = chart.getDatasetMeta(0);
      var total = dataset.data.reduce(function(a,b){return a+Number(b)},0);
      ctx.save();
      ctx.fillStyle = '#ffffff';
      ctx.textAlign = 'center';
      ctx.font = 'bold 12px sans-serif';
      meta.data.forEach(function(arc, i){
        var val = dataset.data[i];
        if(!val){ return; }
        var model = arc._model;
        var midAngle = (model.startAngle + model.endAngle) / 2;
        var r = (model.innerRadius + model.outerRadius) / 2;
        var x = model.x + r * Math.cos(midAngle);
        var y = model.y + r * Math.sin(midAngle);
        ctx.fillText(val, x, y);
      });
      ctx.restore();
    }
  };

  var ctxRes = document.getElementById('chartResidents').getContext('2d');
  var chartResidents = new Chart(ctxRes, {
    type: 'pie',
    data: {
      labels: resLabels,
      datasets: [{
        data: resData,
        backgroundColor: resBg,
        borderWidth: 0
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      layout: { padding: { left: 0, right: 0, top: 0, bottom: 0 } },
      legend: { display: false },
      legendCallback: function(chart){
         var html = ['<div>'];
         var labels = chart.data.labels || [];
         var colors = chart.data.datasets[0].backgroundColor || [];
         labels.forEach(function(lbl, i){
           var color = Array.isArray(colors) ? (colors[i] || '#ccc') : colors;
           html.push('<div style="display:flex;align-items:center;margin:1px 0;">');
           html.push('<span style="width:8px;height:8px;border-radius:50%;display:inline-block;margin-right:3px;background:'+color+';"></span>');
           html.push('<span>'+lbl+'</span>');
           html.push('</div>');
         });
         html.push('</div>');
         return html.join('');
       },
      tooltips: {
        callbacks: {
          label: function(tooltipItem, data){
            var label = data.labels[tooltipItem.index] || '';
            var val = data.datasets[0].data[tooltipItem.index] || 0;
            var total = data.datasets[0].data.reduce(function(a,b){return a+Number(b)},0);
            var pct = total ? (val/total*100).toFixed(1) : 0;
            return label + ': ' + val + ' laporan (' + pct + '%)';
          }
        }
      }
    },
    plugins: [residentLabelPlugin]
  });
  var legendEl = document.getElementById('residentsLegend');
  if(legendEl){ legendEl.innerHTML = chartResidents.generateLegend(); }
})();
</script>
@endsection
