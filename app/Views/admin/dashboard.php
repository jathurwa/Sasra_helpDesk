<?= $this->extend('layouts/sasra_layout') ?>
<?= $this->section('content') ?>


<!-- Top Row: Chart and Live Totals -->
<div class="row g-4 mb-4">
    <div class="col-md-8">
        <div class="card p-4 border-0 shadow-sm h-100">
            <h5 class="fw-bold border-bottom pb-2">RBSS Operational Workflow Chart</h5>
            <canvas id="rbssChart" height="150"></canvas>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card p-4 border-0 shadow-sm bg-sasra-navy text-white h-100" style="background: #0d2d5e;">
            <h5>Live Totals (Weekly)</h5>
            <hr>
            <div class="d-flex justify-content-between mb-2"><span>Pending:</span> <strong><?= $stats->pending ?? 0 ?></strong></div>
            <div class="d-flex justify-content-between mb-2"><span>In Progress:</span> <strong><?= $stats->progress ?? 0 ?></strong></div>
            <div class="d-flex justify-content-between mb-2"><span>Closed:</span> <strong><?= $stats->closed ?? 0 ?></strong></div>
        </div>
    </div>
</div>

<!-- New Row: Most Selected Issues (Rankings) -->
<div class="row g-4">
    <!-- Previous Month Rankings -->
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold text-muted"><i class="fas fa-calendar-check me-2"></i>Most Reported: Previous Month</h6>
            </div>
            <div class="card-body">
                <?php if(!empty($top_last_month)): ?>
                    <ul class="list-group list-group-flush">
                        <?php foreach($top_last_month as $index => $item): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center ps-0">
                                <span><span class="badge bg-light text-dark me-2"><?= $index + 1 ?></span> <?= esc($item['name']) ?></span>
                                <span class="badge rounded-pill bg-sasra-navy" style="background:#0d2d5e;"><?= $item['total'] ?> tickets</span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p class="text-muted small text-center py-3">No data available for last month.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Weekly Rankings (Friday to Friday) -->
    <div class="col-md-6">
        <div class="card border-0 shadow-sm border-top border-warning border-4">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold text-muted"><i class="fas fa-sync-alt me-2 text-warning"></i>Weekly Trend (Friday Update)</h6>
            </div>
            <div class="card-body">
                <?php if(!empty($top_this_week)): ?>
                    <ul class="list-group list-group-flush">
                        <?php foreach($top_this_week as $index => $item): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center ps-0">
                                <span><span class="badge bg-warning text-dark me-2">#<?= $index + 1 ?></span> <?= esc($item['name']) ?></span>
                                <span class="fw-bold" style="color:var(--sasra-gold);"><?= $item['total'] ?> Issues</span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p class="text-muted small text-center py-3">Week currently compiling... (Reset every Friday)</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const ctx = document.getElementById('rbssChart').getContext('2d');
    
    // We pull these values from the $stats variable sent by the controller
    const pendingVal = <?= $stats->pending ?? 0 ?>;
    const progressVal = <?= $stats->progress ?? 0 ?>;
    const closedVal = <?= $stats->closed ?? 0 ?>;

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Received (New)', 'In Progress', 'Closed (Resolved)'],
            datasets: [{
                label: 'RBSS Issue Volume',
                data: [pendingVal, progressVal, closedVal],
                backgroundColor: [
                    '#e74c3c', // Red for Received
                    '#f1c40f', // Gold for In Progress
                    '#27ae60'  // Green for Closed
                ],
                borderRadius: 5,
                barthickness: 40,
                maxBarThickness: 50,
                
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { font: { size: 14, weight: 'bold' } }
                },
                y: {
                    beginAtZero: true,
                    suggestedMax: 10, // Adjust based on expected volume
                    ticks: { stepSize: 1 } // Ensures we don't see 0.5 tickets
                }
                ticks: {
                    stepSize: 1,
                    font: { size: 14 }
                    
                }
            },
            plugins: {
                legend: { display: false }
            }
            tooltip:{
                backgroundColor: '#0d2d5e',
                padding: 10,
            }
        }
    });
});
</script>

<script>
    const ctx = document.getElementById('rbssChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Pending', 'In Progress', 'Closed'],
            datasets: [{
                label: 'Number of Issues',
                data: [<?= $stats->pending ?? 0 ?>, <?= $stats->progress ?? 0 ?>, <?= $stats->closed ?? 0 ?>],
                backgroundColor: ['#e74c3c', '#f1c40f', '#27ae60']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } }
        }
    });
</script>
<?= $this->endSection() ?>