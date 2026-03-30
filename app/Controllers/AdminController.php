<?php

namespace App\Controllers;

use App\Models\TicketModel;
use CodeIgniter\Shield\Models\UserModel;
use CodeIgniter\Shield\Entities\User;

class AdminController extends BaseController
{
    public function index() 
    { 
        $model = new TicketModel(); // Fetch all tickets from all SACCOs + Join category names 
        $data['tickets'] = $model->select('tickets.*, categories.name as cat_name, users.username as username') 
                                 ->join('categories', 'categories.id = tickets.category_id') 
                                 ->join('users', 'users.id = tickets.user_id')
                                 ->orderBy('tickets.created_at', 'DESC') 
                                 ->findAll(); 
        return view('admin/tickets_list', $data); 
    }

    // --- DASHBOARD & ANALYTICS ---
    public function dashboard()
    {
        //the new dashboard calculations for the bar chart with the Date for 'LastMonth' and the 'Weekly Stats' method in the TicketModel
        
        $model = new \App\Models\TicketModel();
        $db = \Config\Database::connect();

        // 1. Calculate Date Ranges
        $startLastMonth = date('Y-m-01 00:00:00', strtotime('first day of last month'));
        $endLastMonth   = date('Y-m-t 23:59:59', strtotime('last day of last month'));

        // Weekly Friday Logic: Compiles the last 7 days leading up to today
        $startOfWeek = date('Y-m-d H:i:s', strtotime('last friday', strtotime('tomorrow')));
        $endOfWeek   = date('Y-m-d H:i:s');

        // 2. Fetch Data
        $data['stats'] = $model->getWeeklyStats();
        $data['trends'] = $db->table('monthly_archives')->orderBy('id', 'DESC')->limit(6)->get()->getResult();
        
        // Fetch Rankings
        $data['top_last_month'] = $model->getCategoryRankings($startLastMonth, $endLastMonth);
        $data['top_this_week']  = $model->getCategoryRankings($startOfWeek, $endOfWeek);

        return view('admin/dashboard', $data);
    

        //another method to get the weekly stats using the new method in the TicketModel
        $model = new TicketModel();
        $db = \Config\Database::connect();

        // Capture time-to-time responses and update status
        $data = [
            'status' => $model->getWeeklyStats(),
            'trends' => $db->table('monthly_archives')->orderBy('id', 'DESC')->limit(6)->get()->getResult(),
            'updated_at'    => date('Y-m-d H:i:s')
        ];

        $db = \Config\Database::connect();
    
        
        // Weekly Statistics for the Bar Chart
        $data['stats'] = $db->table('tickets')
            ->select("SUM(CASE WHEN status='Received' THEN 1 ELSE 0 END) as pending,
                      SUM(CASE WHEN status='In Progress' THEN 1 ELSE 0 END) as progress,
                      SUM(CASE WHEN status='Closed' THEN 1 ELSE 0 END) as closed")
            ->get()->getRow();

        // Monthly Trend Data
        $data['trends'] = $db->table('monthly_archives')->orderBy('id', 'DESC')->limit(6)->get()->getResult();

        return view('admin/dashboard', $data);
    }

    public function viewTicket($id)
    {
        $model = new \App\Models\TicketModel();
        $db = \Config\Database::connect();

        // 1. Fetch ticket details with joins for Category and Username
        $data['ticket'] = $model->select('tickets.*, categories.name as cat_name, users.username')
                                ->join('categories', 'categories.id = tickets.category_id')
                                ->join('users', 'users.id = tickets.user_id')
                                ->find($id);

        if (!$data['ticket']) {
            return redirect()->to('/admin/tickets')->with('error', 'Issue not found.');
        }

        // 2. Fetch all replies (Correspondence History)
        $data['replies'] = $db->table('replies')
                            ->select('replies.*, users.username, users.id as uid')
                            ->join('users', 'users.id = replies.user_id')
                            ->where('ticket_id', $id)
                            ->orderBy('created_at', 'ASC')
                            ->get()->getResult();

        return view('admin/view_ticket', $data);
    }


    // --- TICKET WORKFLOW MANAGEMENT ---
    public function updateTicket()
    {
        $model = new TicketModel();
        $id = $this->request->getPost('ticket_id');

        // Capture time-to-time responses and update status
        $model->update($id, [
            'status'        => $this->request->getPost('status'),
            'admin_comment' => $this->request->getPost('admin_comment'),
            
            'updated_at'    => date('Y-m-d H:i:s')
        ]);

        return redirect()->back()->with('msg', 'RBSS Status and Feedback updated successfully.');
    }

    // --- USER MANAGEMENT (ADMIN & SUPERADMIN) ---
    public function manageUsers()
    {
        $userModel = new UserModel();
        // Fetch all users with their emails
        $data['users'] = $userModel->select('users.*, auth_identities.secret as email')
                                   ->join('auth_identities', 'auth_identities.user_id = users.id')
                                   ->findAll();
        return view('admin/users', $data);
    }

    // Add new SACCO or Admin
    public function addUser()
    {
        $users = auth()->getProvider();
        $username = $this->request->getPost('username');
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $role = $this->request->getPost('role'); // 'sacco_user', 'admin', or 'superadmin'
        
        /*$user = new User([
            'username' => $this->request->getPost('username'),
            'email'    => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
            'role'    => $this->request->getPost('role'),
        ]);*/

         // 2. Security Check: Prevent standard Admins from creating SuperAdmins via post tampering
        if ($role === 'superadmin' && !auth()->user()->inGroup('superadmin')) {
            return redirect()->back()->with('error', 'Unauthorized: You cannot create SuperAdmin accounts.');
        }

        // 3. Create the User Entity
        $user = new \CodeIgniter\Shield\Entities\User([
            'username' => $username,
            'email'    => $email,
            'password' => $password,
        ]);

        // 4. Save to Database
        $users->save($user);
        $newUser = $users->findById($users->getInsertID());

        // 5. Add to the selected Group
        $newUser->addGroup($role);

        return redirect()->back()->with('msg', "Account for $username created as " . strtoupper($role));

        $users->save($user);
        $user = $users->findById($users->getInsertID());
        
        // Default new users to sacco_user
        $user->addGroup('sacco_user');

        return redirect()->back()->with('msg', 'New SACCO User Account Created.');
    }

    // Reset User Password
    public function changePassword()
    {
        $userModel = new UserModel();
        $user = $userModel->find($this->request->getPost('user_id'));
        
        $user->setPassword($this->request->getPost('password'));
        $userModel->save($user);

        return redirect()->back()->with('msg', 'Password for ' . $user->username . ' has been reset.');
    }

    // SUPERADMIN ONLY: Role Promotion/Demotion
    public function updateRole()
    {
        if (!auth()->user()->inGroup('superadmin')) {
            return redirect()->back()->with('error', 'Unauthorized: Only SuperAdmin can modify administrative roles.');
        }

        $userId = $this->request->getPost('user_id');
        $newRole = $this->request->getPost('role');

        $userModel = new UserModel();
        $user = $userModel->find($userId);
        
        // Clear old groups and add the new one
        $user->syncGroups($newRole);

        return redirect()->back()->with('msg', 'User role successfully updated to ' . $newRole);
    }
    
    public function postConversationReply()
    {
        $db = \Config\Database::connect();
        
        $ticketId = $this->request->getPost('ticket_id');
        $message = $this->request->getPost('message');

        // 1. Insert into the 'replies' table (This is the shared chat table)
        $db->table('replies')->insert([
            'ticket_id' => $ticketId,
            'user_id' => auth()->id(), // Admin's ID
            'message' => $message,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        // 2. Automatically set status to 'In Progress' if it was 'Received'
        // This ensures the workflow moves forward just by chatting
        $model = new \App\Models\TicketModel();
        $ticket = $model->find($ticketId);
        if ($ticket['status'] == 'Received') {
            $model->update($ticketId, ['status' => 'In Progress']);
        }

        return redirect()->back()->with('msg', 'Message sent to SACCO correspondence.');
    }
}