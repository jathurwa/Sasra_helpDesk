<?php

namespace Config;

use CodeIgniter\Shield\Config\AuthGroups as ShieldGroups;

class AuthGroups extends ShieldGroups
{
    /**
     * --------------------------------------------------------------------
     * Default Groups
     * --------------------------------------------------------------------
     * These are the "Valid" groups in your SASRA RBSS System.
     */

    public string $defaultGroup = 'sacco_user'; // New users are SACCO staff by default
    
    public array $groups = [
        'superadmin' => [
            'title'       => 'Super Admin',
            'description' => 'Full SASRA ERP control and role management.',
        ],
        'admin' => [
            'title'       => 'Authority Admin',
            'description' => 'Regulatory staff managing RBSS issues and SACCO Users.',
        ],
        'sacco_user' => [
            'title'       => 'SACCO Staff',
            'description' => 'Users from SACCOs raising RBSS support tickets.',
        ],
    ];

    /**
     * --------------------------------------------------------------------
     * Permissions Matrix
     * --------------------------------------------------------------------
     */
    public array $matrix = [
        'superadmin' => [
            'admin.access',
            'users.manage',
            'superadmin.privilege',
        ],
        'admin' => [
            'admin.access',
            'users.manage',
        ],
        'sacco_user' => [],
    ];
}
