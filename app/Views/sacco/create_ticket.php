<?= $this->extend('layouts/sasra_layout') ?>
<?= $this->section('content') ?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-lg border-0">
            <div class="card-header p-4 text-white" style="background: var(--sasra-navy);">
                <h4 class="mb-0 fw-bold"><i class="fas fa-paper-plane me-2"></i> Raise RBSS Operational Issue</h4>
                <small class="opacity-75">Submit requests for approvals or technical support to SASRA.</small>
            </div>
            <div class="card-body p-4 bg-white">
                <form action="<?= base_url('sacco/tickets/create') ?>" method="post" enctype="multipart/form-data">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Select Category (RBSS Activity)</label>
                        <select name="category_id" class="form-select border-2" required>
                            <option value="" selected disabled>Choose category...</option>
                            <?php foreach($categories as $cat): ?>
                                <option value="<?= $cat->id ?>"><?= esc($cat->name) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Subject / Summary</label>
                        <input type="text" name="subject" class="form-control border-2" placeholder="Brief summary of the issue" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Detailed Description</label>
                        <textarea name="description" class="form-control border-2" rows="5" placeholder="Describe the request or error encountered..." required></textarea>
                    </div>

                    <div class="mb-4 p-3 rounded" style="background: #f8f9fa; border: 1px dashed var(--sasra-gold);">
                        <label class="form-label fw-bold text-dark"><i class="fas fa-camera me-2"></i> Attach Screenshot (Optional)</label>
                        <input type="file" name="screenshot" class="form-control">
                        <div class="form-text mt-2 small">Allowed: JPG, PNG | Max Size: 2MB</div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-lg fw-bold text-white shadow" style="background: var(--sasra-navy);">Submit Issue to Authority</button>
                        <a href="<?= base_url('sacco/dashboard') ?>" class="btn btn-link text-muted">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>