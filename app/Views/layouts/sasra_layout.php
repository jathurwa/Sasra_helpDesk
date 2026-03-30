<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="<?= base_url('assets/img/sasra-logo.png') ?>" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SASRA | RBSS Support Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --sasra-navy: rgb(224 168 46 / var(--tw-bg-opacity, 1)); --sasra-gold: #D8E6E8; --sasra-bg: #f0f2f5; }
        body { background: var(--sasra-bg); font-family: 'Segoe UI', sans-serif; }
        .sidebar { width: 260px; height: 100vh; background: var(--sasra-navy); color: white; position: fixed; }
        .main-content { margin-left: 260px; padding: 25px; min-height: 100vh; }
        .nav-link { color: #030303; padding: 12px 20px; transition: 0.3s; border-radius: 5px; margin: 5px 15px; }
        .nav-link:hover, .nav-link.active { background: var(--sasra-gold); color: white; }
        .navbar-custom { background: white; border-bottom: 3px solid var(--sasra-gold); padding: 12px 30px; }
        .btn-sasra { background: var(--sasra-navy); color: white; }
        .btn-sasra:hover { background: var(--sasra-gold); color: white; }
        .status-received { background: #e74c3c; color: white; } /* Pending/New */
        .status-progress { background: #f1c40f; color: #2c3e50; } /* In Progress */
        .status-closed { background: #27ae60; color: white; } /* Closed */
    </style>
</head>
<body>

<div class="sidebar d-flex flex-column shadow">
    <div class="p-4 text-center border-bottom border-secondary">
        <h5 class="fw-bold mb-0">SASRA RBSS</h5>
        <small class="text-white-50">Operational Support</small>
    </div>
    <div class="mt-3">
        <?php if(auth()->user()->inGroup('superadmin', 'admin')): ?>
            <a href="<?=base_url('admin/dashboard') ?>" class="nav-link"><i class="fas fa-chart-bar me-2"></i> Analytics</a>
            <a href="<?=base_url('admin/tickets') ?>" class="nav-link"><i class="fas fa-tasks me-2"></i> Manage Issues</a>
            <a href="<?=base_url('admin/users') ?>" class="nav-link"><i class="fas fa-user-shield me-2"></i> User Management</a>
        <?php else: ?>
            <a href="<?=base_url('sacco/dashboard') ?>" class="nav-link"><i class="fas fa-history me-2"></i> My Dashboard</a>
            <a href="<?=base_url('sacco/tickets/new') ?>" class="nav-link"><i class="fas fa-plus-circle me-2"></i> Raise New Issue</a>
        <?php endif; ?>
        <a href="<?=base_url('logout') ?>" class="nav-link mt-5 bg-danger text-white"><i class="fas fa-power-off me-2"></i> Logout</a>
    </div>
</div>

<div class="main-content">
    <div class="navbar-custom d-flex justify-content-between align-items-center mb-4 shadow-sm rounded">
        <h6 class="mb-0 fw-bold text-uppercase" style="color: var(--sasra-navy);">RBSS Operational Management System</h6>
        <div>
            <span class="small me-3">Role: <strong><?= strtoupper(auth()->user()->getGroups()[0]) ?></strong></span>
            <img src="https://ui-avatars.com/api/?name=<?= auth()->user()->username ?>&background=0d2d5e&color=fff" class="rounded-circle" width="35">
        </div>
    </div>
    <?= $this->renderSection('content') ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</body>
</html>