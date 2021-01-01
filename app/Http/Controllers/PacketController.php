<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

use App\Models\User;
use App\Models\Customer;
use App\Models\Packet;
use App\Mail\SendMail;

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

          //get customer email
          $customer_email = Customer::findOrFail($customer_id)->email;

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
              $sending_email = $this->sendEmail();
              if($sending_email) {
                  
                  return $this->successResponse($packet, 'Pakcet successfully added, sending auto email success', 201);      
              }
              
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

    /**
     * send email
     * percobaan
     */
    public function sendEmail()
    {
        $user_id = auth()->user()->id;
        //get customer id. fetch all customer. but now we doing it manually by assigning it ehehe
        $customer_id = 20;

        //get customer email
        $customer_email = Customer::findOrFail($customer_id)->email;

        $title = '[Pemberitahuan] Paket Untuk Anda [Pemberitahuan]';
        //customer detail
        $customer_detail = Customer::findOrFail($customer_id);

        //packet detail
        $packet_detail = DB::table('packets')
            ->where('customer_id', '=', $customer_id)
            ->where('email_status', '=', '0')
            ->get();
        
        $sendmail = Mail::to($customer_email)->send(new SendMail($title, $customer_detail, $packet_detail));

        if (empty($sendmail)) {
            /**
             * set email status to 1 
             */
            $updated_email_status = DB::table('packets')
                ->where('customer_id', '=', $customer_id)
                ->where('email_status', '=', '0')
                ->update(['email_status' => '1']);

            return response()->json([
                'message' => 'Mail Sent Sucssfully'
            ], 200);
        }else{
            return response()->json([
                'message' => 'Mail Sent fail'
            ], 400);
        }
    }

    /**
     * ambil packet
     * update status to 1 if packet was taken by customer
     */
    public function setPacketStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->messages(), 422);
        }

        /**
         * update status diambil apa tidak
         */
        $update_status = DB::update('update packets set status = 1 where id = ?', [$request->id]);
        $detailed_packet = Packet::findOrFail($request->id);

        return response()->json([
            'message' => 'Data updated successfully, packet status sudah diambil',
            'data' => $detailed_packet
        ]);

    }

    /**
     * test`
     */
    public function getEmail() 
    {
        $customer_id = 20;
        $customer = Customer::findOrFail($customer_id);

        $customer_email = $customer->email;

        return response()->json($customer_email, 200);
    }
}
