<?php

namespace App\Models;

use CodeIgniter\Model;

class TicketModel extends Model
{
    protected $table      = 'tickets';
    protected $primaryKey = 'id';

    // Ensure all these fields match your database columns
    protected $allowedFields = [
        'user_id', 
        'category_id', 
        'subject', 
        'description', 
        'screenshot', 
        'status', 
        'admin_comment'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * THIS WAS THE MISSING METHOD:
     * Calculates the Pending, In Progress, and Closed totals for the last 7 days.
     */
    public function getWeeklyStats()
    {
        $db = \Config\Database::connect();
        $last7Days = date('Y-m-d H:i:s', strtotime('-7 days'));
        
        return $db->table($this->table)
                  ->select("SUM(CASE WHEN status = 'Received' THEN 1 ELSE 0 END) as pending,
                            SUM(CASE WHEN status = 'In Progress' THEN 1 ELSE 0 END) as progress,
                            SUM(CASE WHEN status = 'Closed' THEN 1 ELSE 0 END) as closed")
                  ->where('created_at >=', $last7Days)
                  ->get()->getRow();
    }
    // Additional method to get category rankings for analytics
    public function getCategoryRankings($startDate, $endDate)
    {
        return $this->select('categories.name, COUNT(tickets.id) as total')
                    ->join('categories', 'categories.id = tickets.category_id')
                    ->where('tickets.created_at >=', $startDate)
                    ->where('tickets.created_at <=', $endDate)
                    ->groupBy('tickets.category_id')
                    ->orderBy('total', 'DESC') // Most to Least
                    ->findAll();
    }
}