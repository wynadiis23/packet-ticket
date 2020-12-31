<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Customer;
use App\Models\Packet;

class PacketController extends Controller
{
    //
    /**
     * 1. index  -> return all packet
     * 2. store -> store packet incoming
     * 3. show -> return detailed packet
     * 4. update -> update packet
     * 5. destroy -> delete certain packet by id
     */

     /**
      * show all packet`
      */
      public function index()
      {
          $packets = Packet::all();

          return $this->successResponse($packets, 'List of all packets', 200);
      }

      /**
       * store coming packet
       */
      public function store(Request $request)
      {
          $validator = $this->validateInput();

          if ($validator->fails()) {
              return $this->errorResponse($validator->messages(), 422);
          }
          
          //get user id
          $user_id = auth()->user()->id;
          //get customer id. fetch all customer. but now we doing it manually by assigning it ehehe
          $customer_id = 20;

          $packet = Packet::create([
              'name' => $request->name,
              'ket' => $request->ket,
              'tgl_dtg' => date("yy/m/d"),
              'tgl_ambil' => null,
              //status default 0
              'status' => '0',
              'user_id' => $user_id,
              'customer_id' => $customer_id
          ]);

          if ($packet) {
              return $this->successResponse($packet, 'Pakcet successfully added', 201);
          }

      }


      /**
       * validation input
       */
      public function validateInput(){
        return Validator::make(request()->all(), [
            'name' => 'required|string|max:255',
            'ket' => 'required|string|max:255',
            // 'tgl_dtg' => 'required|string|max:255',
            // 'status' => 'required',
            // 'user_id' => 'required',
            // 'customer_id' => 'required'
        ]);
    }
}
