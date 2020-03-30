<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api', ['except' => ['login', 'refreshToken']]);
    }

    public function username() {
        return "user_email";
    }

    public function login(Request $request) {
        $request->validate([
            'user_email' => 'required|string|email',
            'license_key' => 'required|string',
        ]);

        $request["password"] = $request["license_key"];
        $credentials = $request->only('user_email', 'password');

        if ($token = Auth::attempt($credentials)) {
            $user = DB::table('analytics_license')->where('user_email', $request["user_email"])->where("license_key", $request["license_key"])->first();
            $expired_date = date("Y-m-d", strtotime("+" . $user->key_duration . " month", strtotime($user->register_date)));
            if ($expired_date < date("Y-m-d")) {
                return response()->json([
                    'message' => 'Your license is already expired. Please contact with Admin'
                ], 500);
            }
            $ret = array(
                "email" => $user->user_email,
                "role"  => ($user->user_email == "hasaloli_hf12@hotmail.com" || $user->user_email == "anna.0309.developer@outlook.com") ? 1 : 0,
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth()->factory()->getTTL() * 60 * 24
            );
            return $ret;
        } else {
            return response()->json([
                'message' => 'Your email or license is incorrect'
            ], 402);
        }
    }

    public function logout() {
        auth()->logout();
    }

    public function refreshToken(Request $request) {
        $request->validate([
            'user_email' => 'required|string|email',
            'license' => 'required|string',
        ]);

        $request["password"] = $request["license"];
        $credentials = $request->only('user_email', 'password');

        $token = Auth::attempt($credentials);
        return $token;
    }

    public function getLicense(Request $request) {
        $data = DB::table('analytics_license')->get();
        $rows = array();
        if ($data) {
            foreach ($data as $row) {
                $newAry = array();
                if ($row->register_date == '0000-00-00') {
                    $expire = '';
                } else {
                    $added_timestamp = strtotime($row->register_date.'+'.$row->key_duration . ' month', time()); 
                    $expire = date('d/m/Y', $added_timestamp);
                }
                $newAry["id"] = $row->id;
                $newAry["email"] = $row->user_email;
                $newAry["license"] = $row->license_key;
                $newAry["duration"] = $row->key_duration . " month";
                $newAry["expire"] = $expire;
                $rows[] = $newAry;
            }
        }
        return $rows;
    }

    public function createUser(Request $request) {
        $request->validate([
            'email' => 'required|string|email',
            'period' => 'required|string|numeric',
        ]);
        $license = $this->newLicense();
        $password = bcrypt($license);
        $saveAry = array(
            "license_key"   => $license,
            "user_email"    => $request["email"],
            "key_duration"  => $request["period"],
            "register_date" => date("Y-m-d"),
            "password"      => $password
        );
        if (DB::table('analytics_license')->updateOrInsert(['user_email' => $request["email"]], $saveAry)) {
            return response()->json([
                "message" => "Successfully create a new license."
            ]);
        } else {
            return response()->json([
                "message" => "Failed to create a new license."
            ], 500);
        }
    }

    public function changeUser(Request $request) {
        if (DB::table('analytics_license')->where("id", $request["id"])->update(['register_date' => date("Y-m-d")])) {
            return response()->json([
                "message" => "Successfully renew a new license."
            ]);
        } else {
            return response()->json([
                "message" => "Failed to renew a new license."
            ], 500);
        }
    }

    public function deleteUser(Request $request) {
        $userId = $request->id;
        if (DB::table('analytics_license')->where('id', '=', $userId)->delete()) {
            return response()->json([
                "message" => "Successfully delete license."
            ]);
        } else {
            return response()->json([
                "message" => "Failed to delete license."
            ], 500);
        }
    }

    private function newLicense($length = 20) {
        $str = "";
        $characters = array_merge(range('A','Z'), range('a','z'), range('0','9'));
        $max = count($characters) - 1;
        for ($i = 0; $i < $length; $i++) {
            $rand = mt_rand(0, $max);
            $str .= $characters[$rand];
        }
        return $str;
    }

    public function searchEvents(Request $request) {
        $request->validate([
            'event_date'  => 'nullable|date',
            'event_name'  => 'nullable|string',
            'event_venue' => 'nullable|string',
        ]);
        $where = "";
        if ($request["event_name"]) {
            $where .= " event_name LIKE '%" . $request["event_name"] . "%'";
        }
        if ($request["event_venue"]) {
            $where .= " AND event_venue LIKE '%" . $request["event_venue"] . "%'";
        }
        if ($request["event_date"]) {
            $where .= " AND DATE(event_date)='" . $request["event_date"] . "'";
        }
        return DB::select("SELECT * FROM events_info WHERE url NOT LIKE '%ticketmaster.ca%' AND url NOT LIKE '%ticketmaster.au%' AND url NOT LIKE '%ticketmaster.mx%' AND " . $where . " ORDER BY event_date ASC");
    }
}
