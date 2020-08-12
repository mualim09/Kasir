<?php

namespace App\Http\Controllers;

use App\Repositories\Item;
use App\Repositories\Customer;
use App\Repositories\User;
use Illuminate\Http\Request;

class Dashboard extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $items = (new Item())->totalForDashboard();
        $customers = (new Customer())->totalForDashboard();
        $employeeres =  (new User())->totalForDashboard();

        return view('app.dashboard', [
            'cards' => compact('items', 'customers', 'employeeres')
        ]);
    }
}
