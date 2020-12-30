<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * 1. index() -> return all the user
     * 3. show() -> 
     * 4. store() -> 
     * 5. update() ->
     * 6. destroy()
     * 
     */

    /**
     * return all user
     */
    public function index(Request $request)
    {
        $users = User::all();

        return $this->successResponse($users, 'List of users');
    }

    /**
     * store the request data to database
     */
    public function store(Request $request)
    {
        try {
            $validator = $this->validateUser();

            if($validator->fails()) {
                return $this->errorResponse($validator->messages(), 422);
            }
    
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'role' => $request->role
            ]);
    
            return $this->successResponse($user, 'User Successfully created', 201);

        } catch(Exception $error) {
            return response()->json([
                'message' => 'error occured',
                'data' => $error
            ]);
        }
        
    }

    /**
     * show user by id
     */
    public function show($id) 
    {
        $user = User::findOrFail($id);

        if(is_null($user)) {
            return $this->errorResponse($user, 'User not found', 404);
        }

        return $this->successResponse($user, 'User detail', 200);
    }

    /**
     * update user by id
     */
    public function update(Request $request, $id)
    {
        //use custom validation for update process
        $validator = $this->updateValidateUser();

        if($validator->fails()) {
            return $this->errorResponse($validator->messages(), 422);
        }

        $user = User::findOrFail($id);
        //update data
        if($user) {
            $update = $user->update($request->all());

            if($update) {
                return $this->successResponse($update, 'Data updated succesfully', 201);
            } else {
                return response()->json([
                    'message' => 'Failed to update data',
                    'data' => $update
                ]);
            }
        }
    }
    
    /**
     * destroy data by id
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if($user) {
            $user->delete();

            return response()->json([
                'message' => 'User deleted successfully',
                'success' => true,
            ]);
        } else {
            return response()->json([
                'message' => 'error deleting user'
            ]);
        }
    }

    /**
     * requuest validator untuk setiap form
     */
    public function validateUser(){
        return Validator::make(request()->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required'
        ]);
    }

    /** 
     * for update validation
    */
    public function updateValidateUser()
    {
        return Validator::make(request()->all(), [
            'name' => 'required|string|max:255',
            //'email' => 'required|string|email|max:255|unique:users',
        ]);
    }
}
