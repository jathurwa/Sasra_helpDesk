<?= $this->extend('layouts/sasra_layout') ?>
<?= $this->section('content') ?>

<div class="container-fluid">
    <div class="mb-3">
        <a href="<?= base_url('sacco/dashboard') ?>" class="btn btn-sm btn-outline-secondary"><i class="fas fa-arrow-left"></i> Back to History</a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header text-white" style="background: var(--sasra-navy);">
                    <h5 class="mb-0">Ref #<?= $ticket['id'] ?> | Category: <?= $ticket['cat_name'] ?></h5>
                </div>
                <div class="card-body">
                    <h4 class="fw-bold text-dark mb-3"><?= esc($ticket['subject']) ?></h4>
                    <p class="p-3 bg-light rounded border-start border-5 border-warning"><?= nl2br(esc($ticket['description'])) ?></p>

                    <?php if (!empty($ticket['screenshot'])): ?>
                        <div class="mt-4 p-3 border rounded bg-light">
                            <h6 class="fw-bold small"><i class="fas fa-image me-1"></i> Attached Screenshot:</h6>
                            <a href="<?= base_url('uploads/tickets/' . $ticket['screenshot']) ?>" target="_blank">
                                <img src="<?= base_url('uploads/tickets/' . $ticket['screenshot']) ?>" class="img-thumbnail" style="max-width: 200px;">
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <h5 class="fw-bold mb-3"><i class="fas fa-comments me-2 text-primary"></i>Correspondence History</h5>
            <div class="mb-5">
                <?php foreach($replies as $r): ?>
                    <div class="card mb-2 border-0 shadow-sm <?= $r->uid == auth()->id() ? 'ms-5 border-end border-4 border-warning' : 'me-5 border-start border-4 border-primary bg-light' ?>">
                        <div class="card-body py-2">
                            <small class="fw-bold"><?= $r->username ?> (<?= $r->uid == auth()->id() ? 'You' : 'SASRA Official' ?>)</small>
                            <small class="text-muted float-end"><?= date('d M, H:i', strtotime($r->created_at)) ?></small>
                            <p class="mb-0 mt-1 small"><?= nl2br(esc($r->message)) ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm sticky-top" style="top: 20px;">
                <div class="card-header fw-bold text-white" style="background: var(--sasra-gold);">Official Feedback</div>
                <div class="card-body">
                    <p class="small text-dark fw-bold mb-1">Status: <?= $ticket['status'] ?></p>
                    <hr>
                    <p class="text-muted"><?= $ticket['admin_comment'] ?? 'The Authority is currently reviewing your request. Please wait for an update.' ?></p>
                </div>
                <?php if($ticket['status'] != 'Closed'): ?>
                <div class="card-footer bg-white border-top">
                    <form action="<?= base_url('sacco/tickets/reply') ?>" method="post">
                        <?= csrf_field() ?>
                        <input type="hidden" name="ticket_id" value="<?= $ticket['id'] ?>">
                        <textarea name="message" class="form-control mb-2" rows="3" placeholder="Message to Authority..." required></textarea>
                        <button type="submit" class="btn btn-sm btn-sasra w-100">Send Response</button>
                    </form>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>