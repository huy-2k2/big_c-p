<?php

namespace App\Http\Controllers\Users;

use App\Models\Agent;
use App\Http\Requests\StoreAgentRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateAgentRequest;

class AgentController extends Controller
{
    public function __construct()
    {
        $this->middleware(['author:agent']);
    }

    public function index()
    {
        return view('agent.main');
    }
}
