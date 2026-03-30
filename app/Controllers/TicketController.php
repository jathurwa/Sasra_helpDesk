<?php

namespace App\Controllers;

use App\Models\TicketModel;

class TicketController extends BaseController
{
    /**
     * This is the 'index' method that the error is looking for.
     * It loads the SACCO Dashboard history.
     */
    public function index()
    {
        $model = new TicketModel();

        // Fetch tickets for the logged-in user + join with category names
        $data['tickets'] = $model->select('tickets.*, categories.name as cat_name')
                                 ->join('categories', 'categories.id = tickets.category_id')
                                 ->where('user_id', auth()->id())
                                 ->orderBy('tickets.created_at', 'DESC')
                                 ->findAll();

        return view('sacco/dashboard', $data);
    }

    /**
     * Show the form to raise a new issue.
     */
    public function new()
    {
        $db = \Config\Database::connect();
        $data['categories'] = $db->table('categories')->get()->getResult();

        return view('sacco/create_ticket', $data);
    }

    /**
     * Save the new issue and handle screenshot.
     */
    public function create()
    {
        $model = new TicketModel();
        $screenshotName = null;

        $file = $this->request->getFile('screenshot');

        if ($file && $file->isValid() && ! $file->hasMoved()) {
            $screenshotName = $file->getRandomName();
            $file->move(FCPATH . 'uploads/tickets', $screenshotName);
        }

        $model->save([
            'user_id'     => auth()->id(),
            'username'    => auth()->user()->username,
            'category_id' => $this->request->getPost('category_id'),
            'subject'     => $this->request->getPost('subject'),
            'description' => $this->request->getPost('description'),
            'screenshot'  => $screenshotName,
            'status'      => 'Received'
        ]);

        return redirect()->to('/sacco/dashboard')->with('msg', 'Issue submitted successfully.');
    }

    /**
     * View a specific issue thread.
     */
    public function show($id)
    {
        $model = new TicketModel();
        $db = \Config\Database::connect();

        $data['ticket'] = $model->select('tickets.*, categories.name as cat_name')
                                ->join('categories', 'categories.id = tickets.category_id')
                                ->where('tickets.id', $id)
                                ->where('user_id', auth()->id())
                                ->first();

        if (!$data['ticket']) {
            return redirect()->to('/sacco/dashboard');
        }

        $data['replies'] = $db->table('replies')
                              ->select('replies.*, users.username, users.id as uid')
                              ->join('users', 'users.id = replies.user_id')
                              ->where('ticket_id', $id)
                              ->orderBy('created_at', 'ASC')
                              ->get()->getResult();

        return view('sacco/view_ticket', $data);
    }
    public function addReply()
    {
        $db = \Config\Database::connect();
        $ticketId = $this->request->getPost('ticket_id');
        $message = $this->request->getPost('message');

        // 1. Security: Check if the user actually owns this ticket
        $model = new TicketModel();
        $ticket = $model->where('id', $ticketId)->where('user_id', auth()->id())->first();

        if (!$ticket) {
            return redirect()->to('/sacco/dashboard')->with('error', 'Unauthorized Action.');
        }

        // 2. Insert the message into the replies table
        $db->table('replies')->insert([
            'ticket_id' => $ticketId,
            'user_id' => auth()->id(),
            'message' => $message,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        // 3. Optional: Update the updated_at timestamp on the ticket
        
        $model->update($ticketId, ['updated_at' => date('Y-m-d H:i:s')]);

        return redirect()->back()->with('msg', 'Response sent to SASRA successfully.');
    }
}