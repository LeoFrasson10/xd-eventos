<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientsController extends Controller
{
  public function index()
  {
    $clients = DB::table('clients')->paginate(10);
    return view('site.auth.clients.index', compact('clients'));
  }
}
