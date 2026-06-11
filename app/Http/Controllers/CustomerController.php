<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::latest('id')->get();

        return view('customers.list', compact('customers'));
    }
    public function create()
    {
        return view('customers.form');
    }
    public function store()
    {
        // dd(request()->all());
        Customer::create(
            [
                'name' => request()->name,
                'phone' => request()->phone,
                'email' => request()->email,
                'gender' => request()->gender,
            ]
        );
        return redirect('/customers');
    }

    public function edit($id)
    {
        $customer = Customer::find($id);
        // dd($customer);
        return view('customers.edit', compact('customer'));
    }
    public function update($id)
    {
        // dd($id);
        $customer = Customer::find($id);
        $customer->update(
            [
                'name' => request()->name,
                'phone' => request()->phone,
                'email' => request()->email,
                'gender' => request()->gender,

            ]
        );
        return redirect('/customers');
    }
    public function destroy($id) {
        Customer::destroy($id);
        return redirect('/customers');
    }
}
