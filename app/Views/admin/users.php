<?= $this->extend('layouts/sasra_layout') ?>

<?= $this->section('content') ?>
<div class="container-fluid">

    <!-- Flash Messages -->
    <?php if (session()->getFlashdata('msg')) : ?>
        <div class="alert alert-success shadow-sm border-0"><?= session()->getFlashdata('msg') ?></div>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold" style="color: var(--sasra-navy);">User Management</h3>
        <!-- ADD USER BUTTON: Uses standard Bootstrap data attributes -->
        <button type="button" class="btn btn-sasra" data-bs-toggle="modal" data-bs-target="#addUserModal">
            <i class="fas fa-user-plus me-2"></i> Add New User
        </button>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th class="ps-4">Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $u) : ?>
                        <tr>
                            <td class="ps-4"><strong><?= esc($u->username) ?></strong></td>
                            <td><?= esc($u->email) ?></td>
                            <td>
                                <span class="badge bg-secondary"><?= strtoupper($u->getGroups()[0] ?? 'NONE') ?></span>
                            </td>
                            <td>
                                <!-- PASS BUTTON: Calls your openResetModal function -->
                                <button type="button" class="btn btn-sm btn-outline-warning" 
                                        onclick="openResetModal(<?= $u->id ?>, '<?= esc($u->username, 'js') ?>')">
                                    <i class="fas fa-key"></i> Pass
                                </button>

                                <?php if (auth()->user()->inGroup('superadmin')) : ?>
                                    <form action="<?= base_url('admin/users/update-role') ?>" method="post" class="d-inline ms-2">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="user_id" value="<?= $u->id ?>">
                                        <select name="role" onchange="this.form.submit()" class="form-select form-select-sm d-inline w-auto border-secondary">
                                            <option value="sacco_user" <?= $u->inGroup('sacco_user') ? 'selected' : '' ?>>SACCO</option>
                                            <option value="admin" <?= $u->inGroup('admin') ? 'selected' : '' ?>>Admin</option>
                                            <option value="superadmin" <?= $u->inGroup('superadmin') ? 'selected' : '' ?>>SuperAdmin</option>
                                        </select>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL: ADD NEW USER -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="<?= base_url('admin/users/create') ?>" method="post" class="modal-content border-0 shadow">
            <div class="modal-header text-white" style="background: var(--sasra-navy);">
                <h5 class="modal-title">Create Account</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <?= csrf_field() ?>
                <div class="mb-3"><label class="fw-bold small">Username/SACCO_NAME</label><input type="text" name="username" class="form-control" required></div>
                <div class="mb-3"><label class="fw-bold small">Email</label><input type="email" name="email" class="form-control" required></div>
                <div class="mb-3"> <label class="form-label fw-bold small">Assign Portal Role</label> <select name="role" class="form-select border-2" required> <option value="sacco_user" selected>SACCO User (Default)</option> <option value="admin">Regulatory Officer (Admin)</option> <!-- Only SuperAdmins can see the option to create other SuperAdmins --> <?php if (auth()->user()->inGroup('superadmin')): ?> <option value="superadmin">Super Admin</option> <?php endif; ?> </select> </div>
                <div class="mb-3"><label class="fw-bold small">Password</label><input type="password" name="password" class="form-control" required minlength="8"></div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-sasra">Save User</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL: RESET PASSWORD (Targeted by your script) -->
<div class="modal fade" id="resetModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <form action="<?= base_url('admin/users/change-password') ?>" method="post" class="modal-content border-0 shadow">
            <?= csrf_field() ?>
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title small fw-bold">Reset Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <input type="hidden" name="user_id" id="reset_user_id">
                <p class="small">New password for: <br><strong id="reset_username"></strong></p>
                <input type="password" name="password" class="form-control text-center" placeholder="Enter New Password" required minlength="8">
            </div>
            <div class="modal-footer border-0">
                <button type="submit" class="btn btn-dark w-100">Update Password</button>
            </div>
        </form>
    </div>
</div>

<script>
/**
 * Using the code from your snippet but ensuring the Modal ID matches
 */
function openResetModal(id, name) {
    document.getElementById('reset_user_id').value = id;
    document.getElementById('reset_username').innerText = name;
    
    // This part requires bootstrap.bundle.min.js in your layout
    var myModal = new bootstrap.Modal(document.getElementById('resetModal'));
    myModal.show();
}
</script>

<?= $this->endSection() ?>