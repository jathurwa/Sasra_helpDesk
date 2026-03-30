<?= $this->extend('layouts/sasra_layout') ?>
<?= $this->section('content') ?>

<div class="row mb-4">
    <div class="col">
        <h3 class="fw-bold" style="color: var(--sasra-navy);"><i class="fas fa-tasks me-2 text-primary"></i> Manage RBSS Operational Issues</h3>
        <p class="text-muted small">Process approvals, errors, and license renewals submitted by SACCOs.</p>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-dark">
                <tr>
                    <th class="ps-4">Ticket ID</th>
                    <th>SACCO / User</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Date Received</th>
                    <th class="text-end pe-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($tickets as $t): ?>
                <tr>
                    <td class="ps-4 text-muted">#<?= $t['id'] ?></td>
                    <td class="fw-bold"><?= $t['username'] ?></td>
                    <!--<td class="fw-bold"><?= $t['user_id'] ?> (ID)</td>-->
                    <td><span class="badge bg-info text-dark shadow-sm"><?= $t['cat_name'] ?></span></td>
                    <td>
                        <?php 
                            $statusClass = 'bg-danger'; // Received
                            if($t['status'] == 'In Progress') $statusClass = 'bg-warning text-dark';
                            if($t['status'] == 'Closed') $statusClass = 'bg-success';
                        ?>
                        <span class="badge rounded-pill <?= $statusClass ?>"><?= $t['status'] ?></span>
                    </td>
                    <td class="small"><?= date('M d, Y', strtotime($t['created_at'])) ?></td>
                    <td class="text-end pe-4">
                        <a href="<?= base_url('admin/tickets/view/' . $t['id']) ?>" class="btn btn-primary btn-sm shadow-sm">
                            <i class="fas fa-edit me-1"></i> Review Issue
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>