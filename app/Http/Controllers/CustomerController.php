<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\Customer;
use App\Models\User;

class CustomerController extends Controller
{
    //
    /**
     * 1. index()
     * 2. store()
     * 3. show()
     * 4. delete()
     */
    /**
     * index function
     */
    public function index()
    {
        $customers = Customer::all();

        if($customers) {
            return $this->successResponse($customers, 'list of all customer', 201);
        } else {
            return response()->json([
                'message' => 'Failed to fetch data',
                'data' => $customers
            ]);
        }
    }

    /**
     * store / register customer data
     */
    public function store(Request $request)
    {
        $validator = $this->validateInput();

        if ($validator->fails()) {
            return $this->errorResponse($validator->messages(), 422);
        }
        // return response()->json($request->all());
        $customer = Customer::create([
            'name' => $request->name,
            'address' => $request->address,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'handphone' => $request->handphone,
            
        ]);

        if ($customer) {
            return $this->successResponse($customer, 'Customer successfully registered', 201);
        }
    }

    /**
     * customer request validator
     */
    /**
     * requuest validator untuk setiap form
     */
    public function validateInput(){
        return Validator::make(request()->all(), [
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:customers',
            'handphone' => 'required',
            'password' => 'required|string|min:6|confirmed'
        ]);
    }
}
