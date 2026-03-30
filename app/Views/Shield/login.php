<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">  
    <link rel="icon" href="<?= base_url('assets/img/sasra-logo.png') ?>" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | SASRA RBSS Support Portal</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --sasra-navy: #0d2d5e;
            --sasra-gold: #c5a059;
        }
        body {
            background-color: #f0f2f5;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .login-card {
            width: 100%;
            max-width: 400px;
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .login-header {
            background-color: var(--sasra-navy);
            padding: 30px;
            text-align: center;
            border-bottom: 5px solid var(--sasra-gold);
        }
        .login-header h4 {
            color: white;
            font-weight: 700;
            margin-top: 10px;
            letter-spacing: 1px;
        }
        .login-body {
            background: white;
            padding: 40px 30px;
        }
        .form-control {
            border-radius: 8px;
            padding: 12px;
            border: 1px solid #ddd;
        }
        .form-control:focus {
            border-color: var(--sasra-gold);
            box-shadow: 0 0 0 0.25 margin-top-px rgba(197, 160, 89, 0.25);
        }
        .btn-sasra {
            background-color: var(--sasra-navy);
            color: white;
            border: none;
            padding: 12px;
            border-radius: 8px;
            font-weight: 600;
            transition: 0.3s;
            width: 100%;
        }
        .btn-sasra:hover {
            background-color: var(--sasra-gold);
            color: white;
        }
        .footer-text {
            text-align: center;
            margin-top: 20px;
            font-size: 0.85rem;
            color: #777;
        }
    </style>
</head>
<body>

<div class="login-card">
    <div class="login-header">
        <!-- Replace with SASRA Logo if available -->
        <i class="fas fa-shield-halved fa-3x text-white"></i>
        <h4>SASRA RBSS</h4>
        <small class="text-white-50 text-uppercase">Operational Support Portal</small>
    </div>

    <div class="login-body">
        <?php if (session('error')) : ?>
            <div class="alert alert-danger small py-2"><?= session('error') ?></div>
        <?php endif ?>

        <?php if (session('errors')) : ?>
            <div class="alert alert-danger small py-2">
                <?php foreach (session('errors') as $error) : ?>
                    <li><?= $error ?></li>
                <?php endforeach ?>
            </div>
        <?php endif ?>

        <form action="<?= url_to('login') ?>" method="post">
            <?= csrf_field() ?>

            <!-- Email / Username -->
            <div class="mb-3">
                <label class="form-label small fw-bold">Email or Username</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="fas fa-user text-muted"></i></span>
                    <input type="text" class="form-control border-start-0 ps-0" name="login" placeholder="Enter your credentials" value="<?= old('login') ?>" required autocomplete="off">
                </div>
            </div>

            <!-- Password -->
            <div class="mb-3">
                <label class="form-label small fw-bold">Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="fas fa-lock text-muted"></i></span>
                    <input type="password" class="form-control border-start-0 ps-0" name="password" placeholder="••••••••" required>
                </div>
            </div>

            <!-- Remember Me -->
            <?php if (setting('Auth.sessionConfig')['allowRemembering']): ?>
                <div class="form-check mb-4">
                    <input type="checkbox" name="remember" class="form-check-input" id="rememberMe" <?php if (old('remember')): ?> checked <?php endif ?>>
                    <label class="form-check-label small text-muted" for="rememberMe">Remember my session</label>
                </div>
            <?php endif; ?>

            <button type="submit" class="btn btn-sasra shadow-sm">
                <i class="fas fa-sign-in-alt me-2"></i> SIGN IN TO PORTAL
            </button>
        </form>

        <div class="footer-text">
            &copy; <?= date('Y') ?> Sacco Regulatory Authority (SASRA)<br>
            <small>Internal RBSS Support System</small>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>