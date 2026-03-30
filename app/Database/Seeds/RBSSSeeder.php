<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RBSSSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name' => 'RBSS Approval Request (Form 1/2)',
            ],
            [
                'name' => 'Returns Submission Error',
            ],
            [
                'name' => 'License Application & Renewal Documentation',
            ],
            [
                'name' => 'RBSS Login/Credential Issue',
            ],
            [
                'name' => 'Capital Adequacy Approval',
            ],
            [
                'name' => 'Technical Support (ERP/System Error)',
            ],
            [
                'name' => 'Filing: Liquidity Statement',
            ],
            [
                'name' => 'Filing: Other Approval/Submission',
            ],
            [
                'name' => 'Filing: Other Issues',
            ],
        ];

        // Using Query Builder to insert the data
        $this->db->table('categories')->insertBatch($data);
    }
}
