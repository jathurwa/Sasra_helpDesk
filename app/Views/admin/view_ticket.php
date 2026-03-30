<?= $this->extend('layouts/sasra_layout') ?>

<?= $this->section('content') ?>
<style>
    .chat-container { height: 400px; overflow-y: auto; padding: 15px; background: #f8f9fa; border-radius: 10px; border: 1px solid #dee2e6; }
    .msg { margin-bottom: 15px; max-width: 80%; padding: 10px 15px; border-radius: 15px; font-size: 0.9rem; position: relative; }
    .msg-authority { background: var(--sasra-navy); color: white; margin-left: auto; border-bottom-right-radius: 2px; }
    .msg-sacco { background: #e9ecef; color: #333; margin-right: auto; border-bottom-left-radius: 2px; border: 1px solid #ced4da; }
    .msg-meta { font-size: 0.7rem; display: block; margin-top: 5px; opacity: 0.8; }
    .evidence-img { max-width: 100%; border-radius: 8px; cursor: pointer; transition: 0.3s; }
    .evidence-img:hover { transform: scale(1.02); }
</style>

<div class="container-fluid">
    <div class="row">
        <!-- LEFT COLUMN: Ticket details and Conversation -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-dark">Issue Reference: #<?= $ticket['id'] ?></h5>
                    <span class="badge bg-info text-dark"><?= esc($ticket['cat_name']) ?></span>
                </div>
                <div class="card-body">
                    <h4 class="fw-bold mb-3"><?= esc($ticket['subject']) ?></h4>
                    <div class="p-3 bg-light rounded mb-4 border-start border-4 border-warning">
                        <h6 class="fw-bold small text-muted text-uppercase">Original Description:</h6>
                        <p class="mb-0"><?= nl2br(esc($ticket['description'])) ?></p>
                    </div>

                    <!-- ATTACHED EVIDENCE -->
                    <?php if (!empty($ticket['screenshot'])): ?>
                        <div class="mb-4">
                            <h6 class="fw-bold small text-muted text-uppercase"><i class="fas fa-paperclip me-1"></i> Evidence Attached:</h6>
                            <a href="<?= base_url('uploads/tickets/' . $ticket['screenshot']) ?>" target="_blank">
                                <img src="<?= base_url('uploads/tickets/' . $ticket['screenshot']) ?>" class="evidence-img border shadow-sm" style="max-height: 150px;">
                            </a>
                        </div>
                    <?php endif; ?>

                    <!-- CONVERSATION THREAD -->
                    <h6 class="fw-bold text-muted text-uppercase mb-3"><i class="fas fa-comments me-2"></i>Correspondence History</h6>
                    <div class="chat-container mb-3">
                        <!-- User's Original Message as the first item -->
                        <div class="msg msg-sacco shadow-sm">
                            <strong><?= esc($ticket['username']) ?></strong>
                            <p class="mb-0 mt-1"><?= nl2br(esc($ticket['description'])) ?></p>
                            <span class="msg-meta text-muted"><?= date('d M Y, H:i', strtotime($ticket['created_at'])) ?></span>
                        </div>

                        <?php foreach($replies as $r): ?>
                            <div class="msg shadow-sm <?= $r->uid == auth()->id() ? 'msg-authority' : 'msg-sacco' ?>">
                                <strong><?= $r->username ?></strong>
                                <p class="mb-0 mt-1"><?= nl2br(esc($r->message)) ?></p>
                                <span class="msg-meta"><?= date('d M, H:i', strtotime($r->created_at)) ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- QUICK REPLY BOX (Chat Style) -->
                    <form action="<?= base_url('admin/tickets/conversation') ?>" method="post" class="mt-3">
                    <?= csrf_field() ?>
                    <input type="hidden" name="ticket_id" value="<?= $ticket['id'] ?>">
    
                    <div class="input-group shadow-sm">
                        <textarea name="message" class="form-control border-2" 
                        placeholder="Type a correspondence message to the SACCO..." 
                        style="border-radius: 10px 0 0 10px;" required></textarea>
                  
                        <button type="submit" class="btn btn-sasra px-4">
                        <i class="fas fa-paper-plane me-2"></i> Send Message
                        </button>
                    </div>
                    <small class="text-muted mt-1 d-block italic">
                    <i class="fas fa-info-circle me-1"></i> This adds to the chat thread without requiring a final resolution update.
                    </small>
                    </form>
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN: Action Panel -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm sticky-top" style="top: 20px;">
                <div class="card-header text-white fw-bold" style="background: var(--sasra-gold);">
                    <i class="fas fa-gavel me-2"></i> Official RBSS Action
                </div>
                <div class="card-body">
                    <form action="<?= base_url('admin/tickets/update') ?>" method="post">
                        <?= csrf_field() ?>
                        <input type="hidden" name="ticket_id" value="<?= $ticket['id'] ?>">

                        <div class="mb-3">
                            <label class="form-label fw-bold small">Current Workflow Status</label>
                            <select name="status" class="form-select border-2">
                                <option value="Received" <?= $ticket['status'] == 'Received' ? 'selected' : '' ?>>Received (Pending)</option>
                                <option value="In Progress" <?= $ticket['status'] == 'In Progress' ? 'selected' : '' ?>>In Progress</option>
                                <option value="Closed" <?= $ticket['status'] == 'Closed' ? 'selected' : '' ?>>Closed (Resolved)</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold small text-primary">Final Resolution / Official Update</label>
                            <textarea name="admin_comment" class="form-control border-primary border-2" rows="6" 
                                      placeholder="Provide the final solution or high-level status update for the SACCO Dashboard..."><?= esc($ticket['admin_comment']) ?></textarea>
                            <div class="form-text mt-2 small text-muted">
                                <i class="fas fa-info-circle me-1"></i> This comment appears as the "Latest Feedback" on the SACCO's main dashboard.
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-sasra py-2 fw-bold shadow-sm">
                                <i class="fas fa-save me-1"></i> Update Ticket Status
                            </button>
                            <a href="<?= base_url('admin/tickets') ?>" class="btn btn-outline-secondary btn-sm">Cancel</a>
                        </div>
                    </form>
                </div>
                <div class="card-footer bg-white border-top small text-center text-muted py-3">
                    <i class="fas fa-shield-halved me-1 text-warning"></i> SASRA INTERNAL SYSTEM
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>