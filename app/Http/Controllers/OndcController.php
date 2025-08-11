<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OndcController extends Controller
{
    public function index()
    {
        // Example: fetch these from session, user, or request as needed
        $KIOSKCODEJ = session('KIOSKCODEJ', '');
        $KIOSKNAMEJ = session('KIOSKNAMEJ', '');

        // Provide a default company logo for mydata
        $mydata = [
            'company' => (object)[
                'logo' => 'logos/default-logo.png'
            ]
        ];

        $ondcseller = DB::table('ondc_seller')->get();

        return view('apply.ondc_seller_registration', compact('KIOSKCODEJ', 'KIOSKNAMEJ', 'mydata', 'ondcseller'));
    }

    public function storeSeller(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sellername' => 'required|string|max:255',
            'mobile' => 'required|digits:10|unique:ondc_seller,mobile',
            'email' => 'required|email|unique:ondc_seller,email',
            'district' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        DB::table('ondc_seller')->insert([
            'name' => $request->sellername,
            'mobile' => $request->mobile,
            'email' => $request->email,
            'district' => $request->district,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Seller registered successfully.',
        ]);
    }



    public function sellerDelete($id)
    {
        $seller = DB::table('ondc_seller')->where('id', $id)->first();

        if (!$seller) {
            return response()->json(['success' => false, 'message' => 'Seller not found.']);
        }

        DB::table('ondc_seller')->where('id', $id)->delete();

        return response()->json(['success' => true, 'message' => 'Seller deleted successfully.']);
    }



    // public function showOndcSeller(){
    //     $ondcseller = DB::table('ondc_seller')->get();
    //     return view('ondc_seller_registration',compact('ondcseller'));
    // }
}
