<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Events\DatabaseRefreshed;

class DatabaseRefresherController extends Controller
{
    public function refreshDatabase()
    {
        // Your code to refresh the database goes here
        event(new DatabaseRefreshed('hello'));
    }
}
