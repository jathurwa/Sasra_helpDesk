<?= $this->extend('layouts/sasra_layout') ?>
<?= $this->section('content') ?>
<h4 class="fw-bold mb-4" style="color: var(--sasra-navy);">RBSS Issue Tracking History</h4>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">Ref #</th>
                    <th>Approval Category</th>
                    <th>Progress Status</th>
                    <th>Latest Authority Feedback</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($tickets as $t): ?>
                <tr>
                    <td class="ps-4 text-muted">#<?= $t['id'] ?></td>
                    <td class="fw-bold"><?= $t['cat_name'] ?></td>
                    <td>
                        <span class="badge rounded-pill <?= ($t['status'] == 'Closed') ? 'status-closed' : (($t['status'] == 'In Progress') ? 'status-progress' : 'status-received') ?>">
                            <?= $t['status'] ?>
                        </span>
                    </td>
                    <td class="small text-primary"><?= $t['admin_comment'] ?? 'Awaiting SASRA Officer assignment...' ?></td>
                    <td><a href="<?=base_url('sacco/tickets/view/' . $t['id']) ?>" class="btn btn-sm btn-outline-dark">View Thread</a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>