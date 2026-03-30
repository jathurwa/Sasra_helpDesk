<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\Shield\Entities\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        $users = auth()->getProvider();

        // 1. Create SuperAdmin
        $superAdmin = new User([
            'username' => 'sasra_superadmin',
            'email'    => 'superadmin@sasra.go.ke',
            'password' => 'SuperAdmin123',
        ]);
        $users->save($superAdmin);
        $superAdmin = $users->findById($users->getInsertID());
        $superAdmin->addGroup('superadmin');

        // 2. Create Admin (Officer)
        $admin = new User([
            'username' => 'sasra_officer',
            'email'    => 'officer@sasra.go.ke',
            'password' => 'Officer123',
        ]);
        $users->save($admin);
        $admin = $users->findById($users->getInsertID());
        $admin->addGroup('admin');

        // 3. Create SACCO User
        $sacco = new User([
            'username' => 'demo_sacco',
            'email'    => 'user@sacco.com',
            'password' => 'Sacco123',
        ]);
        $users->save($sacco);
        $sacco = $users->findById($users->getInsertID());
        $sacco->addGroup('sacco_user');
    }
}