<?php



namespace App\Http\Controllers\User;

use App\City;

use App\File;

use App\Http\Controllers\Controller;

use App\KycDetail;

use App\PackageSetting;

use App\Role;

use App\SmsTemplate;

use App\State;

use App\StoreCategory;

use App\TransactionDetail;

use App\ApplicationDetail;

use App\User;

use App\Ekyc;

use App\Verification;

use App\ApiSetting;

use App\ServicesType;

use Auth;

use Config;

use DB;

use PDF;

use Mail;

use Session;

use Response;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Http;

use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\Log;

use App\Packages\Cashfree\CfAutoCollect;

use App\Packages\Cashfree\CfPayout;

use Illuminate\Support\Facades\Redirect;

use App\BankAccount;



class UserController extends Controller

{



    /**

     * User List

     */

    public function allbeneficiarytocashfree()

    {

        //Generate Token

        $clientid = "CF154737C6L0GFLJDDO8UP2KFU3G";

        $clientsecret = "11bd7c7cc53eb959188b10fcc7c282a067ad4997";

        $url = "https://payout-api.cashfree.com/payout/v1/authorize";

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_POST, true);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "X-Client-Id:" . $clientid, "X-Client-Secret:" . $clientsecret));

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $result_json = curl_exec($ch);



        curl_close($ch);

        $result =  json_decode($result_json, true);



        $token = $result['data']['token'];



        //End

        //Verify token



        $url = "https://payout-api.cashfree.com/payout/v1/verifyToken";

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_POST, true);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Authorization:Bearer " . $token));

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $result_json = curl_exec($ch);



        curl_close($ch);

        $result =  json_decode($result_json, true);

        //End

        $deleteuser = DB::table('tbl_dmt_benificiary_dtls')->where('recipient_id', '>', 0)->where('api_name', '=', 'upi')->get();



        foreach ($deleteuser as $data) {

            //   $url="https://payout-api.cashfree.com/payout/v1/addBeneficiary";

            //   $ch = curl_init($url);



            //   $Params["beneId"]= $data->bank_account_number;

            //   $Params["name"]= $data->recipient_name;

            //   $Params["email"] = "mehtashyam13@gmail.com";

            //   $Params["phone"] = "9033975413";

            //   $Params["bankAccount"]= $data->bank_account_number;

            //   $Params["ifsc"] = $data->ifsc;

            //   $Params["address1"] ="ABC Street";

            //   $Params["address2"] = "ABC Streetssss";

            //   $Params["city"] = "";

            //   $Params["state"] = "";

            //   $Params["pincode"] = "";



            //   $post_data = json_encode($Params, JSON_UNESCAPED_SLASHES);



            //   curl_setopt($ch, CURLOPT_POST, true);

            //   curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);

            //   curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Authorization:Bearer " . $token)); 

            //   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 

            //   $result_json = curl_exec($ch);



            //   curl_close($ch);

            //   $result =  json_decode($result_json, true);

            //   if($result['message']!='Beneficiary Id already exists')

            //   {

            //   echo $data->bank_account_number.'-'.$data->recipient_name.'-'.$result['message']."<br>";

            //   }

            $names = $data->recipient_name;

            $bankaccountno = $data->bank_account_number;



            $url = "https://payout-api.cashfree.com/payout/v1/validation/upiDetails?name=" . "&vpa=" . $bankaccountno;

            $ch = curl_init($url);



            $Params["beneId"] = $data->bank_account_number;

            $Params["name"] = $data->recipient_name;

            $Params["email"] = "mehtashyam13@gmail.com";

            $Params["phone"] = "9033975413";

            $Params["bankAccount"] = $data->bank_account_number;

            $Params["ifsc"] = $data->ifsc;

            $Params["address1"] = "ABC Street";

            $Params["address2"] = "ABC Streetssss";

            $Params["city"] = "";

            $Params["state"] = "";

            $Params["pincode"] = "";



            $post_data = json_encode($Params, JSON_UNESCAPED_SLASHES);





            curl_setopt($ch, CURLOPT_HTTPGET, 1);

            curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Authorization:Bearer " . $token));

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $result_json = curl_exec($ch);



            curl_close($ch);

            $result =  json_decode($result_json, true);

            print_r($result);

            echo "<br>";

            $result =  json_decode($result_json, true);

            if ($result['message'] != 'Beneficiary Id already exists') {

                echo $data->bank_account_number . '-' . $data->recipient_name . '-' . $result['message'] . "<br>";
            }
        }













        //Ends Here

    }



    public function test()
    {

        $user = "";

        $data = array(

            'name' => 'Ashish',

            'otp' => '123456'

        );

        $send_email = Mail::send('mail.otp', $data, function ($msg) use ($user) {

            $msg->to('ashishb.jgi@gmail.com', 'Ashish Budhraja');

            $msg->subject('OTP to Reset your Password - PayMama');

            $msg->from('hello@paymamaapp.in', 'PayMama - Business Made Easy');
        });



        return $send_email;
    }



    public function facematch($id = null, Request $request)

    {

        $getdetails = Verification::where('id', $id)->first();

        $selfie_id = $getdetails->selfie_id;

        $pan_id = $getdetails->pan_id;

        $getselfie = File::where('id', $selfie_id)->first();

        $selfiefile = "https://paymamaapp.in/admin" . $getselfie->file_path;

        $selfiename = str_replace('/assets/images/', '', $getselfie->file_path);

        $getpan = File::where('id', $pan_id)->first();

        $panfile = "https://paymamaapp.in/admin" . $getpan->file_path;

        $panname = str_replace('/assets/images/', '', $getpan->file_path);

        $headers = array(

            "Accept: application/json",

            "Authorization: Bearer <eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2MjQ3MDczNjUsIm5iZiI6MTYyNDcwNzM2NSwianRpIjoiYTY4ODdhMWQtNWM2Ny00ZTVjLWI3YTMtMDQ0MDQyM2UxY2U0IiwiZXhwIjoxOTQwMDY3MzY1LCJpZGVudGl0eSI6ImRldi5zbWFydHBheXRlY2hub2xvZ2llc2luZGlhQGFhZGhhYXJhcGkuaW8iLCJmcmVzaCI6ZmFsc2UsInR5cGUiOiJhY2Nlc3MiLCJ1c2VyX2NsYWltcyI6eyJzY29wZXMiOlsicmVhZCJdfX0.QOJRY1IPVTErVWSK1jv0f7A98JvTi7GY60VgUVx5y-I>",

        );



        $registercustomera = Http::withHeaders([

            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2MjQ3MDczNjUsIm5iZiI6MTYyNDcwNzM2NSwianRpIjoiYTY4ODdhMWQtNWM2Ny00ZTVjLWI3YTMtMDQ0MDQyM2UxY2U0IiwiZXhwIjoxOTQwMDY3MzY1LCJpZGVudGl0eSI6ImRldi5zbWFydHBheXRlY2hub2xvZ2llc2luZGlhQGFhZGhhYXJhcGkuaW8iLCJmcmVzaCI6ZmFsc2UsInR5cGUiOiJhY2Nlc3MiLCJ1c2VyX2NsYWltcyI6eyJzY29wZXMiOlsicmVhZCJdfX0.QOJRY1IPVTErVWSK1jv0f7A98JvTi7GY60VgUVx5y-I'
        ])

            ->attach(

                'selfie',
                file_get_contents($selfiefile),
                $selfiename

            )

            ->attach(

                'id_card',
                file_get_contents($panfile),
                $panfile

            )

            ->post('https://kyc-api.aadhaarkyc.io/api/v1/face/face-match');

        $jsonDatas = $registercustomera->json();



        $deleteuser = DB::table('tbl_verification')->where('id', $id)->update(['success_score' => $jsonDatas['data']['confidence'], 'selfiemessage' => $jsonDatas['message']]);



        //$statusMsg=array('Success Score' => $jsonDatas['data']['confidence']);

        $statusMsg = "Verification Successfull";

        $success = "true";

        return $this->sendSuccess($success, $statusMsg);
    }





    public function user_short_info(Request $request)

    {



        if (preg_match('/[A-Za-z]/', $request->username)) // '/[^a-z\d]/i' should also work.

        {





            $getdetails = User::where('username', $request->username)->get(['userId', 'roleId', 'first_name', 'store_name', 'username', 'mobile']);
        } else {

            $getdetails = User::where('mobile', $request->username)->get(['userId', 'roleId', 'first_name', 'store_name', 'username', 'mobile']);
        }



        $success = "Success!!";

        return $this->sendSuccess($success, $getdetails);
    }



    public function index($name = null, Request $request)

    {

        $isspam = false;

        if ($name) {

            $request->is_spam = 1;

            $isspam = true;
        }

        if (!isset($request->role_id) && !isset($request->role_alias)) {

            $roleId = Role::getIdFromAlias(Config::get('constants.ROLE_ALIAS.RETAILER'));

            if ($roleId) {

                $request->role_id = $roleId;
            }
        }



        $userList = $this->filter($request);

        $userList = $this->modifyUserList($userList);



        // return $userList[3]['kyc_dtls'];

        $all_menu = DB::table('tbl_menu')->get();

        $allRoles = Role::where('is_deleted', Config::get('constants.NOT-DELETED'))->orderBy('last_modified_date', 'DESC')->get();

        $allPackages = PackageSetting::where('activated_status', Config::get('constants.ACTIVE'))->where('is_deleted', Config::get('constants.NOT-DELETED'))->get();

        $allServices = ServicesType::where('activated_status', Config::get('constants.ACTIVE'))->where('is_deleted', Config::get('constants.NOT-DELETED'))->get();



        return view('modules.user.user_list', compact('userList', 'allRoles', 'allPackages', 'request', 'isspam', 'allServices', 'all_menu'));
    }



    public function nonEKycUser($name = null, Request $request)

    {

        $isspam = false;

        if ($name) {

            $request->is_spam = 1;

            $isspam = true;
        }

        if (!isset($request->role_id) && !isset($request->role_alias)) {

            $roleId = Role::getIdFromAlias(Config::get('constants.ROLE_ALIAS.RETAILER'));

            if ($roleId) {

                $request->role_id = $roleId;
            }
        }



        $userList = $this->nonEKycfilter($request);

        $userList = $this->modifyEkycUserList($userList);

        // return $userList;

        // return $userList[3]['kyc_dtls'];

        $all_menu = DB::table('tbl_menu')->get();

        $allRoles = Role::where('is_deleted', Config::get('constants.NOT-DELETED'))->orderBy('last_modified_date', 'DESC')->get();

        $allPackages = PackageSetting::where('activated_status', Config::get('constants.ACTIVE'))->where('is_deleted', Config::get('constants.NOT-DELETED'))->get();

        $allServices = ServicesType::where('activated_status', Config::get('constants.ACTIVE'))->where('is_deleted', Config::get('constants.NOT-DELETED'))->get();



        return view('modules.user.user_list_ekyc', compact('userList', 'allRoles', 'allPackages', 'request', 'isspam', 'allServices', 'all_menu'));
    }



    public function checkbottomrole(Request $request)

    {

        $roleid = $request->role;

        $allRoles = Role::where('is_deleted', Config::get('constants.NOT-DELETED'))->orderBy('roleId', 'ASC')->get();

        $value = array();

        foreach ($allRoles as $id => $roles) {

            if ($roles['roleId'] == $roleid) {

                break;
            }

            $value[$id]['id'] = $roles['roleId'];

            $value[$id]['name'] = $roles['role'];
        }

        $statusMsg = $value;

        $success = "true";

        return $this->sendSuccess($success, $statusMsg);
    }





    //Fetch Data according to role in ajax request

    public function checkperrole(Request $request)

    {

        $roleid = $request->role;

        $source = $request->source;



        // if($roleid == 2)

        // {

        //     $roleid=7;

        // }

        // else if($roleid == 4)

        // {

        //     $roleid=2;

        // }

        // else if($roleid == 7)

        // {

        //     $roleid=1;

        // }

        // else

        // {

        // }

        $userList = User::where('roleId', '=', $roleid)->get();

        $value = array();

        foreach ($userList as $id => $values) {

            $value[$id]['id'] = $values['userId'];

            $value[$id]['name'] = $values['username'] . "-" . $values['first_name'];
        }

        if ($source == 0) {

            return $value;
        } else {

            $statusMsg = $value;

            $success = "true";

            return $this->sendSuccess($success, $statusMsg);
        }
    }

    public function checkperroleforedituser(Request $request)

    {

        $roleid = $request->role;

        $source = $request->source;



        $userList = User::where('roleId', '=', $roleid)->get();

        $value = array();





        foreach ($userList as $values) {

            $value[] = $values['userId'] . "-" . $values['username'] . " " . $values['first_name'];
        }

        if ($source == 0) {

            return $value;
        } else {

            $statusMsg = $value;

            $success = "true";

            return $this->sendSuccess($success, $statusMsg);
        }
    }

    //Ends Here



    //Send Reg. SMS with Link

    public function sendregsms(Request $request)

    {



        $role_id = $request->role_id;

        $source_id = $request->source;

        if ($source_id == 0) {

            $user_id = $request->user_id;

            $arr = explode("-", $user_id, 2);

            $user_id = $arr[0];



            if ($role_id == 2) {

                $getlink = "Distributor App Link : https://play.google.com/store/apps/details?id=com.paymama.retailer";
            } else if ($role_id == 4) {

                $getlink = "Retailer App Link : https://play.google.com/store/apps/details?id=com.paymama.retailer";
            } else {

                $getlink = "Master Distributor App Link : https://play.google.com/store/apps/details?id=com.paymama.retailer";
            }
        } else {

            $user_id = $request->user_name;

            if ($role_id == 7) {

                $getlink = "Distributor App Link : https://play.google.com/store/apps/details?id=com.paymama.retailer";
            } else if ($role_id == 2) {

                $getlink = "Retailer App Link : https://play.google.com/store/apps/details?id=com.paymama.retailer";
            } else {

                $getlink = "Master Distributor App Link : https://play.google.com/store/apps/details?id=com.paymama.retailer";
            }
        }

        $mobile = $request->mobile_no;



        $name = $request->name;



        //$message="Hello.$name.Welcome to PayMama Family Signup To Start Our Service's Helpline : 040-29563154,www.paymamaapp.in".$getlink;

        $message = $this->prepareRegistrationMsg($name, $mobile, $user_id);

        if ($message) {

            $regSmsTemplateId = SmsTemplate::where('alias', Config::get('constants.SMS_TEMPLATE_ALIAS.USER_REGISTRATION.name'))->first();

            $this->sendSms($message, $mobile, $regSmsTemplateId->template_id);

            $statusMsg = $message;

            $success = "Success!!";

            //$this->sendSuccess($success, $statusMsg);



            if ($source_id == 0) {

                return  back()->with('success', 'Registration Link Send Succesfully');
            } else {

                $statusMsg = "Message Successfully Send";

                $success = "true";

                return $this->sendSuccess($success, $statusMsg);
            }
        } else {

            if ($source_id == 0) {

                return  back()->with('success', 'Registration Link Not Send');
            } else {

                $statusMsg = "Message Successfully Send";

                $success = "false";

                return $this->sendSuccess($success, $statusMsg);
            }
        }
    }

    //Ends Here





    public function ocr_verification(Request $request)

    {





        /*After all condition full fill, Send image front to OCR Verification*/

        $aadhar_front = $request->file('aadhar_front');

        $aadhar_frontname = $request->file('aadhar_front')->getClientOriginalName();

        $aadhar_back = $request->file('aadhar_back');

        $headers = array(

            "Accept: application/json",

            "Authorization: Bearer <eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2MjQ3MDczNjUsIm5iZiI6MTYyNDcwNzM2NSwianRpIjoiYTY4ODdhMWQtNWM2Ny00ZTVjLWI3YTMtMDQ0MDQyM2UxY2U0IiwiZXhwIjoxOTQwMDY3MzY1LCJpZGVudGl0eSI6ImRldi5zbWFydHBheXRlY2hub2xvZ2llc2luZGlhQGFhZGhhYXJhcGkuaW8iLCJmcmVzaCI6ZmFsc2UsInR5cGUiOiJhY2Nlc3MiLCJ1c2VyX2NsYWltcyI6eyJzY29wZXMiOlsicmVhZCJdfX0.QOJRY1IPVTErVWSK1jv0f7A98JvTi7GY60VgUVx5y-I>",

        );



        $registercustomera = Http::withHeaders([

            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2MjQ3MDczNjUsIm5iZiI6MTYyNDcwNzM2NSwianRpIjoiYTY4ODdhMWQtNWM2Ny00ZTVjLWI3YTMtMDQ0MDQyM2UxY2U0IiwiZXhwIjoxOTQwMDY3MzY1LCJpZGVudGl0eSI6ImRldi5zbWFydHBheXRlY2hub2xvZ2llc2luZGlhQGFhZGhhYXJhcGkuaW8iLCJmcmVzaCI6ZmFsc2UsInR5cGUiOiJhY2Nlc3MiLCJ1c2VyX2NsYWltcyI6eyJzY29wZXMiOlsicmVhZCJdfX0.QOJRY1IPVTErVWSK1jv0f7A98JvTi7GY60VgUVx5y-I'
        ])

            ->attach(

                'file',
                file_get_contents($aadhar_front),
                $aadhar_frontname

            )



            ->post('https://kyc-api.aadhaarkyc.io/api/v1/ocr/aadhaar');

        $jsonDatas = $registercustomera->json();



        $aadhar_name = $jsonDatas['data']['ocr_fields'][0]['full_name']['value'];

        $aadhar_number = $jsonDatas['data']['ocr_fields'][0]['aadhaar_number']['value'];

        /*After all condition full fill, Send Back image front to OCR Verification*/

        $aadhar_front = $request->file('aadhar_front');

        $aadhar_backname = $request->file('aadhar_back')->getClientOriginalName();

        $aadhar_back = $request->file('aadhar_back');

        $headers = array(

            "Accept: application/json",

            "Authorization: Bearer <eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2MjQ3MDczNjUsIm5iZiI6MTYyNDcwNzM2NSwianRpIjoiYTY4ODdhMWQtNWM2Ny00ZTVjLWI3YTMtMDQ0MDQyM2UxY2U0IiwiZXhwIjoxOTQwMDY3MzY1LCJpZGVudGl0eSI6ImRldi5zbWFydHBheXRlY2hub2xvZ2llc2luZGlhQGFhZGhhYXJhcGkuaW8iLCJmcmVzaCI6ZmFsc2UsInR5cGUiOiJhY2Nlc3MiLCJ1c2VyX2NsYWltcyI6eyJzY29wZXMiOlsicmVhZCJdfX0.QOJRY1IPVTErVWSK1jv0f7A98JvTi7GY60VgUVx5y-I>",

        );



        $registercustomera = Http::withHeaders([

            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2MjQ3MDczNjUsIm5iZiI6MTYyNDcwNzM2NSwianRpIjoiYTY4ODdhMWQtNWM2Ny00ZTVjLWI3YTMtMDQ0MDQyM2UxY2U0IiwiZXhwIjoxOTQwMDY3MzY1LCJpZGVudGl0eSI6ImRldi5zbWFydHBheXRlY2hub2xvZ2llc2luZGlhQGFhZGhhYXJhcGkuaW8iLCJmcmVzaCI6ZmFsc2UsInR5cGUiOiJhY2Nlc3MiLCJ1c2VyX2NsYWltcyI6eyJzY29wZXMiOlsicmVhZCJdfX0.QOJRY1IPVTErVWSK1jv0f7A98JvTi7GY60VgUVx5y-I'
        ])

            ->attach(

                'file',
                file_get_contents($aadhar_back),
                $aadhar_backname

            )



            ->post('https://kyc-api.aadhaarkyc.io/api/v1/ocr/aadhaar');

        $jsonDatas = $registercustomera->json();

        $aadhar_address = $jsonDatas['data']['ocr_fields'][0]['address']['value'];



        if ($request->user_id == '') {

            $statusMsg = "User ID Missing";

            $success = "failure";

            $status = "false";

            return Response::json(array('status' => $status, 'message' => $statusMsg));
        }



        if ($request->hasFile('aadhar_front')) {
        } else {

            $statusMsg = "Aadhar Front File Missing";

            $success = "failure";

            $status = "false";

            return Response::json(array('status' => $status, 'message' => $statusMsg));
        }



        if ($request->hasFile('aadhar_back')) {
        } else {

            $statusMsg = "Aadhar Back File Missing";

            $success = "failure";

            $status = "false";

            return Response::json(array('status' => $status, 'message' => $statusMsg));
        }



        $aadhar_front = $request->file('aadhar_front');

        $fileName = time() . 'aadharfront.' . $aadhar_front->extension();



        $movedfile = $aadhar_front->move(public_path() . "/storage/app/public/uploads/", $fileName);

        $saveaadharfront = File::create(['file_path' => '/storage/app/public/uploads/', 'name' => $fileName]);

        $aadharfrontid = $saveaadharfront->id;

        $statusMsg = "Aadhar Front File Successfully Uploaded";

        $success = "success";

        $status = "true";

        if ($movedfile) {
        } else {

            return Response::json(array('status' => 'failed', 'message' => 'File Not uploaded to Drive'));
        }



        $aadhar_back = $request->file('aadhar_back');

        $fileNames = time() . 'aadharback.' . $aadhar_back->extension();

        $movedfileback = $aadhar_back->move(public_path() . "/storage/app/public/uploads/", $fileNames);

        $saveaadharback = File::create(['file_path' => '/storage/app/public/uploads/', 'name' => $fileNames]);

        $aadharbackid = $saveaadharback->id;

        $statusMsg = "Aadhar Back File Successfully Uploaded";

        $success = "success";

        $status = true;

        if ($movedfileback) {
        } else {

            return Response::json(array('status' => 'failed', 'message' => 'Back File Not uploaded to Drive'));
        }





        $deleteuser = DB::table('tbl_verification')->where('id', $request->user_id)->update(['is_ocr' => '1', 'aadhar_front' => $aadharfrontid, 'aadhar_back' => $aadharbackid, 'aadhar_name' => $aadhar_name, 'aadhar_number' => $aadhar_number, 'aadhar_address' => $aadhar_address]);

        if ($deleteuser == 1) {

            $statusMsg = "IMAGE'S SUCCESSFULLY UPLOADED";

            $success = "success";

            $status = "true";

            return Response::json(array('status' => $status, 'message' => $statusMsg));
        } else {

            $statusMsg = "Aadhar Authentication Failure";

            $success = "failure";

            $status = "false";

            return Response::json(array('status' => $status, 'message' => $statusMsg));
        }

        //$statusMsg=array('Success Score' => $jsonDatas['data']['confidence']);



    }

    public function verification_list($name = null, $roles = null, Request $request)

    {



        $isspam = false;

        if ($name) {

            $request->is_spam = 1;

            $isspam = true;
        }

        if (!isset($request->role_id) && !isset($request->role_alias)) {

            $roleId = Role::getIdFromAlias(Config::get('constants.ROLE_ALIAS.RETAILER'));

            if ($roleId) {

                $request->role_id = $roleId;
            }
        }



        $userList = $this->filter($request);

        $userList = $this->modifyUserList($userList);



        // return $userList[3]['kyc_dtls'];

        //$all_menu = DB::table('tbl_menu')->get();

        //$filtersList = $this->setFilterList($loggedInRole, $serviceType);

        $allRoles = Role::where('is_deleted', Config::get('constants.NOT-DELETED'))->orderBy('last_modified_date', 'DESC')->get();

        //$allPackages = PackageSetting::where('activated_status', Config::get('constants.ACTIVE'))->where('is_deleted', Config::get('constants.NOT-DELETED'))->get();

        //$allServices = ServicesType::where('activated_status', Config::get('constants.ACTIVE'))->where('is_deleted', Config::get('constants.NOT-DELETED'))->get();



        //$data = $this->verificationfilter($request);

        $data = Verification::orderBy('id', 'DESC');

        if ($request->is_spam) {

            $data = Verification::where('isSpam', Config::get('constants.SPAM'));
        } else {

            $data = Verification::where('isSpam', Config::get('constants.NOT-SPAM'));
        }

        if ($request->roles) {

            $data = Verification::where('roleID', $request->roles)->orderBy('id', 'DESC');
        } else {

            $data = Verification::orderBy('id', 'DESC');
        }

        // $data=$data->where('isDeleted','0');

        $PicPath = File::all();



        $data = $data->get();



        return view('modules.user.verification_list', compact('data', 'allRoles', 'request', 'isspam'));
    }

    /*Verification Filter Starts*/

    public function verificationfilter($request)

    {

        if ($request->from_date != '') {

            $filter1 = "->where($request->from_date,>=,'created_date')";
        } else {

            $filter1 = '';
        }

        if ($request->to_date != '') {

            $filter2 = "->where('created_date',>=,$request->from_date)";
        } else {

            $filter2 = '';
        }

        return $filter = $filter1 . $filter2;
    }



    /*Verification Filter Ends*/

    /**

     * Filter User Data

     */

    public function filter($request)

    {

        $userList = User::with(['ekyc'])->where('isDeleted', Config::get('constants.NOT-DELETED'))

            // ->where('isSpam', Config::get('constants.NOT-SPAM'))

            ->where('userId', '!=', '1')

            ->orderBy('createdDtm', 'DESC')

            ->orderBy('updatedDtm', 'DESC');

        if ($request->is_spam) {

            $userList = $userList->where('isSpam', Config::get('constants.SPAM'));
        } else {

            $userList = $userList->where('isSpam', Config::get('constants.NOT-SPAM'));
        }

        if (isset($request->role_id) && $request->role_id) {

            $userList->where('roleId', $request->role_id);
        }



        // if ($userList) {

        //     if (Auth::userRoleAlias() == Config::get('constants.ROLE_ALIAS.DISTRIBUTOR')) {

        //         $childResponse = User::where('parent_user_id', Auth::id())->pluck('userId');

        //         if (count($childResponse) > 0) {

        //             $childResponse = $childResponse->toArray();

        //             $userList->whereIn('tbl_users.userId', $childResponse);

        //         }

        //     }

        // }



        if (Auth::userRoleAlias() != Config::get('constants.ROLE_ALIAS.SYSTEM_ADMIN')) {

            $userList->where('tbl_users.parent_user_id', Auth::id());

            $userList->where('tbl_users.parent_role_id', Auth::user()->roleId);
        }



        if (isset($request->role_alias) && $request->role_alias) {

            $roleId = Role::getIdFromAlias($request->role_alias);

            if ($roleId) {

                $userList->where('tbl_users.roleId', $roleId);
            }
        }



        return $userList->get();
    }



    public function nonEKycfilter($request)

    {

        $userList = User::with(['ekyc', 'role', 'storecategory', 'parentuser'])->where('isDeleted', Config::get('constants.NOT-DELETED'))

            // ->where('isSpam', Config::get('constants.NOT-SPAM'))

            ->where('userId', '!=', '1')

            ->whereIn('roleId', [Config::get('constants.RETAILER'), Config::get('constants.DISTRIBUTOR')])

            ->orderBy('createdDtm', 'DESC')

            ->orderBy('updatedDtm', 'DESC');

        if ($request->is_spam) {

            $userList = $userList->where('isSpam', Config::get('constants.SPAM'));
        } else {

            $userList = $userList->where('isSpam', Config::get('constants.NOT-SPAM'));
        }

        if (isset($request->role_id) && $request->role_id) {

            $userList->where('roleId', $request->role_id);
        }



        if (Auth::userRoleAlias() != Config::get('constants.ROLE_ALIAS.SYSTEM_ADMIN')) {

            $userList->where('tbl_users.parent_user_id', Auth::id());

            $userList->where('tbl_users.parent_role_id', Auth::user()->roleId);
        }



        if (isset($request->role_alias) && $request->role_alias) {

            $roleId = Role::getIdFromAlias($request->role_alias);

            if ($roleId) {

                $userList->where('tbl_users.roleId', $roleId);
            }
        }



        $userList = $userList->get();

        foreach ($userList as $id => $user) {

            if ($user->ekyc != null && isset($user->ekyc->complete_kyc) && $user->ekyc->complete_kyc == '1') {

                unset($userList[$id]);
            }
        }



        return $userList;
    }



    /**

     * Modify User List Data

     */

    public function modifyUserList($userList)

    {

        $result = [];

        if ($userList) {

            foreach ($userList as $i => $user) {

                $userList[$i]['role_name'] = Role::where('roleId', $user->parent_role_id)->pluck('role')->first();

                $userList[$i]['parent_role_name'] = Role::where('roleId', $user->roleId)->pluck('role')->first();

                $userList[$i]['parent_store_name'] = User::where('userId', $user->parent_user_id)->pluck('store_name')->first();

                $userList[$i]['store_category_name'] = StoreCategory::where('id', $user->store_category_id)->pluck('store_category_name')->first();

                $userList[$i]['parent_user_first_name'] = User::where('userId', $user->parent_user_id)->pluck('first_name')->first();

                $userList[$i]['parent_user_last_name'] = User::where('userId', $user->parent_user_id)->pluck('last_name')->first();

                $userList[$i]['package_name'] = PackageSetting::where('package_id', $user->package_id)->pluck('package_name')->first();

                $userList[$i]['state_name'] = state::where('state_id', $user->state_id)->pluck('state_name')->first();

                $userList[$i]['city_name'] = City::where('city_id', $user->district_id)->pluck('city_name')->first();

                // $userList[$i]['ekyc'] = Ekyc::where('user_id',$user->userId)->first();
                $userList[$i]['parentuser'] = User::with(['ekyc'])->where('userId', $user->parent_user_id)->first();



                $userList[$i]['last_activity'] = $this->getUserLastActive($user->userId);

                $userList[$i]['kyc_dtls'] = $this->getUserKycStatus($user->userId);

                $userList[$i]['user_services'] = json_encode($this->getUserServicesByUserId($user->userId));

                $userList[$i]['pg_options'] = json_encode($this->getUserPgServicesByUserId($user->userId));
            }

            $result = $userList;
        }

        return $result;
    }



    public function modifyEkycUserList($userList)

    {

        $result = [];

        if ($userList) {

            foreach ($userList as $i => $user) {

                $userList[$i]['parentuser']['ekyc'] = Ekyc::where('user_id', $user->parentuser->userId)->first();


                // $userList[$i]['role_name'] = $user->role->role;

                // $userList[$i]['parent_role_name'] = $user->role->role;

                // $userList[$i]['parent_store_name'] = $user->store_name;

                // $userList[$i]['store_category_name'] = $user->storecategory->store_category_name ?? "";

                // $userList[$i]['parent_user_first_name'] = $user->parentuser->first_name;

                // $userList[$i]['parent_user_last_name'] = $user->parentuser->last_name;

                // $userList[$i]['package_name'] = PackageSetting::where('package_id',$user->package_id)->pluck('package_name')->first();

                // $userList[$i]['state_name'] = state::where('state_id',$user->state_id)->pluck('state_name')->first();

                // $userList[$i]['city_name'] = City::where('city_id',$user->district_id)->pluck('city_name')->first();



                $userList[$i]['last_activity'] = $this->getUserLastActive($user->userId);

                // $userList[$i]['kyc_dtls'] = $user->nonkyc;

            }

            $result = $userList;
        }

        return $result;
    }



    /**

     * Get User Kyc status

     */

    public function getUserKycStatus($userId)

    {

        $kycDtls = "";



        $kycRes = KycDetail::where('user_id', $userId)

            ->with(['panFile', 'aadharFrontFile', 'aadharBackFile', 'photoFrontFile', 'photoInnerFile'])

            ->get();



        if (isset($kycRes) && count($kycRes) > 0) {

            $kycDtls = $kycRes[0];
        }



        return $kycDtls;
    }



    /**

     * Get User Last activity by providing user id

     */

    public function getUserLastActive($userId)

    {

        $lastActiveDt = "";



        $lastActiveDate = TransactionDetail::where('user_id', $userId)->orderBy('trans_date', 'DESC')->first();



        if ($lastActiveDate) {

            $lastActiveDt = $lastActiveDate->trans_date;
        }



        return $lastActiveDt;
    }



    //Send Msg And User Registration



    public function createnew($id = null, Request $request)

    {



        $logggedRole = Auth::user()->roleId;

        // if($logggedRole == Config::get('constants.RETAILER')){

        //     if($id != Auth::user()->userId){

        //           return  redirect('/permission-denied');

        //     }

        // }

        // if($logggedRole != Config::get('constants.ADMIN')){

        //     if(($logggedRole == Config::get('constants.RETAILER')) && ($id != Auth::user()->userId)){

        //         return  redirect('/permission-denied');

        //     }elseif($id != Auth::user()->userId){

        //             $check_User = User::where('parent_user_id', Auth::user()->userId)

        //                                 ->where('userId', $id)->get();

        //             if(count($check_User)==0){

        //                 return  redirect('/permission-denied');

        //             }

        //     }

        // }









        $userById = [];

        $notAdminUserId = "";

        // check if logged in user is any other than Admin

        if (isset($request->role_alias) && $request->role_alias == Config::get('constants.ROLE_ALIAS.RETAILER')) {

            $notAdminUserId = Auth::id();

            $userById = [

                'roleId' => Role::getIdFromAlias(Config::get('constants.ROLE_ALIAS.RETAILER')),

                'parent_role_id' => Auth::user()->roleId,

                'parent_user_id' => Auth::id(),

            ];
        } elseif (isset($request->role_alias) && $request->role_alias == Config::get('constants.ROLE_ALIAS.FOS')) {

            $notAdminUserId = Auth::id();

            $userById = [

                'roleId' => Role::getIdFromAlias(Config::get('constants.ROLE_ALIAS.FOS')),

                'parent_role_id' => Auth::user()->roleId,

                'parent_user_id' => Auth::id(),

            ];
        }



        if ($id) {



            $userById = User::where('userId', $id)->first();

            //Can not update user 

            if ($logggedRole != Config::get('constants.ADMIN')) {

                if (($logggedRole == Config::get('constants.RETAILER')) && ($id != Auth::user()->userId)) {

                    return  redirect('/permission-denied');
                } elseif ($id != Auth::user()->userId) {

                    $check_User = User::where('parent_user_id', Auth::user()->userId)

                        ->where('userId', $id)->get();

                    if (count($check_User) == 0) {

                        return  redirect('/permission-denied');
                    }
                }
            }
        } elseif ($request->user_id) {

            $userById = User::where('userId', $request->user_id)->first();
        }



        $allStates = State::all();

        $allCities = City::all();

        $storeCategories = StoreCategory::where('is_deleted', Config::get('constants.NOT-DELETED'))->where('activated_status', Config::get('constants.ACTIVE'))->get();

        $allPackages = PackageSetting::where('activated_status', Config::get('constants.ACTIVE'))->where('is_deleted', Config::get('constants.NOT-DELETED'))->get();

        $rolesExceptAdmin = Role::where('roleId', '!=', Config::get('constants.ADMIN'))->get();

        $allRoles = Role::where('is_deleted', Config::get('constants.NOT-DELETED'))->get();

        $allServices = ServicesType::where('activated_status', Config::get('constants.ACTIVE'))->where('is_deleted', Config::get('constants.NOT-DELETED'))->get();

        return view('modules.user.createnew', compact('rolesExceptAdmin', 'allRoles', 'allServices', 'userById', 'allPackages', 'allStates', 'allCities', 'storeCategories', 'notAdminUserId'));
    }

    //Send Msg And User Registration Ends Here



    /**

     * Member create page

     */

    public function updateuser(Request $request)

    {



        // return $request->all();



        $ids = $request->ids;



        $options = array(

            "upi" => array(

                'status' => isset($request->upi_status) ? $request->upi_status : 0,

                'mode' => 'UPI',

                'charge' => '' . isset($request->upi_charge_charge) ? $request->upi_charge_charge : 0,

                'type' => '%'

            ),

            "rupay_card" => array(

                'status' => isset($request->rupay_card_status) ? $request->rupay_card_status : 0,

                'mode' => 'RUPAY_CARD',

                'charge' => '' . isset($request->rupay_card_charge) ? $request->rupay_card_charge : 0,

                'type' => '%'

            ),

            "debit_card" => array(

                'status' => isset($request->debit_card_status) ? $request->debit_card_status : 0,

                'mode' => 'DEBIT_CARD',

                'charge' => '' . isset($request->debit_card_charge) ? $request->debit_card_charge : 0,

                'type' => '%'

            ),

            "credit_card" => array(

                'status' => isset($request->credit_card_status) ? $request->credit_card_status : 0,

                'mode' => 'CREDIT_CARD',

                'charge' => '' . isset($request->credit_card_charge) ? $request->credit_card_charge : 0,

                'type' => '%'

            ),

            "prepaid_card" => array(

                'status' => isset($request->prepaid_card_status) ? $request->prepaid_card_status : 0,

                'mode' => 'PREPAID_CARD',

                'charge' => '' . isset($request->prepaid_card_charge) ? $request->prepaid_card_charge : 0,

                'type' => '%'

            ),

            "corporate_card" => array(

                'status' => isset($request->corporate_card_status) ? $request->corporate_card_status : 0,

                'mode' => 'CORPORATE_CARD',

                'charge' => '' . isset($request->corporate_card_charge) ? $request->corporate_card_charge : 0,

                'type' => '%'

            ),

            "net_banking" => array(

                'status' => isset($request->net_banking_status) ? $request->net_banking_status : 0,

                'mode' => 'NET_BANKING',

                'charge' => '' . isset($request->net_banking_charge) ? $request->net_banking_charge : 0,

                'type' => '%'

            ),

            "wallet" => array(

                'status' => isset($request->wallet_status) ? $request->wallet_status : 0,

                'mode' => 'WALLET',

                'charge' => '' . isset($request->wallet_charge) ? $request->wallet_charge : 0,

                'type' => '%'

            ),

        );



        $updateDetails = [

            'first_name' =>  $request->user_full_name,

            'bank_account_name' => $request->full_name,

            'mobile' => $request->mobile,

            'email' => $request->email_id,

            'telegram_no' => $request->telegram_id,

            'va_account_number' => $request->virtual_account_no,

            'va_ifsc_code' => $request->ifsc_code,

            'va_upi_id' => $request->virtual_upi_address,

            'va_id' => $request->virtual_account_id,

            'roleId' => $request->usertype,

            'parent_role_id' => $request->parent_role_id,

            'parent_user_id' => $request->parent_user_id,

            'fos_id' => $request->fos,

            'max_amount_deposit' => $request->max_amount_deposit,

            'min_amount_deposit' => $request->min_amount_deposit,

            'min_balance' => $request->min_balance,

            'business_name' => $request->business_name,

            'business_address' => $request->business_address,

            'state_id' => $request->state_name,

            // 'district_id' =>$request->city_name,

            'zip_code' => $request->pincode,

            'package_id' => $request->package,

            //   'store_category_id' =>$request->category_name,

            'store_name' => $request->business_name,

            'pan_number' => $request->pan_no,

            'aadhar_number' => $request->aadhaar_no,
            'aadhar_name' => $request->aadhar_name,

            'pan_name' => $request->pan_name,

            'pg_status' => $request->pg_status ?? 0,

            'pg_options' => json_encode($options)

        ];



        $ekycupdate = [

            'business_name' => $request->business_name,

            'business_address' => $request->business_address,

            'state' => $request->state_name,

            'city' => $request->city_name,

            'pincode' => $request->pincode,

            'category' => $request->category_name

        ];

        $ekycuserupdate = Ekyc::where('user_id', $ids)->update($ekycupdate);

        $userupdate = User::where('userId', $ids)->update($updateDetails);



        if ($userupdate) {

            return back()->with('success', 'User Updated Succesfully');
        } else {

            return back()->with('success', 'User Updated Succesfully');
        }
    }



    public function updateuserqr(Request $request)

    {

        $ids = $request->user_id;

        $userupdate = User::where('userId', $ids)->first();



        $qr_id = $userupdate->qr_id ?? "";

        $vpa = $userupdate->va_upi_id;



        if (!empty($vpa)) {

            if (empty($qr_id)) {

                $data = 'name=' . $userupdate->store_name . '&vpa=' . $vpa . '&show_name=true&show_upi=false&type=UPI&logo_type=round&template_id=qr_paymama&'; //customtemplate id given by apiclub

            } else {

                $data = 'name=' . $userupdate->store_name . '&vpa=' . $vpa . '&show_name=true&show_upi=false&type=UPI&logo_type=round&template_id=qr_paymama&update_id=' . $qr_id; //customtemplate id given by apiclub

            }

            $curl = curl_init();

            curl_setopt_array($curl, array(

                CURLOPT_URL => 'https://api.apiclub.in/api/v1/generate_qr',

                CURLOPT_RETURNTRANSFER => true,

                CURLOPT_ENCODING => '',

                CURLOPT_MAXREDIRS => 10,

                CURLOPT_TIMEOUT => 0,

                CURLOPT_FOLLOWLOCATION => true,

                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,

                CURLOPT_CUSTOMREQUEST => 'POST',

                CURLOPT_POSTFIELDS => $data,

                CURLOPT_HTTPHEADER => array(

                    'Referer: ' . Config::get('constants.WEBSITE_BASE_URL'),

                    'API-KEY: ' . Config::get('constants.APICLUB_API_KEY'),

                    'Content-Type: application/x-www-form-urlencoded'

                ),

            ));



            $response = curl_exec($curl);

            curl_close($curl);

            $resp = json_decode($response, true);

            if (isset($resp['code']) && $resp['code'] == 200 && $resp['status'] == 'success') {

                $qr_id = $resp['response']['qr_id'];

                $userupdate = $userupdate->update(['qr_id' => $qr_id]);

                if ($userupdate) {

                    return back()->with('success', 'QR Code Updated Succesfully');
                } else {

                    return back()->with('error', 'QR Code Update failed');
                }
            } else {

                return back()->with('error', $resp['response'] ?? "Generate failed");
            }
        } else {

            return back()->with('error', 'UPI not generated yet');
        }
    }



    public function updateretailerid(Request $request)

    {



        if ($request->source != 1) {

            $ids = $request->ids;



            $updateDetails = [

                'telegram_no' => $request->telegram_id,

            ];



            $userupdate = User::where('userId', $ids)->update($updateDetails);



            if ($userupdate) {

                return back()->with('success', 'User Updated Succesfully');
            } else {

                return back()->with('success', 'User Updated Succesfully');
            }
        } else {

            $ids = $request->user_id;



            $updateDetails = [

                'telegram_no' => $request->telegram_no,

            ];



            $userupdate = User::where('userId', $ids)->update($updateDetails);



            $statusMsg = "User Updated Succesfully";

            $success = "Success!!";

            return $this->sendSuccess($success, $statusMsg);
        }
    }

    public function create($id = null, Request $request)

    {



        $logggedRole = Auth::user()->roleId;

        // if($logggedRole == Config::get('constants.RETAILER')){

        //     if($id != Auth::user()->userId){

        //           return  redirect('/permission-denied');

        //     }

        // }

        // if($logggedRole != Config::get('constants.ADMIN')){

        //     if(($logggedRole == Config::get('constants.RETAILER')) && ($id != Auth::user()->userId)){

        //         return  redirect('/permission-denied');

        //     }elseif($id != Auth::user()->userId){

        //             $check_User = User::where('parent_user_id', Auth::user()->userId)

        //                                 ->where('userId', $id)->get();

        //             if(count($check_User)==0){

        //                 return  redirect('/permission-denied');

        //             }

        //     }

        // }









        $userById = [];

        $notAdminUserId = "";

        // check if logged in user is any other than Admin

        if (isset($request->role_alias) && $request->role_alias == Config::get('constants.ROLE_ALIAS.RETAILER')) {

            $notAdminUserId = Auth::id();

            $userById = [

                'roleId' => Role::getIdFromAlias(Config::get('constants.ROLE_ALIAS.RETAILER')),

                'parent_role_id' => Auth::user()->roleId,

                'parent_user_id' => Auth::id(),

            ];
        } elseif (isset($request->role_alias) && $request->role_alias == Config::get('constants.ROLE_ALIAS.FOS')) {

            $notAdminUserId = Auth::id();

            $userById = [

                'roleId' => Role::getIdFromAlias(Config::get('constants.ROLE_ALIAS.FOS')),

                'parent_role_id' => Auth::user()->roleId,

                'parent_user_id' => Auth::id(),

            ];
        }



        if ($id) {



            $userById = User::with(['ekyc'])->where('userId', $id)->first();

            //Can not update user 

            if ($logggedRole != Config::get('constants.ADMIN')) {

                if (($logggedRole == Config::get('constants.RETAILER')) && ($id != Auth::user()->userId)) {

                    return  redirect('/permission-denied');
                } elseif ($id != Auth::user()->userId) {

                    $check_User = User::where('parent_user_id', Auth::user()->userId)

                        ->where('userId', $id)->get();

                    if (count($check_User) == 0) {

                        return  redirect('/permission-denied');
                    }
                }
            }
        } elseif ($request->user_id) {

            $userById = User::with(['ekyc'])->where('userId', $request->user_id)->first();
        }



        $allStates = State::all();

        $allCities = City::all();

        $allUser = User::with(['ekyc']);

        $storeCategories = StoreCategory::where('is_deleted', Config::get('constants.NOT-DELETED'))->where('activated_status', Config::get('constants.ACTIVE'))->get();

        $allPackages = PackageSetting::where('activated_status', Config::get('constants.ACTIVE'))->where('is_deleted', Config::get('constants.NOT-DELETED'))->get();

        $rolesExceptAdmin = Role::where('roleId', '!=', Config::get('constants.ADMIN'))->get();

        $allRoles = Role::where('is_deleted', Config::get('constants.NOT-DELETED'))->get();

        $allServices = ServicesType::where('activated_status', Config::get('constants.ACTIVE'))->where('is_deleted', Config::get('constants.NOT-DELETED'))->get();

        // return $allServices;

        return view('modules.user.create', compact('allUser', 'rolesExceptAdmin', 'allRoles', 'allServices', 'userById', 'allPackages', 'allStates', 'allCities', 'storeCategories', 'notAdminUserId'));
    }

    public function editforretailer($id = null, Request $request)

    {



        $logggedRole = Auth::user()->roleId;

        // if($logggedRole == Config::get('constants.RETAILER')){

        //     if($id != Auth::user()->userId){

        //           return  redirect('/permission-denied');

        //     }

        // }

        // if($logggedRole != Config::get('constants.ADMIN')){

        //     if(($logggedRole == Config::get('constants.RETAILER')) && ($id != Auth::user()->userId)){

        //         return  redirect('/permission-denied');

        //     }elseif($id != Auth::user()->userId){

        //             $check_User = User::where('parent_user_id', Auth::user()->userId)

        //                                 ->where('userId', $id)->get();

        //             if(count($check_User)==0){

        //                 return  redirect('/permission-denied');

        //             }

        //     }

        // }









        $userById = [];

        $notAdminUserId = "";

        // check if logged in user is any other than Admin

        if (isset($request->role_alias) && $request->role_alias == Config::get('constants.ROLE_ALIAS.RETAILER')) {

            $notAdminUserId = Auth::id();

            $userById = [

                'roleId' => Role::getIdFromAlias(Config::get('constants.ROLE_ALIAS.RETAILER')),

                'parent_role_id' => Auth::user()->roleId,

                'parent_user_id' => Auth::id(),

            ];
        } elseif (isset($request->role_alias) && $request->role_alias == Config::get('constants.ROLE_ALIAS.FOS')) {

            $notAdminUserId = Auth::id();

            $userById = [

                'roleId' => Role::getIdFromAlias(Config::get('constants.ROLE_ALIAS.FOS')),

                'parent_role_id' => Auth::user()->roleId,

                'parent_user_id' => Auth::id(),

            ];
        }



        if ($id) {



            $userById = User::where('userId', $id)->first();

            //Can not update user 

            if ($logggedRole != Config::get('constants.ADMIN')) {

                if (($logggedRole == Config::get('constants.RETAILER')) && ($id != Auth::user()->userId)) {

                    return  redirect('/permission-denied');
                } elseif ($id != Auth::user()->userId) {

                    $check_User = User::where('parent_user_id', Auth::user()->userId)

                        ->where('userId', $id)->get();

                    if (count($check_User) == 0) {

                        return  redirect('/permission-denied');
                    }
                }
            }
        } elseif ($request->user_id) {

            $userById = User::where('userId', $request->user_id)->first();
        }



        $allStates = State::all();

        $allCities = City::all();

        $allUser = User::all();

        $storeCategories = StoreCategory::where('is_deleted', Config::get('constants.NOT-DELETED'))->where('activated_status', Config::get('constants.ACTIVE'))->get();

        $allPackages = PackageSetting::where('activated_status', Config::get('constants.ACTIVE'))->where('is_deleted', Config::get('constants.NOT-DELETED'))->get();

        $rolesExceptAdmin = Role::where('roleId', '!=', Config::get('constants.ADMIN'))->get();

        $allRoles = Role::where('is_deleted', Config::get('constants.NOT-DELETED'))->get();

        $allServices = ServicesType::where('activated_status', Config::get('constants.ACTIVE'))->where('is_deleted', Config::get('constants.NOT-DELETED'))->get();

        return view('modules.user.editforretailer', compact('allUser', 'rolesExceptAdmin', 'allRoles', 'allServices', 'userById', 'allPackages', 'allStates', 'allCities', 'storeCategories', 'notAdminUserId'));
    }



    /**

     * Storing user details into database

     */

    public function store($id = null, Request $request)

    {





        $validator = Validator::make($request->all(), [

            'first_name' => 'required|string|max:255',

            'last_name' => 'required|string|max:255',

            'email' => 'required|string|email|max:255|unique:tbl_users,userId',

            'roleId' => 'required|numeric',



            'parent_role_id' => 'required|numeric',

            'package_id' => 'required|numeric',



            // 'mpin' => 'numeric|unique:tbl_users,userId',

            'mobile_no' => 'numeric|min:10|unique:tbl_users,userId',

            // 'password' => 'required|string|min:6|confirmed',

        ]);



        if ($validator->fails()) {

            return response()->json($validator->errors()->toJson(), 400);
        }



        $mpin = rand(1000, 9999);

        $password = rand(100000, 999999);

        $username = $this->generateAutoUsername($request->get('roleId'));



        if ($id) {



            $resultById = User::find((int) $id);



            $resultById->first_name = $request->get('first_name');

            $resultById->last_name = $request->get('last_name');

            $resultById->email = $request->get('email');

            $resultById->username = $resultById->username ? $resultById->username : $username;

            $resultById->mpin = $resultById->mpin ? $resultById->mpin : $mpin;

            $resultById->password = $resultById->password ? $resultById->password : Hash::make($password);

            $resultById->mobile = $request->get('mobile');

            $resultById->alternate_mob_no = $request->get('alternate_mob_no') ? $request->get('alternate_mob_no') : '';



            $resultById->pan_no = $request->get('pan_no');

            $resultById->aadhar_no = $request->get('aadhar_no');

            // $resultById->gst_no = $request->get('gst_no');

            // $resultById->whatsapp_no = $request->get('whatsapp_no');

            $resultById->telegram_no = $request->get('telegram_no');



            $resultById->min_balance = $request->get('min_balance');

            $resultById->roleId = $request->get('roleId');

            $resultById->parent_role_id = $request->get('parent_role_id');

            $resultById->parent_user_id = $request->get('parent_user_id');

            $resultById->package_id = $request->get('package_id');

            $resultById->state_id = $request->get('state_id');

            $resultById->district_id = $request->get('district_id');

            $resultById->address = $request->get('address');

            $resultById->zip_code = $request->get('zip_code');

            $resultById->store_name = $request->get('store_name');

            $resultById->store_category_id = $request->get('store_category_id');



            $resultById->fos_id = (isset($request->fos_id)) ? ($request->fos_id == '?') ? null : $request->fos_id  : null;

            $resultById->min_amount_withdraw = $request->get('min_amount_withdraw');

            $resultById->min_amount_deposit = $request->get('min_amount_deposit');

            $resultById->max_amount_withdraw = $request->get('max_amount_withdraw');

            $resultById->max_amount_deposit = $request->get('max_amount_deposit');



            $response = $resultById->save();



            if ($response) {

                if ($resultById->roleId == Config::get('constants.ADMIN')) {

                    return redirect('/user_list')->with('success', 'User updated!');
                } else {

                    return redirect('/home')->with('success', 'User updated!');
                }
            }
        } else {

            $insrt_usr_arr = [

                'first_name' => $request->get('first_name'),

                'last_name' => $request->get('last_name'),

                'email' => $request->get('email'),

                'password' => Hash::make($password),

                'username' => $username,

                'mpin' => $mpin,

                'mobile' => $request->get('mobile'),

                'alternate_mob_no' => ($request->get('alternate_mob_no') ? $request->get('alternate_mob_no') : ''),



                'pan_no' => $request->get('pan_no'),

                'aadhar_no' => $request->get('aadhar_no'),

                'gst_no' => $request->get('gst_no'),

                'whatsapp_no' => $request->get('whatsapp_no'),

                'telegram_no' => $request->get('telegram_no'),



                'roleId' => $request->get('roleId'),

                'parent_role_id' => $request->get('parent_role_id'),

                'parent_user_id' => $request->get('parent_user_id'),

                'package_id' => $request->get('package_id'),

                'state_id' => $request->get('state_id'),

                'district_id' => $request->get('district_id'),

                'address' => $request->get('address'),

                'zip_code' => $request->get('zip_code'),

                'store_name' => $request->get('store_name'),

                'store_category_id' => $request->get('store_category_id'),

                'wallet_balance' => 0,

                'commission_id' => '',

                'createdDtm' => now(),

                'createdBy' => Auth::user()->userId,

                // 'fos_id' => (isset($request->fos_id)) ? $request->fos_id : null,

                //logged in user detail

            ];





            if ($request->roleId == Config::get('constants.RETAILER')) {

                $insrt_usr_arr['fos_id'] = (isset($request->fos_id)) ? ($request->fos_id == '?') ? null : $request->fos_id  : null;
            }



            $user = User::create($insrt_usr_arr);



            if ($user) {

                if ($request->get('roleId') == Config::get('constants.RETAILER')) {



                    $this->insertAllowServices($user->userId);
                }



                $message = $this->prepareRegistrationMsg($mpin, $password, $username);

                if ($message) {

                    $smsTemplateId = SmsTemplate::where('alias', Config::get('constants.SMS_TEMPLATE_ALIAS.USER_REGISTRATION.name'))->first();

                    $msgResponse = $this->sendSms($message, $request->get('mobile'), $smsTemplateId->template_id);



                    if ($msgResponse) {

                        return redirect('/user_list')->with('success', 'Member saved!');
                    }
                }
            }
        }
    }



    /**

     * Prepare new user registration message

     */

    public function prepareRegistrationMsg($mpin, $password, $username)

    {

        $msg = "";



        $regSmsTemplate = SmsTemplate::where('alias', Config::get('constants.SMS_TEMPLATE_ALIAS.USER_REGISTRATION.name'))->first();

        if (isset($regSmsTemplate)) {

            $msg = __($regSmsTemplate->template, [

                "username" => $username,

                "mpin" => $mpin,

                "password" => $password,

            ]);
        }

        return $msg;
    }



    /**

     * Check If value exist in database

     */

    public function checkUserValueExists(Request $request)

    {

        $columnRes = User::where($request['column'], $request[$request['column']])->get();

        if (count($columnRes) > 0) {

            if (isset($request->id) && $request->id) {

                $isSameUser = User::where('userID', $request->id)->where($request['column'], $request[$request['column']])->get();

                if (count($isSameUser) > 0) {

                    return response()->json("true");
                } else {

                    return response()->json(0);
                }
            } else {

                return response()->json(0);
            }
        }

        return response()->json("true");
    }



    /**

     *Verify User's entered Mpin

     */

    public function verifyUserMpin(Request $request)

    {

        $response = User::where('userId', Auth::user()->userId)->where('mpin', $request->mpin)->get();



        if (count($response) == 0) {

            return response()->json(0);
        }

        return response()->json("true");
    }



    /**

     * Change Operator active status

     */

    public function changeActiveStatus(Request $request)

    {

        $id = $request->get('id');

        $activeStatus = $request->get('status');



        $setStatus = Config::get('constants.IN-ACTIVE');

        if ($activeStatus == 'true') {

            $setStatus = Config::get('constants.ACTIVE');
        }



        $result = false;

        if ($id) {

            $resultById = User::find((int) $id);

            $resultById->activated_status = $setStatus;

            $response = $resultById->save();

            if ($response) {

                $result = true;
            }
        }

        return $result;
    }



    /**

     * Get User List By Providing Parent Role Id

     */

    public function getUserFromPrntRole(Request $request)

    {

        $response = [];

        if (isset($request->parent_role_id) && $request->parent_role_id) {

            $response = User::getUserFromRole($request->parent_role_id)->get();
        }



        return $response;
    }



    /**

     * Get City List By Providing State Id

     */

    public function getCityFromStateId(Request $request)

    {

        $response = [];

        if (isset($request->state_id) && $request->state_id) {

            $response = City::getCityFromState($request->state_id)->get();
        }



        return $response;
    }



    /**

     * Generate Auto User Code

     */

    public function generateAutoUsername($newUserRole)

    {

        $result = "";

        $newCodeVal = 10001;

        $userCodes = Config::get('constants.USER_CODES');

        $users = User::where('roleId', $newUserRole)

            ->orderBy('createdDtm', 'DESC')

            // ->orderBy('updatedDtm', 'DESC')

            ->get();



        if (isset($users) && count($users) > 0 && $users[0]['username']) {

            // $newCodeVal = substr($users[0]['username'], 2) + 1;

            if ($newUserRole == Config::get('constants.FOS')) {

                $newCodeVal = substr($users[0]['username'], 3) + 1;
            } else {

                $newCodeVal = substr($users[0]['username'], 2) + 1;
            }
        }



        foreach ($userCodes as $i => $code) {

            if ($newUserRole == Config::get('constants.ADMIN')) {

                $result = $userCodes['ADMIN'] . $newCodeVal;
            } else if ($newUserRole == Config::get('constants.DISTRIBUTOR')) {

                $result = $userCodes['DISTRIBUTOR'] . $newCodeVal;
            } else if ($newUserRole == Config::get('constants.FOS')) {

                $result = $userCodes['FOS'] . $newCodeVal;
            } else if ($newUserRole == Config::get('constants.RETAILER')) {

                $result = $userCodes['RETAILER'] . $newCodeVal;
            } else if ($newUserRole == Config::get('constants.MASTER_DISTRIBUTOR')) {

                $result = $userCodes['MASTER_DISTRIBUTOR'] . $newCodeVal;
            }
        }



        return $result;
    }



    /**

     * Prepare reset Pwd message

     */

    public function prepareResetPwdMsg($mpin, $password, $username)

    {

        $msg = "";



        $resetPwdSmsTemplate = SmsTemplate::where('alias', Config::get('constants.SMS_TEMPLATE_ALIAS.RESET_USER_PWD.name'))->first();



        if (isset($resetPwdSmsTemplate)) {

            return $msg = __($resetPwdSmsTemplate->template, [

                "username" => $username,

                "mpin" => $mpin,

                "password" => $password,

            ]);
        }
    }



    /**

     * Reset User Password

     */

    public function resetUserPwd($id)

    {



        $result = null;

        $password = rand(100000, 999999);

        if (isset($id) && $id) {

            $user = User::find((int) $id);

            $user->password = Hash::make($password);

            $result = $user->save();
        }



        if ($result) {

            $message = $this->prepareResetPwdMsg($user->mpin, $password, $user->username);

            if ($message) {

                $smsTemplateId = SmsTemplate::where('alias', Config::get('constants.SMS_TEMPLATE_ALIAS.RESET_USER_PWD.name'))->first();

                $message = "Welcome to PayMama Login Details : Username : " . $user->username . " Password : " . $password . " MPIN : " . $user->mpin . " Helpline : 040-29563154 www.paymamaapp.in";

                $msgResponse = $this->sendSms($message, $user->mobile, $smsTemplateId->template_id);



                $data = array(

                    'name' => $user->first_name . " " . $user->last_name,

                    'username' => $user->username,

                    'password' => $password,

                    'mpin' => $user->mpin

                );

                Mail::send('mail.reset', $data, function ($msg) use ($user) {

                    $msg->to($user->email, $user->first_name . " " . $user->last_name)

                        ->subject('Login Credentials - PayMama')

                        ->from('hello@paymamaapp.in', 'PayMama - Business Made Easy');
                });

                if ($msgResponse) {

                    return back()->with('success', 'Password has been reset successfully!!');
                }
            }
        } else {

            return back()->with('error', 'Failed to Reset!!');
        }
    }



    /**

     * Change Logged In/Self User Password

     */

    public function chgPwd(Request $request)

    {

        $result = null;

        if (isset($request->password) && $request->password) {

            $user = User::find(Auth::id());

            $user->password = Hash::make($request->get('password'));

            $result = $user->save();
        }



        if ($result) {

            return back()->with('success', 'Password Updated Successfully!!');
        } else {

            return back()->with('error', 'Failed to Update!!');
        }
    }



    /**

     * Change User Mpin

     */

    public function chgMpin(Request $request)

    {

        $result = null;

        if (isset($request->mpin) && $request->mpin) {

            $user = User::find(Auth::id());

            $user->mpin = $request->get('mpin');

            $result = $user->save();
        }



        if ($result) {

            return back()->with('success', 'Mpin Updated Successfully!!');
        } else {

            return back()->with('error', 'Failed to Update!!');
        }
    }



    /**

     * Send Otp to logged in user

     */

    public function sendOtp(Request $request)

    {

        if (isset($request->mobile) && $request->mobile) {

            $otp = rand(100000, 999999);

            $msg = "Your Verification OTP is " . $otp;

            $msg = $this->prepareOTP($otp);

            $template_id = SmsTemplate::where('alias', Config::get('constants.SMS_TEMPLATE_ALIAS.VERIFY_USER_OTP.name'))->first();

            $response = $this->sendSms($msg, $request->mobile, $template_id->template_id);

            if ($response) {

                $user = User::find((int) $request->id);

                $user->logged_otp = $otp;

                $userResponse = $user->save();

                if ($userResponse) {

                    return response()->json("Sms sent successfilly", 200);
                } else {

                    return resonse()->json("Failed", 400);
                }
            } else {

                return resonse()->json("Failed", 400);
            }
        }
    }



    public function prepareOTP($_otp)
    {

        $msg = '';

        $resetPwdOTP = SmsTemplate::where('alias', Config::get('constants.SMS_TEMPLATE_ALIAS.VERIFY_USER_OTP.name'))->first();

        if (isset($resetPwdOTP)) {

            // print_r($resetPwdOTP);

            $msg = __($resetPwdOTP->template, [

                "otp" => $_otp

            ]);
        }

        return $msg;
    }



    /**

     * Verify UserSent OTP

     */

    public function verifySentOtp(Request $request)

    {

        $response = null;

        if (isset($request->id) && $request->id) {

            $response = User::where('userId', $request->id)->where('logged_otp', $request->verification_otp)->get();
        }



        if (count($response) == 0) {

            return response()->json(0);
        }

        return response()->json("true");
    }



    /**

     * Update Kyc here

     */

    public function updateKyc(Request $request)

    {

        $responseMsg = "";

        if (isset($request->_token) && $request->_token) {

            if (isset($request->kyc_id) && $request->kyc_id) {



                $kyc = KycDetail::find((int) $request->kyc_id);





                $kyc->pan_front_file_status = $request->get('pan_front_file_status');

                $kyc->aadhar_front_file_status = $request->get('aadhar_front_file_status');

                $kyc->aadhar_back_file_status = $request->get('aadhar_back_file_status');

                $kyc->photo_front_file_status = $request->get('photo_front_file_status');

                $kyc->photo_inner_file_status = $request->get('photo_inner_file_status');



                if ($kyc->pan_front_file_id != $request->get('pan_front_file_id')) {

                    $kyc->pan_front_file_status = "PENDING";
                }

                if ($kyc->aadhar_front_file_id != $request->get('aadhar_front_file_id')) {

                    $kyc->aadhar_front_file_status = "PENDING";
                }

                if ($kyc->aadhar_back_file_id != $request->get('aadhar_back_file_id')) {

                    $kyc->aadhar_back_file_status = "PENDING";
                }

                if ($kyc->photo_front_file_id != $request->get('photo_front_file_id')) {

                    $kyc->photo_front_file_status = "PENDING";
                }

                if ($kyc->photo_inner_file_id != $request->get('photo_inner_file_id')) {

                    $kyc->photo_inner_file_status = "PENDING";
                }



                $kyc->pan_front_file_id = $request->get('pan_front_file_id');

                $kyc->aadhar_front_file_id = $request->get('aadhar_front_file_id');

                $kyc->aadhar_back_file_id = $request->get('aadhar_back_file_id');

                $kyc->photo_front_file_id = $request->get('photo_front_file_id');

                $kyc->photo_inner_file_id = $request->get('photo_inner_file_id');

                $kyc->status = $request->get('status');

                $responseMsg = "KYC details updated!!";



                $kycDetail = $kyc->save();

                if ($kycDetail && $kyc->status == "APPROVED") {

                    $kycUserMbNo = isset(User::where('userId', $kyc->user_id)->pluck('mobile')[0]) ? User::where('userId', $kyc->user_id)->pluck('mobile')[0] : null;

                    $kycApprovedTemplate = SmsTemplate::where('alias', Config::get('constants.SMS_TEMPLATE_ALIAS.KYC_APPROVAL.name'))->pluck('template')->first();



                    if ($kycUserMbNo && $kycApprovedTemplate) {

                        $smsTemplateId = SmsTemplate::where('alias', Config::get('constants.SMS_TEMPLATE_ALIAS.KYC_APPROVAL.name'))->pluck('template_id')->first();



                        $this->sendSms($kycApprovedTemplate, $kycUserMbNo, $smsTemplateId);



                        $kycApprovedTemplateNotify = SmsTemplate::where('alias', Config::get('constants.SMS_TEMPLATE_ALIAS.KYC_APPROVAL.name'))->get()->first();

                        $user_session = DB::table('tbl_users_login_session_dtl')->where('user_id', $kyc->user_id)->get()->first();

                        if ($user_session) {

                            // $notmsg = 'Dear SMART PAY User, Your wallet is credited with Rs :amount , Last Balance Rs :last_balance_amount . Updated Balance Rs :updated_balance_amount, Helpline : 040-29563154';

                            $this->sendNotification($user_session->firebase_token, $kycApprovedTemplateNotify->sms_name, $kycApprovedTemplateNotify->notification, $kyc->user_id);
                        }
                    }
                }
            } else {

                $kycDetail = KycDetail::create([

                    "user_id" => Auth::id(),

                    "pan_front_file_id" => $request->get('pan_front_file_id'),

                    "aadhar_front_file_id" => $request->get('aadhar_front_file_id'),

                    "aadhar_back_file_id" => $request->get('aadhar_back_file_id'),

                    "photo_front_file_id" => $request->get('photo_front_file_id'),

                    "photo_inner_file_id" => $request->get('photo_inner_file_id'),



                    "pan_front_file_status" => 'PENDING',

                    "aadhar_front_file_status" => 'PENDING',

                    "aadhar_back_file_status" => 'PENDING',

                    "photo_front_file_status" => 'PENDING',

                    "photo_inner_file_status" => 'PENDING',

                    "status" => 'PENDING',



                ]);

                $responseMsg = "KYC Request Submitted successfully!!";
            }



            if ($kycDetail) {

                return back()->with('success', $responseMsg);
            } else {

                return back()->with('error', $kycDetail);
            }
        }
    }



    /**

     * Login api

     *

     * @return \Illuminate\Http\Response

     */

    public function login(Request $request)

    {

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {

            $user = Auth::user();

            $success['token'] = $user->createToken('MyApp')->accessToken;

            $success['name'] = $user->name;



            return $this->sendResponse($success, 'User login successfully.');
        } else {

            return $this->sendError('Unauthorised.', ['error' => 'Unauthorised']);
        }
    }





    /** User Verification API **/



    public function check_verification(Request $request)

    {

        $user_id = $request->user_id;

        $getdetails = Verification::where([['id', '=', $user_id]])->first();

        if ($getdetails->success_score >= 70) {

            $pan_name = $getdetails->pan_name;

            // $pan_name=explode(' ',$pan_name);

            $bank_account_name = $getdetails->bank_account_name;

            $aadhar_name = $getdetails->aadhar_name;

            $aadhar_names = explode(' ', $aadhar_name);

            $pattern1 = "/" . $pan_name[0] . "/i";

            $pattern2 = "/" . $pan_name[1] . "/i";

            $pattern3 = "/" . $aadhar_names[0] . "/i";

            $pattern4 = "/" . $aadhar_names[1] . "/i";

            /*if(preg_match_all($pattern1, $bank_account_name, $matches) or preg_match_all($pattern2, $bank_account_name, $matches) or preg_match_all($pattern1, $aadhar_name, $matches)

             or preg_match_all($pattern2, $aadhar_name, $matches)  or preg_match_all($pattern3, $bank_account_name, $matches)   or preg_match_all($pattern4, $bank_account_name, $matches))

             {

             */

            if ($bank_account_name == $aadhar_name && $bank_account_name == $pan_name) {

                //Taking Details from Verification Table and storing in user table

                $email = $getdetails->email;

                $password = '123456';

                $type = $getdetails->type;

                $fname = $getdetails->telecome_name;

                $mobile = $getdetails->mobile_number;

                $address = $getdetails->business_address;

                $state_id = $getdetails->state_id;

                $district_id = $getdetails->district_id;

                $zip_code = $getdetails->zip_code;

                $roleId = $getdetails->roleID;

                $store_name = $getdetails->business_name;

                $shop_lat = $getdetails->shop_lat;

                $shop_lan = $getdetails->shop_long;

                $pic_lat = $getdetails->pic_lat;

                $pic_lan = $getdetails->pic_lang;

                $store_category_id = $getdetails->business_category;

                $parent_role_ids = $getdetails->parent_role_id;

                $parent_user_ids = $getdetails->parent_user_id;

                $aadhar_number = $getdetails->aadhar_number;

                $pan_number = $getdetails->pan_number;

                $ifsc_code = $getdetails->ifsc_code;

                $selfie_id = $getdetails->selfie_id;

                $pan_id = $getdetails->pan_id;

                $selfiemessage = $getdetails->selfiemessage;

                $aadhar_number = $getdetails->aadhar_number;

                $aadhar_address = $getdetails->aadhar_address;

                $bank_account_name = $getdetails->bank_account_name;

                $branch_name = $getdetails->branch_name;

                $account_number = $getdetails->account_number;

                $bank_name = $getdetails->bank_name;

                $telecome_name = $getdetails->telecome_name;

                $alternate_mob_no = $getdetails->alternate_mob_no;

                $address = $getdetails->business_address;

                $shop_inside_image = $getdetails->shop_inside_image;

                $shop_front_image = $getdetails->shop_front_image;

                $taddress = $getdetails->address;

                $package_id = 10;

                $min_balance = 200;

                $min_amount_deposit = 1000;

                $max_amount_deposit = 10000;

                $is_ocr = $getdetails->is_ocr;

                $aadhar_front = $getdetails->aadhar_front;

                $aadhar_back = $getdetails->aadhar_back;



                if ($parent_role_ids == '') {

                    if ($roleId == 4) {

                        $parent_role_id = 2;

                        $parent_user_id = 4;
                    } else if ($roleId == 2) {

                        $parent_role_id = 1;

                        $parent_user_id = 1;
                    } else {

                        $parent_role_id = '';

                        $parent_user_id = '';

                        $username = '';
                    }
                } else {

                    $parent_role_id = $parent_role_ids;

                    $parent_user_id = $parent_user_ids;
                }

                if ($roleId == 4) {



                    $username = $this->generateAutoUsername(Role::getIdFromAlias(Config::get('constants.ROLE_ALIAS.RETAILER')));
                } else if ($roleId == 2) {

                    $username = $this->generateAutoUsername(Role::getIdFromAlias(Config::get('constants.ROLE_ALIAS.DISTRIBUTOR')));
                } else {

                    $username = '';
                }



                $mpin = rand(1000, 9999);

                $password = rand(100000, 999999);

                $passwords = Hash::make($password);

                $loggedOtp = rand(100000, 999999);





                //Inserting Data from verification to user table

                $pg_options = '{"upi":{"mode": "UPI","status":1,"charge":0,"type": "%"},"rupay_card":{"mode": "RUPAY_CARD","status":1,"charge": 0,"type": "%"},"debit_card":{"mode": "DEBIT_CARD","status": 0,"charge": 1.50,"type": "%"},"credit_card":{"mode": "CREDIT_CARD","status": 0,"charge": 1.50,"type": "%"},"prepaid_card":{"mode": "PREPAID_CARD","status": 0,"charge": 1.50,"type": "%"},"corporate_card":{"mode": "CORPORATE_CARD","status": 0,"charge": 2.80,"type": "%"},"wallet":{"mode": "WALLET","status":0,"charge": 2.30,"type": "%"},"net_banking":{"mode": "NET_BANKING","status":0,"charge": 2.30,"type": "%"}}';

                $insertuser = User::insert([
                    'pg_options' => $pg_options, 'is_ocr' => $is_ocr, 'aadhar_front' => $aadhar_front, 'aadhar_back' => $aadhar_back, 'min_amount_deposit' => $min_amount_deposit, 'max_amount_deposit' => $max_amount_deposit, 'min_balance' => $minbalance, 'telecom_address' => $taddress, 'alternate_mob_no' => $alternate_mob_no, 'shop_front_image' => $shop_front_image, 'shop_inside_image' => $shop_inside_image, 'address' => $address, 'pan_number' => $pan_number, 'aadhar_name' => $aadhar_name, 'pan_name' => $pan_name, 'aadhar_number' => $aadhar_number, 'aadhar_address' => $aadhar_address, 'bank_account_name' => $bank_account_name, 'telecome_name' => $telecome_name, 'bank_name' => $bank_name, 'branch_name' => $branch_name, 'account_number' => $account_number, 'selfie_id' => $selfie_id, 'pan_id' => $pan_id, 'is_verified' => '1', 'activated_status' => 'YES', 'aadhar_no' => $aadhar_number, 'pan_no' => $pan_number, 'ifsc_code' => $ifsc_code, 'wallet_balance' => '0', 'mpin' => $mpin, 'store_category_id' => $store_category_id, 'shop_lat' => $shop_lat, 'shop_lan' => $shop_lan, 'pic_lat' => $pic_lat, 'pic_lan' => $pic_lan, 'store_name' => $store_name, 'username' => $username, 'email' => $email, 'password' => $passwords, 'roleId' => $roleId, 'first_name' => $fname, 'last_name' => '',

                    'mobile' => $mobile,  'zip_code' => $zip_code, 'address' => $address, 'parent_role_id' => $parent_role_id, 'parent_user_id' => $parent_user_id,

                    'commission_id' => '', 'package_id' => $package_id, 'state_id' => $state_id, 'district_id' => $district_id, 'createdBy' => '0', 'createdDtm' => date('Y-m-d H:i:s')
                ]);



                //Inserting data from verification to user ends 



                //Delete From Verification List

                $deleteuser = DB::table('tbl_verification')->where('id', $user_id)->update(['isDeleted' => '1']);



                //Delete from Verification List

                //Enter Value is Services Table for new user id

                $lastuserid = User::latest('userId')->first();

                $lastuserid = $lastuserid->userId;

                $allServices = ServicesType::where('activated_status', Config::get('constants.ACTIVE'))->get();



                $isServicePresent =  DB::table('tbl_user_services')->where('user_id', $lastuserid)->get();

                if (count($isServicePresent) > 0) {
                }

                foreach ($allServices as $key => $value) {

                    if ($value['service_id'] <= 4) {

                        $insert_service = DB::table('tbl_user_services')->insert(['user_id' => $lastuserid, 'service_id' => $value['service_id'], 'status' => 1]);
                    } else {

                        $insert_service = DB::table('tbl_user_services')->insert(['user_id' => $lastuserid, 'service_id' => $value['service_id']]);
                    }
                }

                //Ends here



                $message = $this->prepareRegistrationMsg($mpin, $password, $username);

                if ($message) {

                    $this->create_va($username, $fname, $mobile, $email, $password, $account_number, $ifsc_code, $store_name);

                    $regSmsTemplateId = SmsTemplate::where('alias', Config::get('constants.SMS_TEMPLATE_ALIAS.USER_REGISTRATION.name'))->first();

                    $this->sendSms($message, $mobile, $regSmsTemplateId->template_id);

                    $statusMsg = $bank_account_name . ", Verification successful.";

                    $success = "Success!!";

                    return $this->sendSuccess($success, $statusMsg);
                }
            } else {

                $statusMsg = "Verification Pending due to KYC Document Not Matching";

                $success = "false";

                return $this->sendSuccess($success, $statusMsg);
            }
        } else {

            $statusMsg = "Verification Pending" . $selfiemessage;

            $success = "false";

            return $this->sendSuccess($success, $statusMsg);
        }
    }

    public function manual_verification($id = null, Request $request)

    {





        $user_id = $id;

        $getdetails = Verification::where([['id', '=', $user_id]])->first();

        $pan_name = $getdetails->pan_name;

        //  $pan_name=explode(' ',$pan_name);

        $bank_account_name = $getdetails->bank_account_name;

        $aadhar_name = $getdetails->aadhar_name;

        $aadhar_names = explode(' ', $aadhar_name);

        // $pattern1 = "/".$pan_name[0]."/i";

        //$pattern2 = "/".$pan_name[1]."/i";

        //$pattern3="/".$aadhar_names[0]."/i";

        //$pattern4="/".$aadhar_names[1]."/i";



        //Taking Details from Verification Table and storing in user table

        $email = $getdetails->email;

        $password = '123456';

        $type = $getdetails->type;

        $fname = $getdetails->telecome_name;

        $mobile = $getdetails->mobile_number;

        $address = $getdetails->business_address;

        $state_id = $getdetails->state_id;

        $district_id = $getdetails->district_id;

        $zip_code = $getdetails->zip_code;

        $roleId = $getdetails->roleID;

        $store_name = $getdetails->business_name;

        $shop_lat = $getdetails->shop_lat;

        $shop_lan = $getdetails->shop_long;

        $pic_lat = $getdetails->pic_lat;

        $pic_lan = $getdetails->pic_lang;

        $store_category_id = $getdetails->business_category;

        $parent_role_ids = $getdetails->parent_role_id;

        $parent_user_ids = $getdetails->parent_user_id;

        $aadhar_number = $getdetails->aadhar_number;

        $pan_number = $getdetails->pan_number;

        $ifsc_code = $getdetails->ifsc_code;

        $selfie_id = $getdetails->selfie_id;

        $pan_id = $getdetails->pan_id;

        $aadhar_number = $getdetails->aadhar_number;

        $aadhar_address = $getdetails->aadhar_address;

        $bank_account_name = $getdetails->bank_account_name;

        $branch_name = $getdetails->branch_name;

        $account_number = $getdetails->account_number;

        $bank_name = $getdetails->bank_name;

        $telecome_name = $getdetails->telecome_name;

        $alternate_mob_no = $getdetails->alternate_mob_no;

        $address = $getdetails->business_address;

        $shop_inside_image = $getdetails->shop_inside_image;

        $shop_front_image = $getdetails->shop_front_image;

        $taddress = $getdetails->address;

        $is_ocr = $getdetails->is_ocr;

        $aadhar_front = $getdetails->aadhar_front;

        $aadhar_back = $getdetails->aadhar_back;

        $package_id = 10;

        $min_balance = 200;

        $min_amount_deposit = 1000;

        $max_amount_deposit = 10000;





        if ($parent_role_ids == '') {

            if ($roleId == 4) {

                $parent_role_id = 2;

                $parent_user_id = 4;
            } else if ($roleId == 2) {

                $parent_role_id = 1;

                $parent_user_id = 1;
            } else {

                $parent_role_id = '';

                $parent_user_id = '';
            }
        } else {

            $parent_role_id = $parent_role_ids;

            $parent_user_id = $parent_user_ids;
        }



        $mpin = rand(1000, 9999);

        $password = rand(100000, 999999);

        $passwords = Hash::make($password);

        $loggedOtp = rand(100000, 999999);

        if ($roleId == 4) {



            $username = $this->generateAutoUsername(Role::getIdFromAlias(Config::get('constants.ROLE_ALIAS.RETAILER')));
        } else if ($roleId == 2) {

            $username = $this->generateAutoUsername(Role::getIdFromAlias(Config::get('constants.ROLE_ALIAS.DISTRIBUTOR')));
        } else {

            $username = '';
        }



        //Inserting Data from verification to user table



        $pg_options = '{"upi":{"mode": "UPI","status":1,"charge":0,"type": "%"},"rupay_card":{"mode": "RUPAY_CARD","status":1,"charge": 0,"type": "%"},"debit_card":{"mode": "DEBIT_CARD","status": 0,"charge": 1.50,"type": "%"},"credit_card":{"mode": "CREDIT_CARD","status": 0,"charge": 1.50,"type": "%"},"prepaid_card":{"mode": "PREPAID_CARD","status": 0,"charge": 1.50,"type": "%"},"corporate_card":{"mode": "CORPORATE_CARD","status": 0,"charge": 2.80,"type": "%"},"wallet":{"mode": "WALLET","status":0,"charge": 2.30,"type": "%"},"net_banking":{"mode": "NET_BANKING","status":0,"charge": 2.30,"type": "%"}}';

        $insertuser = User::insert([
            'pg_options' => $pg_options, 'is_ocr' => $is_ocr, 'aadhar_front' => $aadhar_front, 'aadhar_back' => $aadhar_back, 'min_amount_deposit' => $min_amount_deposit, 'max_amount_deposit' => $max_amount_deposit, 'min_balance' => $min_balance, 'telecom_address' => $taddress, 'alternate_mob_no' => $alternate_mob_no, 'shop_front_image' => $shop_front_image, 'shop_inside_image' => $shop_inside_image, 'address' => $address, 'pan_number' => $pan_number, 'aadhar_name' => $aadhar_name, 'pan_name' => $pan_name, 'aadhar_number' => $aadhar_number, 'aadhar_address' => $aadhar_address, 'bank_account_name' => $bank_account_name, 'telecome_name' => $telecome_name, 'bank_name' => $bank_name, 'branch_name' => $branch_name, 'account_number' => $account_number, 'selfie_id' => $selfie_id, 'pan_id' => $pan_id, 'is_verified' => '1', 'activated_status' => 'YES', 'aadhar_no' => $aadhar_number, 'pan_no' => $pan_number, 'ifsc_code' => $ifsc_code, 'wallet_balance' => '0', 'mpin' => $mpin, 'store_category_id' => $store_category_id, 'shop_lat' => $shop_lat, 'shop_lan' => $shop_lan, 'pic_lat' => $pic_lat, 'pic_lan' => $pic_lan, 'store_name' => $store_name, 'username' => $username, 'email' => $email, 'password' => $passwords, 'roleId' => $roleId, 'first_name' => $fname, 'last_name' => '',

            'mobile' => $mobile,  'zip_code' => $zip_code, 'address' => $address, 'parent_role_id' => $parent_role_id, 'parent_user_id' => $parent_user_id,

            'commission_id' => '', 'package_id' => $package_id, 'state_id' => $state_id, 'district_id' => $district_id, 'createdBy' => '0', 'createdDtm' => date('Y-m-d H:i:s')
        ]);



        //Inserting data from verification to user ends 



        //Delete From Verification List

        $deleteuser = DB::table('tbl_verification')->where('id', $user_id)->update(['isDeleted' => '1']);



        //Delete from Verification List Ends



        //Enter Value is Services Table for new user id

        $lastuserid = User::latest('userId')->first();

        $lastuserid = $lastuserid->userId;

        $allServices = ServicesType::where('activated_status', Config::get('constants.ACTIVE'))->get();



        $isServicePresent =  DB::table('tbl_user_services')->where('user_id', $lastuserid)->get();

        if (count($isServicePresent) > 0) {
        }

        foreach ($allServices as $key => $value) {

            if ($value['service_id'] <= 4) {

                $insert_service = DB::table('tbl_user_services')->insert(['user_id' => $lastuserid, 'service_id' => $value['service_id'], 'status' => 1]);
            } else {

                $insert_service = DB::table('tbl_user_services')->insert(['user_id' => $lastuserid, 'service_id' => $value['service_id']]);
            }
        }

        //Ends here



        $message = $this->prepareRegistrationMsg($mpin, $password, $username);

        if ($message) {

            $regSmsTemplateId = SmsTemplate::where('alias', Config::get('constants.SMS_TEMPLATE_ALIAS.USER_REGISTRATION.name'))->first();

            $this->sendSms($message, $mobile, $regSmsTemplateId->template_id);

            $statusMsg = "Congratulations " . $fname . ", Verification successful. Check Mobile for Login Credentials.";

            $success = "Success!!";

            //$this->sendSuccess($success, $statusMsg);



        }



        // $this->create_va($username,$fname,$mobile,$email,$account_number,$ifsc_code,$store_name);

        $this->create_va_test($username, 'send_email', $password);



        return  back()->with('success', 'Activated Successfully');
    }



    public function create_va($username, $name, $phone, $email, $password, $account_no, $ifsc, $store_name)

    {

        // $username,$name,$phone,$email,$account_no,$ifsc,$store_name;

        include_once(app_path() . '/Packages/Cashfree.php');

        $clientId = Config::get('constants.CASHFREE_COLLECT_KEY');

        $clientSecret = Config::get('constants.CASHFREE_COLLECT_SECRET');

        $stage = "PROD"; //TEST/PROD

        $authParams["clientId"] = $clientId;

        $authParams["clientSecret"] = $clientSecret;

        $authParams["stage"] = $stage;

        try {

            $autoCollect = new CfAutoCollect($authParams);
        } catch (Exception $e) {

            return false;
        }



        $min = 1000;

        $max = 10000;



        if ($autoCollect) {

            $vid = rand(0000, 99999999);

            $acc['vAccountId'] = $vid;

            $vpa['virtualVpaId'] = rand(1111111111, 9999999999);

            $account['name'] = $name;

            $account['email'] = $email;

            $account['phone'] = $phone;

            $account['remitterAccount'] = $account_no;

            $account['remitterIfsc'] = $ifsc;

            $acc['minAmount'] = $min;

            $acc['maxAmount'] = $max;

            $resp_acc = $autoCollect->createVirtualAccount(array_merge($acc, $account));

            $resp_vpa = $autoCollect->createVirtualAccount(array_merge($vpa, $account));



            Log::info('Cashfree Bank Request: ' . json_encode(array_merge($acc, $account)));

            Log::info('Cashfree VPA Request: ' . json_encode(array_merge($vpa, $account)));

            Log::info('Cashfree Bank Response: ' . json_encode($resp_acc));

            Log::info('Cashfree VPA Response: ' . json_encode($resp_vpa));

            if ($resp_acc['status'] == 'SUCCESS' && $resp_acc['subCode'] == 200 && $resp_vpa['status'] == 'SUCCESS' && $resp_vpa['subCode'] == 200) {

                $qr_id = "";

                $data = 'name=' . $store_name . '&vpa=' . $resp_vpa['data']['vpa'] . '&show_name=true&show_upi=false&type=UPI&logo_type=round&template_id=qr_paymama&'; //customtemplate id given by apiclub

                $curl = curl_init();

                curl_setopt_array($curl, array(

                    CURLOPT_URL => 'https://api.apiclub.in/api/v1/generate_qr',

                    CURLOPT_RETURNTRANSFER => true,

                    CURLOPT_ENCODING => '',

                    CURLOPT_MAXREDIRS => 10,

                    CURLOPT_TIMEOUT => 0,

                    CURLOPT_FOLLOWLOCATION => true,

                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,

                    CURLOPT_CUSTOMREQUEST => 'POST',

                    CURLOPT_POSTFIELDS => $data,

                    CURLOPT_HTTPHEADER => array(

                        'Referer: ' . Config::get('constants.WEBSITE_BASE_URL'),

                        'API-KEY: ' . Config::get('constants.APICLUB_API_KEY'),

                        'Content-Type: application/x-www-form-urlencoded'

                    ),

                ));



                $response = curl_exec($curl);

                curl_close($curl);

                $resp = json_decode($response, true);

                if (isset($resp['code']) && $resp['code'] == 200 && $resp['status'] == 'success') {

                    $qr_id = $resp['response']['qr_id'];
                }



                $vdata = array(

                    'va_id' => $vid,

                    'va_account_number' => $resp_acc['data']['accountNumber'],

                    'va_ifsc_code' => $resp_acc['data']['ifsc'],

                    'va_upi_id' => $resp_vpa['data']['vpa'],

                    'qr_id' => $qr_id,

                );

                $user_info = User::where('username', $username)->get()->first();

                $user_info->va_id = $vid;

                $user_info->va_account_number = $resp_acc['data']['accountNumber'];

                $user_info->va_ifsc_code = $resp_acc['data']['ifsc'];

                $user_info->va_upi_id = $resp_vpa['data']['vpa'];

                $user_info->qr_id = $qr_id;

                if ($user_info->save()) {

                    $data = array(

                        'email' => $email,

                        'name' => $name,

                        'username' => $username,

                        'password' => $password,

                        'mpin' => $user_info->mpin

                    );

                    $send_email = Mail::send('mail.welcome', $data, function ($msg) use ($email, $name) {

                        $msg->to($email, $name);

                        $msg->subject('Welcome to PayMama');

                        $msg->from('hello@paymamaapp.in', 'PayMama - Business Made Easy');
                    });

                    $send_email = Mail::send('mail.kyc', $data, function ($msg) use ($email, $name) {

                        $msg->to($email, $name);

                        $msg->subject('KYC Completed Successfully - PayMama');

                        $msg->from('hello@paymamaapp.in', 'PayMama - Business Made Easy');
                    });

                    return $vdata;
                }
            }
        }

        return false;
    }



    public function create_va_test($username, $semail = null, $password = null)

    {



        // $username,$name,$phone,$email,$account_no,$ifsc,$store_name;

        include_once(app_path() . '/Packages/Cashfree.php');

        $user = User::where('username', $username)->first();

        $username = $user->username;

        $name = $user->first_name . " " . $user->last_name;

        $phone = $user->mobile;

        // $phone=rand(1111111111,9999999999);

        $email = $user->email;

        $account_no = $user->account_number;

        $ifsc = $user->ifsc_code;

        $store_name = $user->store_name;

        $clientId = Config::get('constants.CASHFREE_COLLECT_KEY');

        $clientSecret = Config::get('constants.CASHFREE_COLLECT_SECRET');

        $stage = "PROD"; //TEST/PROD

        $authParams["clientId"] = $clientId;

        $authParams["clientSecret"] = $clientSecret;

        $authParams["stage"] = $stage;

        try {

            $autoCollect = new CfAutoCollect($authParams);
        } catch (Exception $e) {

            return $e;
        }

        $min = 1000;

        $max = 10000;

        if ($autoCollect) {

            $vid = rand(0000, 99999999);

            $acc['vAccountId'] = $vid;

            $vpa['virtualVpaId'] = rand(1111111111, 9999999999);

            $account['name'] = $name;

            $account['email'] = $email;

            $account['phone'] = $phone;

            $account['remitterAccount'] = $account_no;

            $account['remitterIfsc'] = $ifsc;

            $acc['minAmount'] = $min;

            $acc['maxAmount'] = $max;

            $resp_acc = $autoCollect->createVirtualAccount(array_merge($acc, $account));

            $resp_vpa = $autoCollect->createVirtualAccount(array_merge($vpa, $account));

            Log::info('Cashfree Bank Request: ' . json_encode(array_merge($acc, $account)));

            Log::info('Cashfree VPA Request: ' . json_encode(array_merge($vpa, $account)));

            Log::info('Cashfree Bank Response: ' . json_encode($resp_acc));

            Log::info('Cashfree VPA Response: ' . json_encode($resp_vpa));

            if ($resp_acc['status'] == 'SUCCESS' && $resp_acc['subCode'] == 200 && $resp_vpa['status'] == 'SUCCESS' && $resp_vpa['subCode'] == 200) {

                $qr_id = "";

                $data = 'name=' . $store_name . '&vpa=' . $resp_vpa['data']['vpa'] . '&show_name=true&show_upi=false&type=UPI&logo_type=round&template_id=qr_paymama&'; //customtemplate id given by apiclub

                $curl = curl_init();

                curl_setopt_array($curl, array(

                    CURLOPT_URL => 'https://api.apiclub.in/api/v1/generate_qr',

                    CURLOPT_RETURNTRANSFER => true,

                    CURLOPT_ENCODING => '',

                    CURLOPT_MAXREDIRS => 10,

                    CURLOPT_TIMEOUT => 0,

                    CURLOPT_FOLLOWLOCATION => true,

                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,

                    CURLOPT_CUSTOMREQUEST => 'POST',

                    CURLOPT_POSTFIELDS => $data,

                    CURLOPT_HTTPHEADER => array(

                        'Referer: ' . Config::get('constants.WEBSITE_BASE_URL'),

                        'API-KEY: ' . Config::get('constants.APICLUB_API_KEY'),

                        'Content-Type: application/x-www-form-urlencoded'

                    ),

                ));



                $response = curl_exec($curl);

                curl_close($curl);

                $resp = json_decode($response, true);

                if (isset($resp['code']) && $resp['code'] == 200 && $resp['status'] == 'success') {

                    $qr_id = $resp['response']['qr_id'];
                }



                $vdata = array(

                    'va_id' => $vid,

                    'va_account_number' => $resp_acc['data']['accountNumber'],

                    'va_ifsc_code' => $resp_acc['data']['ifsc'],

                    'va_upi_id' => $resp_vpa['data']['vpa'],

                    'qr_id' => $qr_id,

                );

                $user_info = User::where('username', $username)->get()->first();

                $user_info->va_id = $vid;

                $user_info->va_account_number = $resp_acc['data']['accountNumber'];

                $user_info->va_ifsc_code = $resp_acc['data']['ifsc'];

                $user_info->va_upi_id = $resp_vpa['data']['vpa'];

                $user_info->qr_id = $qr_id;

                if ($user_info->save()) {

                    if (!empty($semail)) {

                        $data = array(

                            'email' => $email,

                            'name' => $name,

                            'username' => $username,

                            'password' => $password ?? "",

                            'mpin' => $user_info->mpin

                        );

                        if (!empty($password)) {

                            $send_email = Mail::send('mail.welcome', $data, function ($msg) use ($email, $name) {

                                $msg->to($email, $name);

                                $msg->subject('Welcome to PayMama');

                                $msg->from('hello@paymamaapp.in', 'PayMama - Business Made Easy');
                            });
                        }

                        $send_email = Mail::send('mail.kyc', $data, function ($msg) use ($email, $name) {

                            $msg->to($email, $name);

                            $msg->subject('KYC Completed Successfully - PayMama');

                            $msg->from('hello@paymamaapp.in', 'PayMama - Business Made Easy');
                        });
                    }

                    return $vdata;
                } else {

                    return "No";
                }
            } else {

                return $resp_vpa;
            }
        }

        return "NA";
    }



    public function update_va($id)

    {

        include_once(app_path() . '/Packages/Cashfree.php');

        $user = User::find((int) $id);

        $username = $user->username;

        $name = $user->first_name . " " . $user->last_name;

        $phone = $user->mobile;

        $email = $user->email;

        $account_no = $user->account_number;

        $ifsc = $user->ifsc_code;

        $store_name = $user->store_name;

        $clientId = Config::get('constants.CASHFREE_COLLECT_KEY');

        $clientSecret = Config::get('constants.CASHFREE_COLLECT_SECRET');

        $stage = "PROD"; //TEST/PROD

        $authParams["clientId"] = $clientId;

        $authParams["clientSecret"] = $clientSecret;

        $authParams["stage"] = $stage;

        try {

            $autoCollect = new CfAutoCollect($authParams);
        } catch (Exception $e) {

            return $e;
        }

        $min = 1000;

        $max = 10000;

        if ($autoCollect) {

            $vid = $user->va_id;

            $account['vAccountId'] = $vid;

            $account['name'] = $name;

            $account['email'] = $email;

            $account['phone'] = $phone;

            $account['remitterAccount'] = $account_no;

            $account['remitterIfsc'] = $ifsc;

            $account['minAmount'] = $min;

            $account['maxAmount'] = $max;

            $resp_acc = $autoCollect->updateVirtualAccount($account);

            Log::info('Cashfree Bank Request : ' . json_encode($account));

            Log::info('Cashfree Bank Response : ' . json_encode($resp_acc));

            return $resp_acc;
        }

        return "NA";
    }



    public function fetch_va($id)
    {

        include_once(app_path() . '/Packages/Cashfree.php');

        $clientId = Config::get('constants.CASHFREE_COLLECT_KEY');

        $clientSecret = Config::get('constants.CASHFREE_COLLECT_SECRET');

        $stage = "PROD"; //TEST/PROD

        $authParams["clientId"] = $clientId;

        $authParams["clientSecret"] = $clientSecret;

        $authParams["stage"] = $stage;

        try {

            $autoCollect = new CfAutoCollect($authParams);
        } catch (Exception $e) {

            return $e;
        }



        if ($autoCollect) {

            $resp_acc = $autoCollect->getVirtualAccount($id);

            return $resp_acc;
        }

        return "NA";
    }



    /** User Verification API Ends**/



    public static function filepath($id)

    {

        return $id;

        return $profilePicPath = File::where('id', $id)->pluck('file_path')->first();
    }

    /**

     * Register api

     *

     * @return \Illuminate\Http\Response

     */

    public function register(Request $request)

    {



        if (isset($request->otp) && $request->otp) {

            $validator = Validator::make($request->all(), [

                'otp' => 'required|numeric|min:6',

                'user_id' => 'required',

            ]);
        } else {

            // $validator = Validator::make($request->all(), [

            //     'first_name' => 'required|string|max:255',

            //     'last_name' => 'required|string|max:255',

            //     'email' => 'required|string|email|max:255|unique:tbl_users,email,' . $request->user_id . ',userId',

            //     'state_id' => 'required|numeric',

            //     'district_id' => 'required|numeric',

            //     'store_category_id' => 'required|numeric',

            //     'mobile' => 'numeric|min:10|unique:tbl_users,mobile,' . $request->user_id . ',userId',

            //     'store_name' => 'required|string',

            //     'whatsapp_no' => 'required',

            //     'zip_code' => 'required|numeric',

            //     'address' => 'required'

            // ]);

            $validate_arr = [

                'first_name' => 'required|string|max:255',

                'last_name' => 'required|string|max:255',

                'email' => 'required|string|email|max:255|unique:tbl_users,email,' . $request->user_id . ',userId',

                'state_id' => 'required|numeric',

                'district_id' => 'required|numeric',

                'store_category_id' => 'required|numeric',

                // 'mobile' => 'numeric|min:10|unique:tbl_users,mobile,' . $request->user_id . ',userId',

                'store_name' => 'required|string',

                'whatsapp_no' => 'required',

                'zip_code' => 'required|numeric',

                'address' => 'required'

            ];



            if ((isset($request->user_id) && $request->user_id) && !(isset($request->otp) && $request->otp)) {

                $validate_arr['mobile'] = 'numeric|min:10';
            } else {

                $validate_arr['mobile'] = 'numeric|min:10|unique:tbl_users,mobile,' . $request->user_id . ',userId';
            }

            $validator = Validator::make($request->all(), $validate_arr);
        }



        $mpin = rand(1000, 9999);

        $password = rand(100000, 999999);

        $loggedOtp = rand(100000, 999999);

        $username = $this->generateAutoUsername(Role::getIdFromAlias(Config::get('constants.ROLE_ALIAS.RETAILER')));

        $statusMsg = "";

        $success = null;



        if ($validator->fails()) {

            if (isset($validator->messages()->get('mobile')[0]) && isset($validator->messages()->get('email')[0]) && $validator->messages()->get('mobile')[0] == 'The mobile has already been taken.' && $validator->messages()->get('email')[0] == 'The email has already been taken.') {

                $userWithMobile = User::where('email', $request->email)->where('mobile', $request->mobile)->first();

                if (isset($userWithMobile) && $userWithMobile['is_verified'] == 0) {

                    $userWithMobile->logged_otp = $loggedOtp;

                    $statusMsg = "Please verify with the verification code sent to your mobile!!";

                    $success['user_id'] = $userWithMobile->userId;

                    $otpUpdated = $userWithMobile->save();



                    //create services records of user



                    if ($otpUpdated) {



                        $this->insertAllowServices($userWithMobile->userId);



                        // $message = "Your verification OTP is " . $loggedOtp;

                        $sms_tem_id = SmsTemplate::where('alias', 'verify_user_otp')->get()->first();



                        $message =  $loggedOtp . ' is your verification code. SMARTPAY';

                        $this->sendSms($message, $request->get('mobile'),  $sms_tem_id->verify_user_otp);

                        return $this->sendSuccess($success, $statusMsg);
                    }
                }
            }



            return $this->sendError('Validation Error.', $validator->errors());
        }



        if ((isset($request->user_id) && $request->user_id) && (isset($request->otp) && $request->otp)) {

            $resultById = User::find((int) $request->user_id);

            if (isset($resultById) && $resultById) {

                if ($resultById->logged_otp == $request->otp) {

                    $resultById->password = Hash::make($password);

                    $resultById->is_verified = 1;

                    $resultById->save();

                    $this->insertAllowServices($request->user_id);

                    $message = $this->prepareRegistrationMsg($resultById->mpin, $password, $resultById->username);

                    if ($message) {

                        $regSmsTemplateId = SmsTemplate::where('alias', Config::get('constants.SMS_TEMPLATE_ALIAS.USER_REGISTRATION.name'))->first();

                        $this->sendSms($message, $resultById->mobile, $regSmsTemplateId->template_id);

                        $statusMsg = "Congratulations " . $resultById->first_name . " " . $resultById->last_name . ". Verification successful. Check Mobile for Login Credentials.";

                        $success = "Success!!";
                    }
                } else {

                    return $this->sendError('Invalid OTP!!');
                }
            }
        } elseif ((isset($request->user_id) && $request->user_id) && !(isset($request->otp) && $request->otp)) {



            $resultById = User::find((int) $request->user_id);

            if (isset($resultById) && $resultById) {



                $resultById->first_name = $request->get('first_name');

                $resultById->last_name = $request->get('last_name');

                $resultById->email = $request->get('email');

                $resultById->username = $resultById->username ? $resultById->username : $username;

                $resultById->mobile = $request->get('mobile');

                $resultById->alternate_mob_no = $request->get('alternate_mob_no') ? $request->get('alternate_mob_no') : '';



                $resultById->pan_no = $request->get('pan_no');

                $resultById->aadhar_no = $request->get('aadhar_no');

                $resultById->gst_no = $request->get('gst_no');

                $resultById->whatsapp_no = $request->get('whatsapp_no');

                $resultById->telegram_no = $request->get('telegram_no');

                $resultById->state_id = $request->get('state_id');

                $resultById->district_id = $request->get('district_id');

                $resultById->address = $request->get('address');

                $resultById->zip_code = $request->get('zip_code');

                $resultById->store_name = $request->get('store_name');

                $resultById->store_category_id = $request->get('store_category_id');

                $resultById->fos_id = (isset($request->fos_id)) ? $request->fos_id : null;



                $user = $resultById->save();



                if ($user) {

                    $statusMsg = "User Updated Successfully!!";

                    $success['first_name'] = $resultById->first_name;

                    $success['last_name'] = $resultById->last_name;

                    $success['store_name'] = $resultById->store_name;

                    $this->insertAllowServices($request->user_id);
                }
            } else {

                return $this->sendError('No such user found!!');
            }
        } else {

            $url = "https://staging.eko.in:25004/ekoapi/v1/user/onboard";

            $state_id = $request->get('state_id');

            $district_id = $request->get('district_id');

            $state_name = State::getStateNameById($state_id);

            $district_name = City::getCityNameById($district_id);

            $usernameapi = ApiSetting::getApiusernameById('9');

            $Apitoken = ApiSetting::getApitokenById('9');

            $Apisecretkey = ApiSetting::getApisecretkeyById('9');

            $key_result = $this->getsecretkey_timestramp($Apisecretkey);

            $key = json_decode($key_result, true);

            $secret_key = $key['secret_key'];

            $secret_key_timestamp = $key['secret_key_timestamp'];

            $curl = curl_init();

            curl_setopt_array($curl, array(

                CURLOPT_PORT => "25004",

                CURLOPT_URL => $url,

                CURLOPT_RETURNTRANSFER => true,

                CURLOPT_ENCODING => "",

                CURLOPT_MAXREDIRS => 10,

                CURLOPT_TIMEOUT => 30,

                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,

                CURLOPT_CUSTOMREQUEST => "PUT",

                CURLOPT_POSTFIELDS => 'initiator_id=' . $usernameapi . '&pan_number=' . $request->get('pan_no') . '&mobile=' . $request->get('mobile') . '&first_name=' . $request->get('first_name') . '&last_name=' . $request->get('last_name') . '&email=' . $request->get('email') . '&residence_address={"line": "Eko India","city":"' . $district_name . '","state":"' . $state_name . '","pincode":"' . $request->get('zip_code') . '"}&dob=' . $request->get('dob') . '&shop_name=' . $request->get('store_name') . '',

                CURLOPT_HTTPHEADER => array(

                    "Cache-Control: no-cache",

                    "Content-Type: application/x-www-form-urlencoded",

                    "developer_key: " . $Apitoken . "",

                    "secret-key:" . $secret_key . "",

                    "secret-key-timestamp:" . $secret_key_timestamp . ""



                ),

            ));



            $response = curl_exec($curl);

            $err = curl_error($curl);



            curl_close($curl);



            if ($err) {

                //echo "cURL Error #:" . $err;

            } else {

                $response;
            }

            $result =  json_decode($response, true);





            $usercode = (!empty($result['data']['user_code']) ? $result['data']['user_code'] : '');



            $user = User::create([

                'first_name' => $request->get('first_name'),

                'last_name' => $request->get('last_name'),

                'user_dob' => date('Y-m-d', strtotime($request->get('dob'))),

                'email' => $request->get('email'),

                'password' => Hash::make($password),

                'username' => $username,

                'mpin' => $mpin,

                'user_code' => $usercode,

                'mobile' => $request->get('mobile'),

                'alternate_mob_no' => ($request->get('alternate_mob_no') ? $request->get('alternate_mob_no') : ''),



                'pan_no' => $request->get('pan_no'),

                'aadhar_no' => $request->get('aadhar_no'),

                'gst_no' => $request->get('gst_no'),

                'whatsapp_no' => $request->get('whatsapp_no'),

                'telegram_no' => $request->get('telegram_no'),



                'roleId' => Role::getIdFromAlias(Config::get('constants.ROLE_ALIAS.RETAILER')),

                'parent_role_id' => Role::getIdFromAlias(Config::get('constants.ROLE_ALIAS.DISTRIBUTOR')),

                'parent_user_id' => 17,

                'package_id' => 1,

                'state_id' => $request->get('state_id'),

                'district_id' => $request->get('district_id'),

                'address' => $request->get('address'),

                'zip_code' => $request->get('zip_code'),

                'store_name' => $request->get('store_name'),

                'store_category_id' => $request->get('store_category_id'),

                'wallet_balance' => 0,

                'commission_id' => '',

                'createdDtm' => now(),

                'logged_otp' => $loggedOtp,

                'fos_id' => (isset($request->fos_id)) ? $request->fos_id : null,



                // 'createdBy' => 1, //logged in user detail

            ]);



            if ($user) {

                $statusMsg = "Please verify with the verification code sent to your mobile!!";

                $success['user_id'] = $user->userId;

                $success['usercode'] = $usercode;



                if ($user) {

                    $smsTemplateId = SmsTemplate::where('alias', Config::get('constants.SMS_TEMPLATE_ALIAS.VERIFY_USER_OTP.name'))->pluck('template_id')->first();

                    $message = $loggedOtp . " is your verification code. SMARTPAY";

                    // $message = "Your verification OTP is " . $loggedOtp;'

                    $this->sendSms($message, $request->get('mobile'), $smsTemplateId);
                }
            }
        }



        return $this->sendSuccess($success, $statusMsg);
    }



    public function storeSubAdmin($id = null, Request $request)
    {



        if ($id) {





            $resultById = User::find((int) $id);

            if (isset($resultById) && $resultById) {

                $resultById->first_name = ($request->first_name) ? $request->first_name : '';

                $resultById->last_name = ($request->last_name) ? $request->last_name : '';

                $resultById->email = ($request->email) ? $request->email : '';

                $resultById->mobile = ($request->first_name) ? $request->mobile : '';

                $resultById->alternate_mob_no = ($request->alternate_mob_no) ? $request->alternate_mob_no : '';

                $resultById->whatsapp_no = ($request->whatsapp_no) ? $request->whatsapp_no : '';

                $resultById->updatedDtm = now();

                $response = $resultById->save();



                if ($response) {

                    $success = "Success!!";

                    return redirect('/user_list')->with('success', 'Sub Admin Added!');
                } else {

                    return redirect('/user_list')->with('error', 'Failed !!');
                }
            }
        } else {





            $mpin = rand(1000, 9999);

            $password = rand(100000, 999999);

            $loggedOtp = rand(100000, 999999);

            $username = $this->generateAutoUsername(Role::getIdFromAlias(Config::get('constants.ROLE_ALIAS.SYSTEM_ADMIN')));

            $statusMsg = "";

            $success = null;

            $user = User::create([

                'first_name' => $request->get('first_name'),

                'last_name' => $request->get('last_name'),

                'user_dob' => now(),

                'email' => $request->get('email'),

                'password' => Hash::make($password),

                'username' => $username,

                'mpin' => $mpin,

                'user_code' => '',

                'mobile' => $request->get('mobile'),

                'alternate_mob_no' => ($request->get('alternate_mob_no') ? $request->get('alternate_mob_no') : ''),



                'pan_no' => '',

                'aadhar_no' => '',

                'gst_no' => '',

                'whatsapp_no' => $request->get('whatsapp_no'),

                'telegram_no' => '',



                'roleId' => Role::getIdFromAlias(Config::get('constants.ROLE_ALIAS.SYSTEM_ADMIN')),

                'parent_role_id' => Role::getIdFromAlias(Config::get('constants.ROLE_ALIAS.SYSTEM_ADMIN')),

                'parent_user_id' => 1,

                'package_id' => 1,

                'state_id' => '',

                'district_id' => '',

                'address' => '',

                'zip_code' => '',

                'store_name' => '',

                'store_category_id' => 0,

                'wallet_balance' => 0,

                'commission_id' => '',

                'createdDtm' => now(),

                'logged_otp' => 0000,



                'createdBy' => 1, //logged in user detail

            ]);

            if ($user) {

                $user_info = User::where('username', $username)->get()->first();

                if ($user_info) {

                    $inserted_permission = $this->insertUserPermission($user_info->userId);
                }





                $message = $this->prepareRegistrationMsg($mpin, $password, $username);

                // print_r( $message );

                if ($message) {

                    $smsTemplateId = SmsTemplate::where('alias', Config::get('constants.SMS_TEMPLATE_ALIAS.USER_REGISTRATION.name'))->first();

                    $this->sendSms($message, $request->get('mobile'), $smsTemplateId->template_id);

                    $statusMsg = "Congratulations " . $request->get('first_name') . " " . $request->get('last_name') . ". Verification successful. Check Mobile for Login Credentials.";

                    $success = "Success!!";

                    return redirect('/user_list')->with('success', 'Sub Admin Adde!');
                }
            } else {

                return back()->with('error', 'Failed!!');
            }
        }
    }



    /**

     * Get User Wallet Balance api

     *

     * @return \Illuminate\Http\Response

     */

    public function getUserDetail(Request $request)

    {

        $validator = Validator::make($request->all(), [

            'user_id' => 'required',

        ]);



        if ($validator->fails()) {

            return $this->sendError('Validation Error.', $validator->errors());
        }





        if (isset($request->user_id) && $request->user_id) {

            $resultById = User::find((int) $request->user_id);

            $kycStatus = KycDetail::where('user_id', $request->user_id)->pluck('status')->first();

            $appVersion = ApplicationDetail::where('alias', Config::get('constants.APP_DTLS_ALIAS.VERSION'))->pluck('value')->first();

            $profilePicPath = File::where('id', $resultById->profile_pic_id)->pluck('file_path')->first();

            if (isset($resultById)) {

                $statusMsg = "Success!!";

                $success['first_name'] = $resultById->first_name;

                $success['last_name'] = $resultById->last_name;

                $success['store_name'] = $resultById->store_name;

                $success['wallet_balance'] = $resultById->wallet_balance;

                $success['kyc_status'] = 'APPROVED';

                $success['profile_pic_file_path'] = $profilePicPath ? $profilePicPath : null;

                $success['app_version'] = $appVersion;

                $success['services'] = $this->getUserServicesByUserId_API($request->user_id);

                $success['activated_status'] = $resultById->activated_status;

                $success['notification_count'] = $this->getNotificationCount($request->user_id);

                $check_kyc = Ekyc::where('user_id', $request->user_id)->first();

                if ($check_kyc) {

                    $success['aadhaar_kyc'] = '' . $check_kyc->aadhaar_kyc; //0 - unverified,1 - pending ,2 - verified,3 - rejected

                    $success['pan_kyc'] = '' . $check_kyc->pan_kyc;

                    $success['bank_kyc'] = '' . $check_kyc->bank_kyc;

                    $success['selfie_kyc'] = '' . $check_kyc->selfie_kyc;

                    $success['business_kyc'] = '' . $check_kyc->business_kyc;

                    $success['complete_kyc'] = '' . $check_kyc->complete_kyc;
                } else {

                    $success['aadhaar_kyc'] = '0'; //0 - unverified,1 - pending ,2 - verified,3 - rejected

                    $success['pan_kyc'] = '0';

                    $success['bank_kyc'] = '0';

                    $success['selfie_kyc'] = '0';

                    $success['business_kyc'] = '0';

                    $success['complete_kyc'] = '0';
                }
            } else {

                return $this->sendError('No such user found!!');
            }
        } else {

            return $this->sendError('No such user found!!');
        }

        return $this->sendSuccess($success, $statusMsg);
    }



    public function signUpUserbyAdmin(Request $request)

    {

        $validator = Validator::make($request->all(), [

            'first_name' => 'required|string|max:255',

            'last_name' => 'required|string|max:255',

            'email_id' => 'required|string|email|max:255|unique:tbl_users,email',

            'mobile_no' => 'numeric|min:10|unique:tbl_users,mobile',

            'parent_user_id' => 'required',

            'parent_role_id' => 'required',

            'package_id' => 'required',

            'role_id' => 'required',

        ]);



        if ($validator->fails()) {

            return $this->sendError($validator->errors()->first());
        }

        $mpin = rand(1000, 9999);

        $password = rand(100000, 999999);

        $loggedOtp = rand(100000, 999999);

        if ($request->role_id == Config::get('constants.RETAILER')) {

            $roleId = Config::get('constants.RETAILER');

            $alias = Config::get('constants.ROLE_ALIAS.RETAILER');
        } elseif ($request->role_id == Config::get('constants.DISTRIBUTOR')) {

            $roleId = Config::get('constants.DISTRIBUTOR');

            $alias = Config::get('constants.ROLE_ALIAS.DISTRIBUTOR');

            // }

            // elseif($request->role_id == Config::get('constants.FOS')) {

            //     $roleId = Config::get('constants.FOS');

        } else {

            return $this->sendError('Invalid User');
        }

        // return $roleId;

        $username = $this->generateAutoUsername($roleId);

        $statusMsg = "";

        $success = null;

        $pg_options = '{"upi":{"mode": "UPI","status":1,"charge":0,"type": "%"},"rupay_card":{"mode": "RUPAY_CARD","status":0,"charge": 0,"type": "%"},"debit_card":{"mode": "DEBIT_CARD","status": 0,"charge": 1.50,"type": "%"},"credit_card":{"mode": "CREDIT_CARD","status": 0,"charge": 1.50,"type": "%"},"prepaid_card":{"mode": "PREPAID_CARD","status": 0,"charge": 1.50,"type": "%"},"corporate_card":{"mode": "CORPORATE_CARD","status": 0,"charge": 2.80,"type": "%"},"wallet":{"mode": "WALLET","status":0,"charge": 2.30,"type": "%"},"net_banking":{"mode": "NET_BANKING","status":0,"charge": 2.30,"type": "%"}}';

        $user = User::create([

            'first_name' => $request->get('first_name') . ' ' . $request->get('last_name'),

            'last_name' => '', //confirm

            'user_dob' => now(),

            'email' => $request->get('email_id'),

            'password' => Hash::make($password),

            'username' => $username,

            'mpin' => $mpin,

            'user_code' => '',

            'mobile' => $request->get('mobile_no'),

            'alternate_mob_no' => '',

            'pan_no' => '',

            'aadhar_no' => '',

            'gst_no' => '',

            'whatsapp_no' => '',

            'telegram_no' => '',

            'roleId' => $roleId,

            'min_amount_deposit' => 1000,

            'min_balance' => 200,

            'max_amount_deposit' => 10000,

            'min_amount_withdraw' => 200,

            'max_amount_withdraw' => 10000,

            'parent_role_id' => $request->parent_role_id ?? '1',

            'parent_user_id' => $request->parent_user_id ?? '1',

            'package_id' => $request->package_id ?? '',

            'state_id' => '',

            'district_id' => '',

            'activated_status' => Config::get('constants.ACTIVE'),

            'address' => '',

            'zip_code' => '',

            'store_name' => '',

            'store_category_id' => 0,

            'wallet_balance' => 0,

            'commission_id' => '',

            'pg_options' => $pg_options,

            'pg_status' => '1',

            'createdDtm' => now(),

            'logged_otp' => 0000,

            'createdBy' => Auth::user()->userId,

        ]);

        if ($user) {

            $user_info = User::with(['ekyc'])->where('username', $username)->get()->first();

            $this->insertAllowServices($user_info->userId);

            if ($user_info->ekyc == "") {

                Ekyc::create([

                    'user_id' => $user_info->userId,

                    'aadhaar_kyc' => '0',

                    'zip_file' => '',

                    'share_code' => '',

                    'mobile' => '',

                    'aadhaar_no' => '',

                    'aadhaar_name' => '',

                    'aadhaar_address' => '',

                    'aadhaar_image' => '',

                    'pan_kyc' => '0',

                    'pan_no' => '',

                    'pan_name' => '',

                    'pan_file' => '',

                    'bank_kyc' => '0',

                    'acc_no' => '',

                    'acc_name' => '',

                    'ifsc_code' => '',

                    'bank_name' => '',

                    'branch_name' => '',

                    'selfie_kyc' => '0',

                    'selfie_image' => '',

                    'success_score' => '',

                    'business_kyc' => '0',

                    'business_name' => '',

                    'business_address' => '',

                    'pincode' => '',

                    'state' => '',

                    'city' => '',

                    'category' => '',

                    'front_image' => '',

                    'inside_image' => '',

                    'latitude' => '',

                    'longitude' => '',

                    'blat' => '',

                    'blong' => '',

                    'complete_kyc' => '0'

                ]);
            }

            $message = $this->prepareRegistrationMsg($mpin, $password, $username);

            if ($message) {

                $smsTemplateId = SmsTemplate::where('alias', Config::get('constants.SMS_TEMPLATE_ALIAS.USER_REGISTRATION.name'))->first();

                $this->sendSms($message, $request->get('mobile_no'), $smsTemplateId->template_id);

                $statusMsg = "Congratulations " . $request->get('first_name') . " " . $request->get('last_name') . ". Verification successful. Check Mobile for Login Credentials.";

                $success = "Success!!";

                return $this->sendSuccess('success', 'User Successfully Added!');
            }
        } else {

            return $this->sendError('Failed');
        }
    }



    public function signUpUser(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'send_otp' => 'required|boolean',

            'business' => 'required',

            'emailId' => 'required|string|email|max:255|unique:tbl_users,email',

            'mobile_no' => 'required|unique:tbl_users,mobile',

            'name' => 'required',

            'otp' => 'required|string|min:4|max:6|required_if:send_otp,0,false'

        ]);



        if ($validator->fails()) {

            return $this->sendError($validator->errors()->first());
        }

        $otp = '';

        $dtId = 'DT10001';
        if (isset($request->distributorId)) {
            $dtId = $request->distributorId;
        }

        $parent_id = User::getIdbyUsername($dtId);
        if ($parent_id == "") {
            $parent_id = '4';
        }


        if ($request->send_otp) {

            $otp = rand(111111, 999999);

            $msg = $otp . " is your verification code,Helpline : 040-29563154 www.paymamaapp.in";

            $sms_tempid = SmsTemplate::where('alias', Config::get('constants.SMS_TEMPLATE_ALIAS.VERIFY_USER_OTP.name'))->get()->first();

            $response = $this->sendSms($msg, $request->mobile_no, $sms_tempid->template_id);

            Session::put('OTP', $otp);

            $req = $request->all();

            $req['get_otp'] = '' . $otp;

            return $this->sendSuccess($req, "Success");
        } elseif (!$request->send_otp && $request->otp != "") {
            Log::info('SIGN UP DATA : ' . json_encode($request->all()));

            Session::forget('OTP');

            $mpin = rand(1000, 9999);

            $password = rand(100000, 999999);

            $loggedOtp = rand(100000, 999999);

            $roleId = Config::get('constants.RETAILER');

            $alias = Config::get('constants.ROLE_ALIAS.RETAILER');

            $username = $this->generateAutoUsername($roleId);

            $statusMsg = "";

            $success = null;

            $pg_options = '{"upi":{"mode": "UPI","status":1,"charge":0,"type": "%"},"rupay_card":{"mode": "RUPAY_CARD","status":0,"charge": 0,"type": "%"},"debit_card":{"mode": "DEBIT_CARD","status": 0,"charge": 1.50,"type": "%"},"credit_card":{"mode": "CREDIT_CARD","status": 0,"charge": 1.50,"type": "%"},"prepaid_card":{"mode": "PREPAID_CARD","status": 0,"charge": 1.50,"type": "%"},"corporate_card":{"mode": "CORPORATE_CARD","status": 0,"charge": 2.80,"type": "%"},"wallet":{"mode": "WALLET","status":0,"charge": 2.30,"type": "%"},"net_banking":{"mode": "NET_BANKING","status":0,"charge": 2.30,"type": "%"}}';

            /*$user = User::create([

                'first_name' => $request->get('name'),

                'last_name' => '', //confirm

                'user_dob' => now(),

                'email' => $request->get('emailId'),

                'password' => Hash::make($password),

                'username' => $username,

                'mpin' => $mpin,

                'user_code' => '',

                'mobile' => $request->get('mobile_no'),

                'alternate_mob_no' => '',

                'pan_no' => '',

                'aadhar_no' => '',

                'gst_no' => '',

                'whatsapp_no' => '',

                'telegram_no' => '',

                'roleId' => $roleId,

                'min_amount_deposit' => 1000,

                'min_balance' => 200,

                'max_amount_deposit' => 10000,

                'min_amount_withdraw' => 200,

                'max_amount_withdraw' => 10000,

                'parent_role_id' => $request->parent_role_id ?? Config::get('constants.DISTRIBUTOR'),

                'parent_user_id' => $parent_id,

                'package_id' => $request->package_id ?? '3',

                'state_id' => '',

                'district_id' => '',

                'activated_status' => Config::get('constants.ACTIVE'),

                'address' => '',

                'zip_code' => '',

                'store_name' => $request->get('business'),

                'store_category_id' => 0,

                'wallet_balance' => 0,

                'commission_id' => '',

                'pg_options' => $pg_options,

                'pg_status' => '1',

                'createdDtm' => now(),

                'logged_otp' => 0000,

                'createdBy' => 0,

            ]);*/

            if ($user) {

                $user_info = User::with(['ekyc'])->where('username', $username)->get()->first();

                $this->insertAllowServices($user_info->userId);

                if ($user_info->ekyc == "") {

                    Ekyc::create([

                        'user_id' => $user_info->userId,

                        'aadhaar_kyc' => '0',

                        'zip_file' => '',

                        'share_code' => '',

                        'mobile' => '',

                        'aadhaar_no' => '',

                        'aadhaar_name' => '',

                        'aadhaar_address' => '',

                        'aadhaar_image' => '',

                        'pan_kyc' => '0',

                        'pan_no' => '',

                        'pan_name' => '',

                        'pan_file' => '',

                        'bank_kyc' => '0',

                        'acc_no' => '',

                        'acc_name' => '',

                        'ifsc_code' => '',

                        'bank_name' => '',

                        'branch_name' => '',

                        'selfie_kyc' => '0',

                        'selfie_image' => '',

                        'success_score' => '',

                        'business_kyc' => '0',

                        'business_name' => $request->get('business'),

                        'business_address' => '',

                        'pincode' => '',

                        'state' => '',

                        'city' => '',

                        'category' => '',

                        'front_image' => '',

                        'inside_image' => '',

                        'latitude' => '',

                        'longitude' => '',

                        'blat' => '',

                        'blong' => '',

                        'complete_kyc' => '0'

                    ]);
                }

                $message = $this->prepareRegistrationMsg($mpin, $password, $username);

                if ($message) {

                    $email = $request->get('emailId');

                    $name = $request->get('name');

                    $data = array(

                        'email' => $email,

                        'name' => $name,

                        'username' => $username,

                        'password' => $password,

                        'mpin' => $mpin

                    );

                    $send_email = Mail::send('mail.welcome', $data, function ($msg) use ($email, $name) {

                        $msg->to($email, $name);

                        $msg->subject('Welcome to PayMama');

                        $msg->from('hello@paymamaapp.in', 'PayMama - Business Made Easy');
                    });

                    $smsTemplateId = SmsTemplate::where('alias', Config::get('constants.SMS_TEMPLATE_ALIAS.USER_REGISTRATION.name'))->first();

                    $this->sendSms($message, $request->get('mobile_no'), $smsTemplateId->template_id);

                    $statusMsg = "Congratulations " . $name . ". Verification successful. Check Mobile for Login Credentials.";

                    $success = "Success!!";

                    return $this->sendSuccess('success', 'User Successfully Added!');
                }
            } else {

                return $this->sendError('Failed');
            }
        } else {

            return $this->sendError("Invalid OTP");
        }



        return $this->sendError("Failed");
    }



    public function edit_ekyc($id)

    {

        if ($id) {

            $user = User::with(['ekyc'])->where('userId', $id)->first();

            if ($user->ekyc == "") {

                Ekyc::create([

                    'user_id' => $id,

                    'aadhaar_kyc' => '0',

                    'zip_file' => '',

                    'share_code' => '',

                    'mobile' => '',

                    'aadhaar_no' => '',

                    'aadhaar_name' => '',

                    'aadhaar_address' => '',

                    'aadhaar_image' => '',

                    'pan_kyc' => '0',

                    'pan_no' => '',

                    'pan_name' => '',

                    'pan_file' => '',

                    'bank_kyc' => '0',

                    'acc_no' => '',

                    'acc_name' => '',

                    'ifsc_code' => '',

                    'bank_name' => '',

                    'branch_name' => '',

                    'selfie_kyc' => '0',

                    'selfie_image' => '',

                    'success_score' => '',

                    'business_kyc' => '0',

                    'business_name' => '',

                    'business_address' => '',

                    'pincode' => '',

                    'state' => '',

                    'city' => '',

                    'category' => '',

                    'front_image' => '',

                    'inside_image' => '',

                    'latitude' => '',

                    'longitude' => '',

                    'blat' => '',

                    'blong' => '',

                    'complete_kyc' => '0'

                ]);

                $user = User::with(['ekyc'])->where('userId', $id)->first();
            }

            if ($user && $user->ekyc != "") {

                $allStates = State::all();

                $allCities = City::all();

                $allCat = StoreCategory::where('is_deleted', Config::get('constants.NOT-DELETED'))->where('activated_status', Config::get('constants.ACTIVE'))->get();

                return view('modules.user.edit_ekyc', compact('user', 'allStates', 'allCities', 'allCat'));
            }
        }

        return back()->with('error', 'Failed!!');
    }



    public function update_ekyc(Request $request)

    {

        if ($request->user_id) {

            $user = User::with(['ekyc'])->where('userId', $request->user_id)->first();

            if ($user) {

                $check_kyc = Ekyc::where('user_id', $user->userId)->first();

                if ($check_kyc) {

                    $success['aadhaar_kyc'] = "" . $request->aadhaar_kyc ?? $check_kyc->aadhaar_kyc; //0 - unverified,1 - pending ,2 - verified,3 - rejected

                    $success['pan_kyc'] = "" . $request->pan_kyc ?? $check_kyc->pan_kyc;

                    $success['bank_kyc'] = "" . $request->bank_kyc ?? $check_kyc->bank_kyc;

                    $success['selfie_kyc'] = "" . $request->selfie_kyc ?? $check_kyc->selfie_kyc;

                    $success['business_kyc'] = "" . $request->business_kyc ?? $check_kyc->business_kyc;

                    if ($success['aadhaar_kyc'] == '2' && $success['pan_kyc'] == '2' && $success['bank_kyc'] == '2' && $success['selfie_kyc'] == '2' && $success['business_kyc'] == '2') {

                        $success['complete_kyc'] = '1';
                    } else {

                        $success['complete_kyc'] = '0';
                    }

                    $update = $check_kyc->update($success);

                    if ($update) {

                        if ($success['complete_kyc'] == '1') {

                            $kycUserMbNo = $user->mobile;

                            $kycApprovedTemplate = SmsTemplate::where('alias', Config::get('constants.SMS_TEMPLATE_ALIAS.KYC_APPROVAL.name'))->pluck('template')->first();

                            if ($kycApprovedTemplate) {

                                $smsTemplateId = SmsTemplate::where('alias', Config::get('constants.SMS_TEMPLATE_ALIAS.KYC_APPROVAL.name'))->pluck('template_id')->first();

                                $this->sendSms($kycApprovedTemplate, $kycUserMbNo, $smsTemplateId);

                                $kycApprovedTemplateNotify = SmsTemplate::where('alias', Config::get('constants.SMS_TEMPLATE_ALIAS.KYC_APPROVAL.name'))->get()->first();

                                $user_session = DB::table('tbl_users_login_session_dtl')->where('user_id', $user->userId)->get()->first();

                                if ($user_session) {

                                    // $notmsg = 'Dear SMART PAY User, Your wallet is credited with Rs :amount , Last Balance Rs :last_balance_amount . Updated Balance Rs :updated_balance_amount, Helpline : 040-29563154';

                                    $this->sendNotification($user_session->firebase_token, $kycApprovedTemplateNotify->sms_name, $kycApprovedTemplateNotify->notification, $user->userId);
                                }
                            }

                            if ($user->va_id == "") {

                                $this->create_va_test($user->username, 'send_email');
                            } else {

                                $this->update_va($request->user_id);
                            }
                        }

                        return $this->sendSuccess($update, 'Success');
                    } else {

                        return $this->sendSuccess($success, 'Test');
                    }
                } else {

                    return $this->sendError('Failed1');
                }
            }
        }

        return $this->sendError('Failed');
    }



    public function checkKyc(Request $request)

    {

        $validator = Validator::make($request->all(), [

            'user_id' => 'required',

        ]);



        if ($validator->fails()) {

            return $this->sendError('Validation Error.', $validator->errors());
        }

        $check_kyc = Ekyc::where('user_id', $request->user_id)->first();

        $success['aadhaar_kyc'] = '0'; //0 - unverified,1 - pending ,2 - verified,3 - rejected

        $success['pan_kyc'] = '0';

        $success['bank_kyc'] = '0';

        $success['selfie_kyc'] = '0';

        $success['business_kyc'] = '0';

        $success['complete_kyc'] = '0';

        if ($check_kyc) {

            $success['aadhaar_kyc'] = '' . $check_kyc->aadhaar_kyc; //0 - unverified,1 - pending ,2 - verified,3 - rejected

            $success['pan_kyc'] = '' . $check_kyc->pan_kyc;

            $success['bank_kyc'] = '' . $check_kyc->bank_kyc;

            $success['selfie_kyc'] = '' . $check_kyc->selfie_kyc;

            $success['business_kyc'] = '' . $check_kyc->business_kyc;

            $success['complete_kyc'] = '' . $check_kyc->complete_kyc;
        }

        return $this->sendSuccess($success, "Success");
    }



    public function submitAadharKyc(Request $request)

    {

        $validator = Validator::make($request->all(), [

            'user_id' => 'required',

            'phoneNo' => 'required',

            'adhaarNumber' => 'required',

            'shareCode' => 'required',

            'zipFileCode' => 'required'

        ]);



        if ($validator->fails()) {

            return $this->sendError($validator->errors()->first());
        }



        if (!$this->isAadharValid($request->adhaarNumber)) {

            return $this->sendError('Invalid Aadhaar Number');
        }

        $user = User::find((int) $request->user_id);

        $check_kyc = Ekyc::where('user_id', $request->user_id)->first();

        if ($check_kyc) {

            if ($check_kyc->aadhaar_kyc == '1') {

                return $this->sendError('Aadhaar Verification is pending at admin approval');
            } elseif ($check_kyc->aadhaar_kyc == '2') {

                return $this->sendError('Aadhaar Verification is already completed');
            }

            $file = base64_decode($request->zipFileCode);

            $zipname = $request->user_id . '' . rand(00000000, 99999999) . '.' . 'zip';

            $upload = file_put_contents(public_path() . '/storage/kyc/zip/' . $zipname, $file);

            $zip_url = 'https://paymamaapp.in/public/storage/kyc/zip/' . $zipname;

            $status = '1';

            $data = array(

                'passcode' => $request->shareCode,

                'mobile' => $request->phoneNo,

                'zip_url' => $zip_url

            );

            $response = Http::withHeaders([

                'Referer' => Config::get('constants.WEBSITE_BASE_URL'),

                'API-KEY' => Config::get('constants.APICLUB_API_KEY')

            ])->post('https://api.apiclub.in/api/v1/verify_aadhar', $data);

            if ($response->successful()) {

                $resp = $response->json();

                Log::info('APICLUB AADHAAR KYC : ' . json_encode($data));

                Log::info('APICLUB AADHAAR KYC : ' . json_encode($resp));

                if ($resp['code'] == 200 && $resp['status'] == 'success') {

                    if (isset($resp['response']['mobile_verified']) && (!$resp['response']['mobile_verified'] || $resp['response']['mobile_verified'] == false)) {

                        return $this->sendError('Please enter Aadhaar registered mobile number');
                    }

                    $file = base64_decode($resp['response']['image']);

                    $name = $request->user_id . '' . rand(00000000, 99999999) . '.' . 'jpg';

                    $upload = file_put_contents(public_path() . '/storage/kyc/aadhaar/' . $name, $file);

                    $status = '1';

                    $fileupload = File::create(['file_path' => '/storage/kyc/aadhaar/' . $name, 'name' => $name]);

                    $status = '2';

                    $check_kyc->aadhaar_image = $fileupload->id;

                    $name = str_replace('  ', ' ', $resp['response']['name']);
                    $name = strtoupper($name);
                    $check_kyc->aadhaar_name = $name;

                    $add = $resp['response']['address']['careof'] . ", " ?? "";

                    $add .= $resp['response']['address']['house'] . ", " ?? "";

                    $add .= $resp['response']['address']['street'] . ", " ?? "";

                    $add .= $resp['response']['address']['po'] . ", " ?? "";

                    $add .= $resp['response']['address']['dist'] . ", " ?? "";

                    $add .= $resp['response']['address']['state'] . "- " ?? "";

                    $add .= $resp['response']['address']['pc'] ?? "";

                    $check_kyc->aadhaar_address = $add;

                    $user->first_name = $name;

                    $user->update();
                } else {

                    return $this->sendError($resp['response'] ?? 'Something went wrong');
                }
            }

            $fileupload = File::create(['file_path' => '/storage/kyc/zip/' . $zipname, 'name' => $zipname]);

            $check_kyc->zip_file = $fileupload->id;

            $check_kyc->share_code = $request->shareCode;

            $check_kyc->mobile = $request->phoneNo;

            $check_kyc->aadhaar_no = 'xxxxxxxxx' . substr($request->adhaarNumber, -4);

            $check_kyc->aadhaar_kyc = $status;

            if ($check_kyc->update()) {

                $success['aadhaar_kyc'] = '' . $check_kyc->aadhaar_kyc; //0 - unverified,1 - pending ,2 - verified,3 - rejected

                return $this->sendSuccess($success, "Aadhaar Kyc Submitted successfully");
            } else {

                return $this->sendError("Unable to complete your Aadhaar KYC now");
            }
        }

        return $this->sendError("Something went wrong please logout and login again!");
    }



    public function submitPanKyc(Request $request)

    {

        $validator = Validator::make($request->all(), [

            'user_id' => 'required',

            'pan_no' => 'required',

            'pan_file' => 'required'

        ]);



        if ($validator->fails()) {

            return $this->sendError($validator->errors()->first());
        }



        if (strlen($request->pan_no) != 10 || !preg_match("/([a-zA-Z]){5}([0-9]){4}([a-zA-Z]){1}/", $request->pan_no)) {

            return $this->sendError('Invalid Pan Number');
        }

        $user = User::find((int) $request->user_id);

        $check_kyc = Ekyc::where('user_id', $request->user_id)->first();

        if ($check_kyc) {

            if ($check_kyc->pan_kyc == '1') {

                return $this->sendError('Pan Verification is pending at admin approval');
            } elseif ($check_kyc->pan_kyc == '2') {

                return $this->sendError('Pan Verification is already completed');
            }

            $file = base64_decode($request->pan_file);

            $name = $request->user_id . '' . rand(00000000, 99999999) . '.' . 'jpg';

            $upload = file_put_contents(public_path() . '/storage/kyc/pan/' . $name, $file);

            $status = '1';

            $fileupload = File::create(['file_path' => '/storage/kyc/pan/' . $name, 'name' => $name]);

            // $user->pan_id = $fileupload->id;

            $response = Http::withHeaders([

                'Referer' => Config::get('constants.WEBSITE_BASE_URL'),

                'API-KEY' => Config::get('constants.APICLUB_API_KEY')

            ])->post('https://api.apiclub.in/api/v1/verify_pan', array('pan_no' => $request->pan_no));

            if ($response->successful()) {

                $resp = $response->json();

                if ($resp['code'] == 200 && $resp['status'] == 'success') {

                    $name = str_replace('  ', ' ', $resp['response']['registered_name']);
                    $name = strtoupper($name);

                    $check_kyc->pan_name = $name;

                    // $user->pan_number = strtoupper($request->pan_no);

                    // $user->pan_name = $name;

                    similar_text($name, $check_kyc->aadhaar_name, $percent);

                    if ($percent >= 70) {

                        $status = '2';
                    }
                } else {

                    return $this->sendError($resp['response'] ?? 'Something went wrong');
                }
            }

            // $user->update();

            $check_kyc->pan_file = $fileupload->id;

            $check_kyc->pan_no = strtoupper($request->pan_no);

            $check_kyc->pan_kyc = $status;

            if ($check_kyc->update()) {

                $success['pan_kyc'] = '' . $check_kyc->pan_kyc; //0 - unverified,1 - pending ,2 - verified,3 - rejected

                return $this->sendSuccess($success, "Pan Kyc Submitted successfully");
            } else {

                return $this->sendError("Unable to complete your PAN KYC now");
            }
        }

        return $this->sendError("Something went wrong please logout and login again!");
    }



    public function submitBankKyc(Request $request)

    {

        $validator = Validator::make($request->all(), [

            'user_id' => 'required',

            'acc_name' => 'required',

            'acc_no' => 'required',

            'acc_ifsc' => 'required'

        ]);



        if ($validator->fails()) {

            return $this->sendError($validator->errors()->first());
        }

        $user = User::find((int) $request->user_id);

        $check_kyc = Ekyc::where('user_id', $request->user_id)->first();

        if ($check_kyc) {

            if ($check_kyc->bank_kyc == '1') {

                return $this->sendError('Bank Account Verification is pending at admin approval');
            } elseif ($check_kyc->bank_kyc == '2') {

                return $this->sendError('Bank Account Verification is already completed');
            }

            if ($check_kyc->pan_kyc != '2' && $check_kyc->pan_kyc != '1') {

                return $this->sendError('Please complete Pan Verification to continue');
            }

            $name = $request->acc_name;

            $status = '1';

            include_once(app_path() . '/Packages/CashfreePayouts.php');

            $clientId = Config::get('constants.CASHFREE_PAYOUT_KEY');

            $clientSecret = Config::get('constants.CASHFREE_PAYOUT_SECRET');

            $stage = "PROD"; //TEST/PROD

            $authParams["clientId"] = $clientId;

            $authParams["clientSecret"] = $clientSecret;

            $authParams["stage"] = $stage;

            try {

                $payouts = new CfPayout($authParams);
            } catch (Exception $e) {

                return $this->sendError('Something went wrong');
            }

            if ($payouts) {

                $validateIfsc = $payouts->validateIfsc($request->acc_ifsc);

                Log::info('IFSC API : ' . json_encode($validateIfsc));

                if ($validateIfsc['status'] == 'SUCCESS' && $validateIfsc['subCode'] == 200) {

                    $bank_name = $validateIfsc['data']['bank'] ?? '';

                    $branch_name = $validateIfsc['data']['branch'] ?? '';

                    $validateBank = $payouts->validateBank($request->acc_no, $request->acc_ifsc);

                    Log::info('BANK VALIDATE API : ' . json_encode($validateBank));

                    if ($validateBank['status'] == 'SUCCESS' && $validateBank['subCode'] == 200) {

                        if ($validateBank['accountStatus'] == 'VALID') {

                            $name = trim($validateBank['data']['nameAtBank']);

                            $name = str_replace("  ", " ", $name);
                            $name = strtoupper($name);

                            similar_text($name, $check_kyc->pan_name, $percent);

                            if ($percent >= 70) {

                                $status = '2';
                            }

                            // $user->account_number = $request->acc_no;

                            // $user->ifsc_code = $request->acc_ifsc;

                            // $user->bank_account_name = $name;

                            // $user->bank_name = $bank_name;

                            // $user->update();

                            $check_kyc->acc_no = $request->acc_no;

                            $check_kyc->acc_name = $name;

                            $check_kyc->ifsc_code = $request->acc_ifsc;

                            $check_kyc->branch_name = $branch_name;

                            $check_kyc->bank_name = $bank_name;

                            $check_kyc->bank_kyc = $status;

                            if ($check_kyc->update()) {

                                $success['bank_kyc'] = '' . $check_kyc->bank_kyc; //0 - unverified,1 - pending ,2 - verified,3 - rejected

                                // if($user->va_id == "") {

                                //     $this->create_va_test($user->username);

                                // } else {

                                //     $this->update_va($request->user_id);

                                // }

                                return $this->sendSuccess($success, "Bank Verification submitted successfully");
                            } else {

                                return $this->sendError("Unable to complete your Bank Verification now");
                            }
                        } else {

                            return $this->sendError($validateBank['message'] ?? 'Invalid Account Number');
                        }
                    } else {

                        return $this->sendError($validateBank['message'] ?? 'Unable to Validate your Bank Account');
                    }
                } else {

                    return $this->sendError($validateIfsc['message'] ?? 'Invalid IFSC Code');
                }
            }
        }

        return $this->sendError("Something went wrong please logout and login again!");
    }



    public function submitSelfieKyc(Request $request)

    {

        Log::info('SELFIE KYC : ' . json_encode($request->except('selfie_image')));

        $validator = Validator::make($request->all(), [

            'user_id' => 'required',

            'selfie_image' => 'required',

            // 'latitude' => 'required',

            // 'longitude' => 'required'

        ]);



        if ($validator->fails()) {

            return $this->sendError($validator->errors()->first());
        }

        $user = User::find((int) $request->user_id);

        $check_kyc = Ekyc::where('user_id', $request->user_id)->first();

        if ($check_kyc) {

            if ($check_kyc->selfie_kyc == '1') {

                return $this->sendError('Selfie Verification is pending at admin approval');
            } elseif ($check_kyc->selfie_kyc == 2) {

                return $this->sendError('Selfie Verification is already completed');
            }

            if ($check_kyc->aadhaar_image == "") {

                return $this->sendError('Please complete Aadhaar Verification to continue');
            }

            $file = base64_decode($request->selfie_image);

            $name = $request->user_id . '' . rand(00000000, 99999999) . '.' . 'jpg';

            $upload = file_put_contents(public_path() . '/storage/kyc/selfie/' . $name, $file);

            $aadhaar_img = File::where('id', $check_kyc->aadhaar_image)->pluck('file_path')->first();

            // return $this->sendError($request->selfie_image);

            $aadhaar_img = base64_encode(file_get_contents(public_path() . $aadhaar_img));

            $status = '1';

            $data = array(

                'doc_img' => $aadhaar_img,

                'selfie' => $request->selfie_image

            );

            $percent = '0';

            $response = Http::withHeaders([

                'Referer' => Config::get('constants.WEBSITE_BASE_URL'),

                'API-KEY' => Config::get('constants.APICLUB_API_KEY')

            ])->post('https://api.apiclub.in/api/v1/face_match', $data);

            if ($response->successful()) {

                $resp = $response->json();

                Log::info('APICLUB SELFIE KYC : ' . json_encode($resp));

                if ($resp['code'] == 200 && $resp['status'] == 'success') {

                    $percent = (float) $resp['response']['match_score'];

                    if ($percent >= 70) {

                        $status = '2';
                    }
                } else {

                    return $this->sendError($resp['response'] ?? 'Something went wrong');
                }
            }

            $fileupload = File::create(['file_path' => '/storage/kyc/selfie/' . $name, 'name' => $name]);

            // $user->selfie_id = $fileupload->id;

            // $user->success_score = "".$percent;

            // $user->pic_lat = $request->latitude;

            // $user->pic_lan = $request->longitude;

            // $user->update();

            $check_kyc->selfie_image = $fileupload->id;

            $check_kyc->selfie_kyc = $status;

            $check_kyc->success_score = "" . $percent;

            $check_kyc->latitude = $request->latitude ?? "";

            $check_kyc->longitude = $request->longitude ?? "";

            if ($check_kyc->update()) {

                $success['selfie_kyc'] = '' . $check_kyc->selfie_kyc; //0 - unverified,1 - pending ,2 - verified,3 - rejected

                return $this->sendSuccess($success, "Selfie Kyc submitted successfully");
            } else {

                return $this->sendError("Unable to complete your Selfie Kyc now");
            }
        }

        return $this->sendError("Something went wrong please logout and login again!");
    }



    public function submitBusinessKyc(Request $request)

    {

        $validator = Validator::make($request->all(), [

            'user_id' => 'required',

            'business_name' => 'required',

            'business_address' => 'required',

            'business_category' => 'required',

            'shop_lat' => 'required',

            'shop_long' => 'required',

            'state_id' => 'required',

            'district_id' => 'required',

            'zip_code' => 'required',

            'shop_front_image' => 'required',

            'shop_inside_image' => 'required',

        ]);



        if ($validator->fails()) {

            Log::info('API : ' . $validator->errors()->first());

            return $this->sendError($validator->errors()->first());
        }

        $check_kyc = Ekyc::where('user_id', $request->user_id)->first();

        if ($check_kyc) {

            if ($check_kyc->business_kyc == '1') {

                return $this->sendError('Business Verification is pending at admin approval');
            } elseif ($check_kyc->business_kyc == 2) {

                return $this->sendError('Business Verification is already completed');
            }

            $ffile = base64_decode($request->shop_front_image);

            $fname = $request->user_id . '' . rand(00000000, 99999999) . '.' . 'jpg';

            file_put_contents(public_path() . '/storage/kyc/shop/' . $fname, $ffile);

            $ffileupload = File::create(['file_path' => '/storage/kyc/shop/' . $fname, 'name' => $fname]);

            $ff = $ffileupload->id;



            $ifile = base64_decode($request->shop_inside_image);

            $iname = $request->user_id . '' . rand(00000000, 99999999) . '.' . 'jpg';

            file_put_contents(public_path() . '/storage/kyc/shop/' . $iname, $ifile);

            $ifileupload = File::create(['file_path' => '/storage/kyc/shop/' . $iname, 'name' => $iname]);

            $if = $ifileupload->id;

            $status = '1';

            $check_kyc->business_kyc = '1';

            $check_kyc->business_name = $request->business_name;

            $check_kyc->business_address = $request->business_address;

            $check_kyc->pincode = $request->zip_code;

            $check_kyc->city = $request->district_id;

            $check_kyc->state = $request->state_id;

            $check_kyc->category = $request->business_category;

            $check_kyc->front_image = $ff;

            $check_kyc->inside_image = $if;

            $check_kyc->blat = $request->shop_lat;

            $check_kyc->blong = $request->shop_long;

            $check_kyc->update();

            $success['business_kyc'] = '' . $check_kyc->business_kyc; //0 - unverified,1 - pending ,2 - verified,3 - rejected

        }

        return $this->sendSuccess($success, "Business Verification is pending for admin approval");
    }



    public function getPincode(Request $request)
    {

        $pincode = $request->pincode;

        $response = Http::withHeaders([

            'Referer' => Config::get('constants.WEBSITE_BASE_URL'),

            'API-KEY' => Config::get('constants.APICLUB_API_KEY')

        ])->post('https://api.apiclub.in/api/v1/pincode_info', array('pincode' => $pincode));

        if ($response->successful()) {

            $resp = $response->json();

            if ($resp['code'] == 200 && $resp['status'] == 'success') {

                $city_name = $resp['response'][0]['region_name'];

                $state_name = $resp['response'][0]['state_name'];

                $state = State::where('state_name', 'LIKE', '%' . $state_name . '%')->first();

                // $cities = City::where('state_id',$state->state_id)->get();

                // foreach($cities as $id=>$city) {

                //     similar_text($city->city_name,$city_name,$percent);

                //     if($percent > 70) {

                //         $city_id = $id;

                //         $city_name = $city->region_name;

                //         break;

                //     }

                // }

                $res = array(

                    'state_name' => $state->state_name ?? "",

                    'state_id' => $state->state_id ?? 0,

                    'city_name' => $city_name ?? "",

                    'city_id' => 0

                );

                Log::info('API : ' . $response->body());

                return $this->sendSuccess($res);
            } else {

                return $this->sendError($resp['response'] ?? 'Something went wrong');
            }
        }

        return $this->sendError('Something went wrong');

        $states = State::select(['state_id', 'state_code', 'state_name'])->get();

        if ($states) {

            $response['states'] = $states;
        }
    }



    public function isAadharValid($num)
    {

        settype($num, "string");

        $expectedDigit = substr($num, -1);

        $actualDigit = $this->CheckAadharDigit(substr($num, 0, -1));

        return ($expectedDigit == $actualDigit) ? $expectedDigit == $actualDigit : 0;
    }



    public function CheckAadharDigit($partial)
    {



        $dihedral = array(

            array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9),

            array(1, 2, 3, 4, 0, 6, 7, 8, 9, 5),

            array(2, 3, 4, 0, 1, 7, 8, 9, 5, 6),

            array(3, 4, 0, 1, 2, 8, 9, 5, 6, 7),

            array(4, 0, 1, 2, 3, 9, 5, 6, 7, 8),

            array(5, 9, 8, 7, 6, 0, 4, 3, 2, 1),

            array(6, 5, 9, 8, 7, 1, 0, 4, 3, 2),

            array(7, 6, 5, 9, 8, 2, 1, 0, 4, 3),

            array(8, 7, 6, 5, 9, 3, 2, 1, 0, 4),

            array(9, 8, 7, 6, 5, 4, 3, 2, 1, 0)

        );

        $permutation = array(

            array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9),

            array(1, 5, 7, 6, 2, 8, 3, 0, 9, 4),

            array(5, 8, 0, 3, 7, 9, 6, 1, 4, 2),

            array(8, 9, 1, 6, 0, 4, 3, 5, 2, 7),

            array(9, 4, 5, 3, 1, 2, 6, 8, 7, 0),

            array(4, 2, 8, 6, 5, 7, 3, 9, 0, 1),

            array(2, 7, 9, 3, 8, 0, 6, 4, 1, 5),

            array(7, 0, 4, 6, 9, 1, 3, 2, 5, 8)

        );



        $inverse = array(0, 4, 3, 2, 1, 5, 6, 7, 8, 9);



        settype($partial, "string");

        $partial = strrev($partial);

        $digitIndex = 0;

        for ($i = 0; $i < strlen($partial); $i++) {

            $digitIndex = $dihedral[$digitIndex][$permutation[($i + 1) % 8][$partial[$i]]];
        }

        return $inverse[$digitIndex];
    }



    /**

     * User Sign Up Details

     */

    public function userSignUpDetails(Request $request)

    {

        $storeCategories = [];

        $states = [];

        $districts = [];



        $storeCategories = StoreCategory::select(['id', 'store_category_name'])->get();

        if ($storeCategories) {

            $response['store_categories'] = $storeCategories;
        }



        $states = State::select(['state_id', 'state_code', 'state_name'])->get();

        if ($states) {

            $response['states'] = $states;
        }



        $cities = City::select(['city_id', 'city_code', 'state_id', 'city_name']);



        if (isset($request->state_id) && $request->state_id) {

            $response = [];

            $cities = $cities->where('state_id', $request->state_id);
        }



        $cities = $cities->get();



        if ($cities) {

            $response['cities'] = $cities;
        }



        $statusMsg = "Success!!";

        return $this->sendSuccess($response, $statusMsg);
    }



    /**

     * Send Sms API

     */

    public function sendSmsApi(Request $request)

    {

        if (isset($request->message) && $request->message) {



            if (isset($request->mobile_nos) && count($request->mobile_nos) > 0) {



                foreach ($request->mobile_nos as $key => $no) {

                    $response = $this->sendSms($request->message, $no);
                }



                if ($response) {

                    $statusMsg = "SMS sent successfully!!";

                    $response = "Success!!";

                    return $this->sendSuccess($response, $statusMsg);
                }
            } else {

                $statusMsg = "Mobile No. cannot be empty!!";
            }
        } else {

            $statusMsg = "Message cannot be empty!!";
        }
    }



    /**

     * Update Kyc though API

     */

    public function updateKycApi(Request $request)

    {

        $validator = Validator::make($request->all(), [

            'user_id' => 'required',

            // 'pan_front_file_id' => 'numeric',

            // 'aadhar_front_file_id' => 'numeric',

            // 'aadhar_back_file_id' => 'numeric',

            // 'photo_front_file_id' => 'numeric',

            // 'photo_inner_file_id' => 'numeric',



        ]);



        if ($validator->fails()) {

            return $this->sendError('Validation Error!!', $validator->errors());
        }



        $responseMsg = "";

        if (isset($request->kyc_id) && $request->kyc_id) {



            $kyc = KycDetail::find((int) $request->kyc_id);



            if (isset($request->pan_front_file_id) && $request->pan_front_file_id) {

                if ($kyc->pan_front_file_id != $request->get('pan_front_file_id')) {

                    $kyc->pan_front_file_status = "PENDING";
                }

                $kyc->pan_front_file_id = $request->get('pan_front_file_id');
            }

            if (isset($request->aadhar_front_file_id) && $request->aadhar_front_file_id) {

                if ($kyc->aadhar_front_file_id != $request->get('aadhar_front_file_id')) {

                    $kyc->aadhar_front_file_status = "PENDING";
                }

                $kyc->aadhar_front_file_id = $request->get('aadhar_front_file_id');
            }

            if (isset($request->aadhar_back_file_id) && $request->aadhar_back_file_id) {

                if ($kyc->aadhar_back_file_id != $request->get('aadhar_back_file_id')) {

                    $kyc->aadhar_back_file_status = "PENDING";
                }

                $kyc->aadhar_back_file_id = $request->get('aadhar_back_file_id');
            }

            if (isset($request->photo_front_file_id) && $request->photo_front_file_id) {

                if ($kyc->photo_front_file_id != $request->get('photo_front_file_id')) {

                    $kyc->photo_front_file_status = "PENDING";
                }

                $kyc->photo_front_file_id = $request->get('photo_front_file_id');
            }

            if (isset($request->photo_inner_file_id) && $request->photo_inner_file_id) {

                if ($kyc->photo_inner_file_id != $request->get('photo_inner_file_id')) {

                    $kyc->photo_inner_file_status = "PENDING";
                }

                $kyc->photo_inner_file_id = $request->get('photo_inner_file_id');
            }



            if (isset($request->longitude) && $request->longitude) {

                if ($kyc->longitude != $request->get('longitude')) {

                    $kyc->longitude = $request->get('longitude');

                    $kyc->latitude = $request->get('latitude');
                }

                $kyc->longitude = $request->get('longitude');

                $kyc->latitude = $request->get('latitude');
            }



            $responseMsg = "KYC details updated!!";



            $kycDetail = $kyc->save();
        } else {

            $kycDetail = KycDetail::create([

                "user_id" => $request->get('user_id'),

                "pan_front_file_id" => $request->get('pan_front_file_id'),

                "aadhar_front_file_id" => $request->get('aadhar_front_file_id'),

                "aadhar_back_file_id" => $request->get('aadhar_back_file_id'),

                "photo_front_file_id" => $request->get('photo_front_file_id'),

                "photo_inner_file_id" => $request->get('photo_inner_file_id'),



                "pan_front_file_status" => 'PENDING',

                "aadhar_front_file_status" => 'PENDING',

                "aadhar_back_file_status" => 'PENDING',

                "photo_front_file_status" => 'PENDING',

                "photo_inner_file_status" => 'PENDING',



                "longitude" =>  $request->get('longitude'),

                "latitude" =>  $request->get('latitude'),



                "status" => 'PENDING',



            ]);



            $responseMsg = "KYC Request Submitted successfully!!";

            $success['kyc_id'] = $kycDetail->id;
        }



        if ($kycDetail) {

            $success['success'] = "Success!!";

            return $this->sendSuccess($success, $responseMsg);
        } else {

            return $this->sendError('Failure!!', $kycDetail);
        }
    }



    /**

     * Get Kyc details

     */

    public function getKycDetails(Request $request)

    {

        $kycDetail = [];

        if (isset($request->user_id) && $request->user_id) {

            $kycDetail = KycDetail::where('user_id', $request->user_id)

                ->with(['panFile', 'aadharFrontFile', 'aadharBackFile', 'photoFrontFile', 'photoInnerFile'])

                ->first();
        }



        if ($kycDetail) {

            $message = "Success!!";

            return $this->sendSuccess($kycDetail, $message);
        } else {

            return $this->sendError('No Kyc record found!!', $kycDetail);
        }
    }



    /**

     * Update User's Profile Pic

     */

    public function updateUserProfilePicApi(Request $request)

    {

        $result = "";

        if (!isset($request->profile_pic_id)) {

            $result = $this->sendError('Profile pic key not found!!');
        }

        $resultById = User::find((int) $request->user_id);

        if (isset($resultById) && $resultById) {

            $resultById->profile_pic_id = $request->profile_pic_id;

            $response = $resultById->save();

            if ($response) {

                $message = "Profile picture updated successfully!!";

                $res['status'] = "Success!!";

                $result = $this->sendSuccess($res, $message);
            } else {

                $result = $this->sendError('Failure!!', $response);
            }
        } else {

            $result = $this->sendError('No User record found!!');
        }



        if (isset($request->request_from) && $request->request_from == "in-app") {

            return back()->with('success', "Profile picture updated successfully!!");
        }



        return $result;
    }



    /**

     * Update User's Password

     */

    public function updateUserSecureData(Request $request)

    {

        $keyType = null;

        $requestKey = null;

        $newData = null;

        $validationRule = "";

        if (isset($request->type)) {

            if ($request->type == "MPIN") {

                $keyType = "mpin";

                $requestKey = "new_mpin";

                $validationRule = [$requestKey => 'numeric|digits_between:4,4'];
            } else if ($request->type == "PASSWORD") {

                $keyType = "password";

                $requestKey = "new_password";

                $validationRule = [$requestKey => 'string|min:6|max:20'];
            } else {

                return $this->sendError('Invalid [type] passed!!');
            }
        } else {

            return $this->sendError('Key "type" not found!!');
        }



        if (!isset($request[$requestKey])) {

            $errorMsg = "Key [" . $requestKey . "] not found!!";

            return $this->sendError($errorMsg);
        }



        $mpinValidationPattern = 'numeric|min:4';

        $pwdValidationPattern = 'string|min:6';



        $customMessages = [

            'new_mpin.digits_between' => 'The :attribute must be of 4 digits.',

            'new_password.digits_between' => 'The :attribute must be of 6 digits.'

        ];



        $validator = Validator::make($request->all(), $validationRule, $customMessages);



        if ($validator->fails()) {

            return $this->sendError('Validation Error!!', $validator->errors());
        }



        $setIsVerified = null;

        $otp = rand(100000, 999999);

        $resultById = User::find((int) $request->user_id);



        if (isset($resultById) && $resultById) {



            if (!isset($request->otp)) {

                // $msg = "Your Verification OTP is " . $otp;

                $msg = $otp . " is your verification code,Helpline : 040-29563154 www.paymamaapp.in";

                $sms_tempid = SmsTemplate::where('alias', Config::get('constants.SMS_TEMPLATE_ALIAS.VERIFY_USER_OTP.name'))->get()->first();



                $resultById->is_verified = 0;

                $resultById->logged_otp = $otp;

                $setNotVerified = $resultById->save();



                $data = array(

                    'name' => $resultById->first_name . " " . $resultById->last_name,

                    'otp' => $otp

                );

                Mail::send('mail.otp', $data, function ($msg) use ($resultById) {

                    $msg->to($resultById->email, $resultById->first_name . " " . $resultById->last_name)

                        ->subject("Password Reset OTP")

                        ->from('hello@paymamaapp.in', 'PayMama - Business Made Easy');
                });



                if ($setNotVerified) {

                    $response = $this->sendSms($msg, $resultById->mobile, $sms_tempid->template_id);

                    return $response;

                    $message = "Please verify with the verification OTP sent to your registered mobile!!";

                    $res['status'] = "Success!!";

                    return $this->sendSuccess($res, $message);
                }
            } else {

                if ($resultById->logged_otp != $request->otp) {

                    return $this->sendError('Invalid OTP!!');
                } else {

                    $resultById->is_verified = 1;

                    $setIsVerified = $resultById->save();
                }
            }



            if (isset($setIsVerified) && $setIsVerified) {

                $resultById[$keyType] = $requestKey == "new_password" ? Hash::make($request[$requestKey]) : $request[$requestKey];

                $resultById->logged_otp = $otp;

                $resultById->is_verified = 0;

                $response = $resultById->save();



                if ($response) {

                    $message = "Your " . $keyType . " is updated successfully!!";

                    $res['status'] = "Success!!";

                    $sendMsg = "Dear SMARTPAY User, Your new " . $keyType . " is successfully updated to :" . $request[$requestKey] . ". Helpline " . Config::get('constants.DEFAULT_HELPLINE') . " www.smartpaytech.in";

                    $msgResponse = $this->sendSms($sendMsg, $resultById->mobile);

                    return $this->sendSuccess($res, $message);
                } else {

                    return $this->sendError('Failure!!', $response);
                }
            }
        } else {

            return $this->sendError('No User record found!!');
        }
    }



    /**

     * Distributor Retailer/Fos List API

     */

    public function dTsRetailerListApi(Request $request)
    {

        $userList = $this->filterdtsRtListApi($request);

        $userList = $this->modifyUserList($userList);

        $userList = $this->hideUserListColumns($userList);



        return $userList;
    }



    /**

     * Hide User List Columns

     */

    public function hideUserListColumns($userList)
    {

        return $userList->makeHidden([

            'userId',

            'commission_id',

            'activated_status',

            'isDeleted',

            'logged_otp',

            'is_verified',

            'createdBy',

            'createdDtm',

            'updatedBy',

            'updatedDtm',

            'kyc_dtls',

            'mpin',

            'wallet_balance',

            'parent_user_id',

            'parent_role_id',

            'roleId',

            'store_category_id',

            'state_id',

            'district_id',

            'package_id',

            'profile_pic_id',

        ]);
    }



    /**

     * Filter User List API Distributor's Retailer and FOS

     */

    public function filterdtsRtListApi($request)

    {

        $userList = User::where('isDeleted', Config::get('constants.NOT-DELETED'))->orderBy('createdDtm', 'DESC')->orderBy('updatedDtm', 'DESC');



        if ($request->has('role_id') && isset($request->role_id)) {

            $userList->where('parent_role_id', $request->get('role_id'));
        }



        if ($request->has('user_id') && isset($request->user_id)) {

            $userList->where('parent_user_id', $request->get('user_id'));
        }



        if ($request->has('type') && isset($request->type)) {

            $roleId = Role::getIdFromAlias($request->type);

            $userList->where('roleId', $roleId);
        }





        return $userList->get();
    }



    /**

     * Storing user details into database

     */

    public function createDistributorMember(Request $request)

    {



        $validator = Validator::make($request->all(), [

            'first_name' => 'required|string|max:255',

            'last_name' => 'required|string|max:255',

            'email' => 'required|string|email|max:255|unique:tbl_users,email,' . $request->user_id . ',userId',

            'state_id' => 'required|numeric',

            'district_id' => 'required|numeric',

            'mobile' => 'numeric|min:10|unique:tbl_users,mobile,' . $request->user_id . ',userId',

        ]);



        if ($validator->fails()) {

            return $this->sendError("Validation Error!!", $validator->errors());
        }



        $mpin = rand(1000, 9999);

        $password = rand(100000, 999999);

        $username = $this->generateAutoUsername(Role::getIdFromAlias($request->type));

        $statusMsg = "";

        $success = [];



        if (isset($request->member_id) && $request->member_id) {



            $resultById = User::find((int) $request->member_id);



            $resultById->first_name = $request->get('first_name');

            $resultById->last_name = $request->get('last_name');

            $resultById->email = $request->get('email');

            $resultById->username = $resultById->username ? $resultById->username : $username;

            $resultById->mpin = $resultById->mpin ? $resultById->mpin : $mpin;

            $resultById->password = $resultById->password ? $resultById->password : Hash::make($password);

            $resultById->mobile = $request->get('mobile');

            $resultById->alternate_mob_no = $request->get('alternate_mob_no') ? $request->get('alternate_mob_no') : '';



            $resultById->pan_no = $request->get('pan_no');

            $resultById->aadhar_no = $request->get('aadhar_no');

            $resultById->gst_no = $request->get('gst_no');

            $resultById->whatsapp_no = $request->get('whatsapp_no');

            $resultById->telegram_no = $request->get('telegram_no');



            $resultById->roleId =  Role::getIdFromAlias($request->type);

            $resultById->parent_role_id = $request->role_id;

            $resultById->parent_user_id = $request->user_id;

            $resultById->state_id = $request->get('state_id');

            $resultById->district_id = $request->get('district_id');

            $resultById->address = $request->get('address');

            $resultById->zip_code = $request->get('zip_code');

            $resultById->store_name = $request->get('store_name');

            $resultById->store_category_id = $request->get('store_category_id');



            $response = $resultById->save();



            if ($response) {

                $statusMsg = "Member updated successfully!!";
            }
        } else {



            $user = User::create([

                'first_name' => $request->get('first_name'),

                'last_name' => $request->get('last_name'),

                'email' => $request->get('email'),

                'password' => Hash::make($password),

                'username' => $username,

                'mpin' => $mpin,

                'mobile' => $request->get('mobile'),

                'alternate_mob_no' => ($request->get('alternate_mob_no') ? $request->get('alternate_mob_no') : ''),



                'pan_no' => $request->get('pan_no'),

                'aadhar_no' => $request->get('aadhar_no'),

                'gst_no' => $request->get('gst_no'),

                'whatsapp_no' => $request->get('whatsapp_no'),

                'telegram_no' => $request->get('telegram_no'),



                'roleId' => Role::getIdFromAlias($request->type),

                'parent_role_id' => $request->role_id,

                'parent_user_id' => $request->user_id,

                'package_id' => 1,

                'state_id' => $request->get('state_id'),

                'district_id' => $request->get('district_id'),

                'address' => $request->get('address'),

                'zip_code' => $request->get('zip_code'),

                'store_name' => $request->get('store_name'),

                'store_category_id' => $request->get('store_category_id'),

                'wallet_balance' => 0,

                'commission_id' => '',

                'createdDtm' => now(),

                'createdBy' => $request->user_id

            ]);



            if ($user) {

                $message = $this->prepareRegistrationMsg($mpin, $password, $username);

                if ($message) {

                    $smsTemplateId = SmsTemplate::where('alias', Config::get('constants.SMS_TEMPLATE_ALIAS.USER_REGISTRATION.name'))->first();

                    $msgResponse = $this->sendSms($message, $request->get('mobile'), $smsTemplateId->template_id);

                    if ($msgResponse) {



                        if (Role::getIdFromAlias($request->type) == Config::get('constants.RETAILER')) {



                            $resultById = User::where('email', $request->get('email'))->where('mobile', $request->get('mobile'))->get()->first();

                            $this->insertAllowServices($resultById->userId);
                        }



                        $statusMsg = "Member registered successfully!!";
                    }
                }
            }
        }



        $success['success'] = "Success!!";

        return $this->sendSuccess($success, $statusMsg);
    }





    //API  get user by userid

    public function APIgetUserById(Request $request)
    {



        $validator = Validator::make($request->all(), [

            'user_id' => 'required',

        ]);



        if ($validator->fails()) {

            return $this->sendError('Validation Error.', $validator->errors());
        }





        if (isset($request->user_id) && $request->user_id) {

            $resultById = User::find((int) $request->user_id);

            if ($resultById) {



                $statusMsg = "Success!!";

                $success['fullname'] = $resultById->bank_account_name;

                $success['first_name'] = $resultById->first_name;

                $success['last_name'] = $resultById->last_name;

                $success['aadhaar_number'] = $resultById->aadhaar_number;

                $success['pan_number'] = $resultById->pan_number;

                $success['pin_code'] = $resultById->zip_code;

                $success['mobile'] = $resultById->mobile;

                $success['email'] = $resultById->email;

                $success['telegram_no'] = $resultById->telegram_no;

                $success['va_account_number'] = $resultById->va_account_number;

                $success['va_ifsc_code'] = $resultById->va_ifsc_code;

                $allpackage = PackageSetting::where('package_id', $resultById->package_id)->first();

                $success['package_name'] = $allpackage->package_name;

                $success['min_amount_deposit'] = $resultById->min_amount_deposit;

                $success['min_balance'] = $resultById->min_balance;

                $success['max_amount_deposit'] = $resultById->max_amount_deposit;

                $success['business_name'] = $resultById->business_name;

                $success['business_address'] = $resultById->business_address;

                $check_kyc = Ekyc::where('user_id', $request->user_id)->first();

                if ($check_kyc) {

                    $success['state_name'] = $check_kyc->state;

                    $success['city_name'] = $check_kyc->city;
                } else {

                    $success['state_name'] = "";

                    $success['city_name'] = "";
                }

                /*if($resultById->state_id == "") {

                        $success['state_name'] = "";

                    } else {

                        $allStates = State::where('state_id',$resultById->state_id)->first();

                        $success['state_name'] = $allStates->state_name;

                    }

                    if($resultById->district_id == "") {

                        $success['city_name'] = "";

                    } else {

                        $allCities = City::where('city_id',$resultById->district_id)->first();

                        $success['city_name'] = $allCities->city_name;

                    }*/

                if ($resultById->store_category_id == '') {

                    $success['category_name'] = "";
                } else {

                    $storeCategories = StoreCategory::where('id', $resultById->store_category_id)->first();

                    $success['category_name'] = $storeCategories->store_category_name;
                }







                /*$success['userId'] = $resultById->userId; 

                    $success['roleId'] = $resultById->roleId;

                    $success['first_name'] = $resultById->first_name;

                    $success['last_name'] = $resultById->last_name;

                    $success['store_name'] = $resultById->store_name;

                    $success['aadhar_no'] = $resultById->aadhar_no;

                    $success['gst_no'] = $resultById->gst_no;

                    $success['mobile'] = $resultById->mobile;

                    $success['whatsapp_no'] = $resultById->whatsapp_no;

                    $success['telegram_no'] = $resultById->telegram_no;

                    $success['alternate_mob_no'] = $resultById->alternate_mob_no;

                    $success['email'] = $resultById->email;

                   

                    $success['store_name'] = $resultById->store_name;

                    $success['store_category_id'] = $resultById->store_category_id;

                    $success['store_category_name'] = StoreCategory::getStoreCatNameById($resultById->store_category_id);

                    $success['pan_no'] = $resultById->pan_no;

                    $success['state_id'] = $resultById->state_id;

                    $success['state'] = State::getStateNameById($resultById->state_id);

                    $success['district_id'] = $resultById->district_id;

                    $success['district'] = City::getCityNameById($resultById->district_id);

                    $success['address'] = $resultById->address;

                    $success['zip_code'] = $resultById->zip_code;

                    $success['activated_status'] = $resultById->activated_status;*/





                return $this->sendSuccess($success, $statusMsg);
            } else {



                $result = $this->sendError('No User record found!!');
            }
        } else {

            $result = $this->sendError('No User record found!!');
        }

        // $user_dtls = User::where('userId', $request->user_id);





    }











    //eko getsecret key and keytimestramp

    public function getsecretkey_timestramp($skey)
    {

        // Initializing key in some variable. You will receive this key from Eko via email

        $key = $skey;

        // Encode it using base64

        $encodedKey = base64_encode($key);

        // Get current timestamp in milliseconds since UNIX epoch as STRING

        // Check out https://currentmillis.com to understand the timestamp format

        $secret_key_timestamp = "" . round(microtime(true) * 1000);

        // Computes the signature by hashing the salt with the encoded key 

        $signature = hash_hmac('SHA256', $secret_key_timestamp, $encodedKey, true);

        // Encode it using base64

        $secret_key = base64_encode($signature);

        $key = array('secret_key' => $secret_key, 'secret_key_timestamp' => $secret_key_timestamp);

        return json_encode($key);
    }



    public function spamUser($id = null, Request $request)
    {





        $setSpam = User::where('userId', $id)

            ->update(['isSpam' => Config::get('constants.SPAM')]);

        if ($setSpam) {

            return  back()->with('success', 'User Spam!!');
        } else {



            return back()->with('error', 'Failed!!');
        }
    }



    public function spamVerificationUser($id = null, Request $request)
    {





        $setSpam = Verification::where('id', $id)

            ->update(['isSpam' => Config::get('constants.SPAM')]);

        if ($setSpam) {

            return  back()->with('success', 'User Spam!!');
        } else {



            return back()->with('error', 'Failed!!');
        }
    }



    public function regenerateQr($id, Request $request)
    {

        $user_info = User::where('userId', $id)->get()->first();

        if ($user_info->va_upi_id != "") {

            if ($user_info->qr_id == "") {

                $data = 'name=' . $user_info->store_name . '&vpa=' . $user_info->va_upi_id . '&show_name=true&show_upi=false&type=UPI&logo_type=round&template_id=qr_paymama';
            } else {

                $data = 'name=' . $user_info->store_name . '&vpa=' . $user_info->va_upi_id . '&show_name=true&show_upi=false&type=UPI&logo_type=round&template_id=qr_paymama&update_id=' . $user_info->qr_id; //customtemplate id given by apiclub

            }

            $curl = curl_init();

            curl_setopt_array($curl, array(

                CURLOPT_URL => 'https://api.apiclub.in/api/v1/generate_qr',

                CURLOPT_RETURNTRANSFER => true,

                CURLOPT_ENCODING => '',

                CURLOPT_MAXREDIRS => 10,

                CURLOPT_TIMEOUT => 0,

                CURLOPT_FOLLOWLOCATION => true,

                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,

                CURLOPT_CUSTOMREQUEST => 'POST',

                CURLOPT_POSTFIELDS => $data,

                CURLOPT_HTTPHEADER => array(

                    'Referer: ' . Config::get('constants.WEBSITE_BASE_URL'),

                    'API-KEY: ' . Config::get('constants.APICLUB_API_KEY'),

                    'Content-Type: application/x-www-form-urlencoded'

                ),

            ));



            $response = curl_exec($curl);

            curl_close($curl);

            Log::info('APIclub : ' . $response);



            $resp = json_decode($response, true);

            if (isset($resp['code']) && $resp['code'] == 200 && $resp['status'] == 'success') {

                $qr_id = $resp['response']['qr_id'];

                $update = User::where('userId', $id)->update(['qr_id' => $qr_id]);

                if ($update) {

                    return  back()->with('success', 'QR Regenerated success!!');
                }
            }
        } else {

            return back()->with('error', 'VPA Is not generated!!');
        }

        return back()->with('error', 'Failed!!');
    }



    public function removeSpam($id = null, Request $request)
    {

        $setSpam = User::where('userId', $id)

            ->update(['isSpam' => Config::get('constants.NOT-SPAM')]);

        if ($setSpam) {

            return  back()->with('success', 'Spam Removed!!');
        } else {



            return back()->with('error', 'Failed!!');
        }
    }

    public function removeVerificationSpam($id = null, Request $request)
    {

        $setSpam = Verification::where('id', $id)

            ->update(['isSpam' => Config::get('constants.NOT-SPAM')]);

        if ($setSpam) {

            return  back()->with('success', 'Spam Removed!!');
        } else {



            return back()->with('error', 'Failed!!');
        }
    }



    public function getUserPgServicesByUserId($user_id)
    {

        $user_info = User::where('userId', $user_id)->get()->first();





        $pg_options = !empty($user_info->pg_options) ? json_decode($user_info->pg_options, true) : '';



        //   $response = array(

        //       "pg_status" =>  $user_info->pg_status != "" ? $user_info->pg_status : 0,

        //       "user_id" => $user_info->userId,

        //       "credit_card" => isset($pg_options['credit_card']) ? $pg_options['credit_card'] : 0,

        //       "debit_card" => isset($pg_options['debit_card']) ? $pg_options['debit_card'] : 0,

        //       "rupay_card" => isset($pg_options['rupay_card']) ? $pg_options['rupay_card'] : 0,

        //       "upi" => isset($pg_options['upi']) ? $pg_options['upi'] : 0,

        //       "wallet" => isset($pg_options['wallet']) ? $pg_options['wallet'] : 0,

        //       "net_banking" => isset($pg_options['net_banking']) ? $pg_options['net_banking'] : 0

        //   );

        $response = array(

            "pg_status" =>  $user_info->pg_status != "" ? $user_info->pg_status : 0,

            "user_id" => $user_info->userId,

            "upi" => isset($pg_options['upi']) ? $pg_options['upi'] : array(),

            "rupay_card" => isset($pg_options['rupay_card']) ? $pg_options['rupay_card'] : array(),

            "debit_card" => isset($pg_options['debit_card']) ? $pg_options['debit_card'] : array(),

            "credit_card" => isset($pg_options['credit_card']) ? $pg_options['credit_card'] : array(),

            "prepaid_card" => isset($pg_options['prepaid_card']) ? $pg_options['prepaid_card'] : array(),

            "corporate_card" => isset($pg_options['corporate_card']) ? $pg_options['corporate_card'] : array(),

            "net_banking" => isset($pg_options['net_banking']) ? $pg_options['net_banking'] : array(),

            "wallet" => isset($pg_options['wallet']) ? $pg_options['wallet'] : array(),

        );

        return $response;
    }



    public function getUserServicesByUserId_API($user_id)
    {



        $services = DB::table('tbl_user_services')->select('tbl_services_type.service_name', 'tbl_user_services.status')

            ->leftJoin('tbl_services_type', 'tbl_user_services.service_id', '=', 'tbl_services_type.service_id')

            ->where('user_id', $user_id)

            ->get();

        $services = (count($services) > 0) ? $services : [];

        return $services;
    }



    public function getUserServicesByUserId($user_id)
    {



        $services = DB::table('tbl_user_services')

            ->leftJoin('tbl_services_type', 'tbl_user_services.service_id', '=', 'tbl_services_type.service_id')

            ->where('user_id', $user_id)

            ->get();

        $services = (count($services) > 0) ? $services : [];

        return $services;
    }

    public function updateUserSesrvices(Request $request)
    {



        $allServices = ServicesType::where('activated_status', Config::get('constants.ACTIVE'))->where('is_deleted', Config::get('constants.NOT-DELETED'))->get();

        foreach ($allServices as $key => $value) {

            $update_arr = [];

            // if (array_key_exists($value['alias'],$request))

            if ($request->has($value['alias'])) {

                //    $update_arr = [ $value['alias'] => $request[$value['alias']]];

                $update_arr = ['status' => 1];
            } else {

                // $update_arr = [ $value['alias'] => 0];

                $update_arr = ['status' => 0];
            }



            $update_services = DB::table('tbl_user_services')

                ->where('user_id', $request->allow_service_user_id)

                ->where('service_id', $value['service_id'])

                ->update($update_arr);
        }



        return back()->with('success', 'User Services Updated Successfully !!');
    }

    public function changeStatus(Request $request)

    {

        $update_arr = ['status' => $request->status];

        if ($request->service_id == 4) {



            $update_services = DB::table('tbl_user_services')

                ->where('user_id', $request->user_id)

                ->where('service_id', 2)

                ->update($update_arr);

            $update_services = DB::table('tbl_user_services')

                ->where('user_id', $request->user_id)

                ->where('service_id', 4)

                ->update($update_arr);
        } else {

            $update_services = DB::table('tbl_user_services')

                ->where('user_id', $request->user_id)

                ->where('service_id', $request->service_id)

                ->update($update_arr);
        }





        return back()->with('success', 'User Services Updated Successfully !!');
    }



    public function updatebankdetails(Request $request)

    {

        $fullname = $request->full_name;

        $bank_account_no = $request->bank_account_no;

        $ifsc_code = $request->ifsc_code;

        $branch_name = $request->branch_name;

        $bank_name = $request->bank_name;

        $user = User::find((int) $request->user_id);

        $ekyc = Ekyc::where('user_id', $user->userId)->update(['acc_name' => $request->full_name, 'acc_no' => $request->bank_account_no, 'ifsc_code' => $request->ifsc_code, 'branch_name' => $request->branch_name, 'bank_name' => $request->bank_name]);

        $ekyc = User::where('userId', $user->userId)->update(['bank_account_name' => $request->full_name, 'account_number' => $request->bank_account_no, 'ifsc_code' => $request->ifsc_code, 'branch_name' => $request->branch_name, 'bank_name' => $request->bank_name]);

        if ($ekyc) {

            if ($user->va_id == "") {

                $this->create_va_test($user->username);
            } else {

                $this->update_va($request->user_id);
            }

            return back()->with('success', 'Bank Details Updated Successfully !!');
        } else {

            return back()->with('success', 'Please ask User to complete Ekyc !!');
        }
    }



    public function updateUserPgSesrvices(Request $request)
    {

        $options = array(

            "upi" => isset($request->upi) ? $request->upi : 0,

            "rupay_card" => isset($request->rupay_card) ? $request->rupay_card : 0,

            "debit_card" => isset($request->debit_card) ? $request->debit_card : 0,

            "credit_card" => isset($request->credit_card) ? $request->credit_card : 0,

            "prepaid_card" => isset($request->prepaid_card) ? $request->prepaid_card : 0,

            "corporate_card" => isset($request->corporate_card) ? $request->corporate_card : 0,

            "wallet" => isset($request->wallet) ? $request->wallet : 0,

            "net_banking" => isset($request->net_banking) ? $request->net_banking : 0

        );



        // {"upi":{"mode": "UPI","status":1,"charge":1,"type": "%"},"rupay_card":{"mode": "RUPAY_CARD","status":1,"charge": 0,"type": "%"},"debit_card":{"mode": "DEBIT_CARD","status": 0,"charge": 1.50,"type": "%"},"credit_card":{"mode": "CREDIT_CARD","status": 0,"charge": 1.50,"type": "%"},"prepaid_card":{"mode": "PREPAID_CARD","status": 0,"charge": 1.50,"type": "%"},"corporate_card":{"mode": "CORPORATE_CARD","status": 0,"charge": 2.80,"type": "%"},"wallet":{"mode": "WALLET","status":0,"charge": 2.30,"type": "%"},"net_banking":{"mode": "NET_BANKING","status":0,"charge": 2.30,"type": "%"}}



        $options = array(

            "upi" => array(

                'status' => isset($request->upi) ? $request->upi : 0,

                'mode' => 'UPI',

                'charge' => '' . isset($request->upi_charge_mode) ? $request->upi_charge_mode : 0,

                'type' => '%'

            ),

            "rupay_card" => array(

                'status' => isset($request->rupay_card) ? $request->rupay_card : 0,

                'mode' => 'RUPAY_CARD',

                'charge' => '' . isset($request->rc_charge_mode) ? $request->rc_charge_mode : 0,

                'type' => '%'

            ),

            "debit_card" => array(

                'status' => isset($request->debit_card) ? $request->debit_card : 0,

                'mode' => 'DEBIT_CARD',

                'charge' => '' . isset($request->dc_charge_mode) ? $request->dc_charge_mode : 0,

                'type' => '%'

            ),

            "credit_card" => array(

                'status' => isset($request->credit_card) ? $request->credit_card : 0,

                'mode' => 'CREDIT_CARD',

                'charge' => '' . isset($request->cc_charge_mode) ? $request->cc_charge_mode : 0,

                'type' => '%'

            ),

            "prepaid_card" => array(

                'status' => isset($request->prepaid_card) ? $request->prepaid_card : 0,

                'mode' => 'PREPAID_CARD',

                'charge' => '' . isset($request->pc_charge_mode) ? $request->pc_charge_mode : 0,

                'type' => '%'

            ),

            "corporate_card" => array(

                'status' => isset($request->corporate_card) ? $request->corporate_card : 0,

                'mode' => 'CORPORATE_CARD',

                'charge' => '' . isset($request->ccc_charge_mode) ? $request->ccc_charge_mode : 0,

                'type' => '%'

            ),

            "wallet" => array(

                'status' => isset($request->wallet) ? $request->wallet : 0,

                'mode' => 'WALLET',

                'charge' => '' . isset($request->wa_charge_mode) ? $request->wa_charge_mode : 0,

                'type' => '%'

            ),

            "net_banking" => array(

                'status' => isset($request->net_banking) ? $request->net_banking : 0,

                'mode' => 'NET_BANKING',

                'charge' => '' . isset($request->nb_charge_mode) ? $request->nb_charge_mode : 0,

                'type' => '%'

            ),

        );





        /*$update_user = User::where('userId', $request->allow_pg_user_id)->update([

        'pg_status' => $request->pg_status,

        'pg_options' => json_encode($options),

        'updatedDtm'=>now()

    ]);*/



        $update = array(

            'pg_status' => $request->pg_status,

            'pg_options' => json_encode($options),

            'updatedDtm' => now()

        );



        // print_r($update);die();



        $updateUser = User::where('userId', $request->allow_pg_user_id)

            ->update($update);



        if ($updateUser) {

            return back()->with('success', 'User PG Services Upadated Successfully !!');
        }

        return back()->with('error', 'User PG Services Upadate Failed !!');
    }





    public function insertAllowServices($user_id)
    {



        $allServices = ServicesType::where('activated_status', Config::get('constants.ACTIVE'))->get();



        $isServicePresent =  DB::table('tbl_user_services')->where('user_id', $user_id)->get();

        if (count($isServicePresent) > 0) {

            return true;
        }

        foreach ($allServices as $key => $value) {

            $insert_service = DB::table('tbl_user_services')->insert(['user_id' => $user_id, 'service_id' => $value['service_id']]);
        }

        return true;
    }



    public function getParentInfoAPI(Request $request)
    {



        $result = [];

        $user_info = User::where('userId', $request->user_id)->get()->first();

        if (isset($user_info->parent_user_id)) {

            $result['distributor'] = null;

            $result['fos'] = null;

            $parent_user =  User::where('userId', $user_info->parent_user_id)->get()->first();

            if ($parent_user) {



                $result['distributor']['first_name'] = $parent_user->first_name;

                $result['distributor']['last_name'] = $parent_user->last_name;

                // $result['distributor']['role'] = Role::getNameById($parent_user->roleId);

                $result['distributor']['mobile'] = $parent_user->mobile;

                $result['distributor']['address'] = $parent_user->address;
            }

            $parent_fos =  User::where('userId', $user_info->fos_id)->get()->first();

            if ($parent_fos) {



                $result['fos']['first_name'] = $parent_fos->first_name;

                $result['fos']['last_name'] = $parent_fos->last_name;

                // $result['fos']['role'] = Role::getNameById($parent_fos->roleId);

                $result['fos']['mobile'] = $parent_fos->mobile;

                $result['fos']['address'] = $parent_fos->address;
            }

            $statusMsg = 'Success';

            return $this->sendSuccess($result, $statusMsg);
        }

        $result = $this->sendError('No Parent found!!');
    }



    public function insertUserPermission($user_id)
    {

        $insert_row =  DB::table('tbl_user_persmissions')->insert(['user_id' => $user_id]);

        return $insert_row;
    }



    public function createSubAdmin($id = null, Request $request)
    {

        $user_info = null;

        if ($id) {

            $user_info = User::where('userId', $id)->get()->first();
        }

        return  view('modules.user.create_subadmin', compact('user_info'));
    }





    public function deleteUser(Request $request)
    {



        $resultById = User::find((int) $request->delete_user_id);



        if ($resultById) {

            $resultById->isDeleted = 1;

            $response = $resultById->save();



            if ($response) {

                $success = "Success!!";

                return redirect('/user_list')->with('success', 'User Deleted Successfully !!!');
            } else {

                return redirect('/user_list')->with('error', 'Failed !!');
            }
        }

        return redirect('/user_list')->with('error', 'User not found !!');
    }

    public function deleteVerificationUser(Request $request)
    {



        $resultById = Verification::find((int) $request->delete_user_id);



        if ($resultById) {

            $resultById->isDeleted = 1;

            $response = $resultById->save();



            if ($response) {

                $success = "Success!!";

                return redirect('/verification_list')->with('success', 'User Deleted Successfully !!!');
            } else {

                return redirect('/verification_list')->with('error', 'Failed !!');
            }
        }

        return redirect('/user_list')->with('error', 'User not found !!');
    }



    public function getUserPermission(Request $request)
    {

        $result = DB::table('tbl_user_persmissions')->where('user_id', $request->get('id'))->get()->first();

        // print_r($result);

        return json_encode($result);
    }



    public function updateUserPermisssion(Request $request)
    {



        $all_menu = json_decode(json_encode(DB::table('tbl_menu')->get()), true);

        $update_arr = [];

        foreach ($all_menu as $key => $value) {



            if ($request->has($value['alias'])) {

                $update_arr[$value['alias']] = 1;
            } else {

                $update_arr[$value['alias']] = 0;
            }
        }

        $update_arr['updated_on'] = now();



        $update_permissions = DB::table('tbl_user_persmissions')

            ->where('user_id', $request->permission_id)

            ->update($update_arr);

        if ($update_permissions) {

            $success = "Success!!";

            return back()->with('success', 'User Pernission Updated Successfully !!!');
        } else {

            return back()->with('error', 'Failed !!');
        }
    }



    //certificate

    public function userCertificate(Request $request)
    {



        $name = 'VISHAL RANGARI';

        $date = '27-11-1995';

        $img_certificate = Config::get('constants.WEBSITE_BASE_URL') . "public/template_assets/assets/images/certificate.jpeg";



        return view('modules.user.certificate', compact('img_certificate'));
    }

    public function userCertificateAPI(Request $request)
    {



        $name = 'VISHAL RANGARI';

        $date = '27-11-1995';



        $user_cert =  $this->exportPDF_Certificate($name, $date);



        $response = base64_encode($user_cert);

        // print_r($response);

        $statusMsg = "Success!!";

        $result = [

            'pdf_base64' => $response,

        ];

        return $this->sendSuccess($result, $statusMsg);

        // return view('modules.user.certificate');

    }



    public function exportPDF_Certificate($name, $date)
    {



        $fileName = $name;



        $img_certificate = Config::get('constants.WEBSITE_BASE_URL') . "public/template_assets/assets/images/certificate.jpeg";



        $pdf = PDF::loadView('modules.user.certificate', compact('img_certificate'));

        $pdf->setPaper('A4', 'landscape');



        // $response = $pdf->download('xyz.pdf');

        $response = $pdf->download($fileName . '.pdf');



        return $response;
    }



    public function getNotificationLogs(Request $request)
    {

        $notify_logs = DB::table('tbl_notifiaction_log')->where('user_id', $request->user_id)->orderBy('id', 'DESC')->get();



        if (count($notify_logs) > 0) {

            $statusMsg = 'Success';

            return $this->sendSuccess($notify_logs, $statusMsg);
        }

        return $this->sendError('Empty !!');
    }



    /*

        get FOS of perticular Distributor

    */

    public function getFosByDist(Request $request)
    {



        $response = [];

        if (isset($request->parent_user_id) && $request->parent_user_id) {

            $response = User::where('parent_user_id', $request->parent_user_id)->where('roleId', Config::get('constants.FOS'))->get();
        }



        return $response;
    }



    /*

        get FOS of perticular Distributor Using API

    */

    public function getFosByDistAPI(Request $request)
    {





        if (isset($request->user_id) && $request->user_id) {

            $response = User::where('parent_user_id', $request->user_id)->where('roleId', Config::get('constants.FOS'))->get();

            if (count($response) > 0) {

                $statusMsg = 'Success';

                return $this->sendSuccess($response, $statusMsg);
            }

            return $this->sendError('No FOS Found !!');
        }

        return $this->sendError('No FOS Found !!');
    }



    /*

        Allocate FOS to Retailer by Distributor using API

    */

    public function updateUserFos(Request $request)
    {



        $resultById = User::find((int) $request->retailer_id);

        $resultById->fos_id = (isset($request->fos_id)) ? $request->fos_id : null;

        $user = $resultById->save();

        if ($user) {

            $statusMsg = 'Success';

            return $this->sendSuccess($user, $statusMsg);
        }

        return $this->sendError('Failed !!');
    }



    public function notificationViewed(Request $request)
    {



        $allNotViewed = DB::table('tbl_notifiaction_log')->select('id')->where('user_id', $request->user_id)

            ->where('isViewed', '0')

            ->pluck('id');



        if ($allNotViewed) {

            $setViewed = DB::table('tbl_notifiaction_log')->whereIn('id', $allNotViewed)

                ->update(['isViewed' => '1']);
        }

        $statusMsg = 'Success';

        return $this->sendSuccess($request, $statusMsg);
    }



    public function getNotificationCount($user_id)
    {



        $notification = DB::table('tbl_notifiaction_log')->select('id')

            ->where('user_id', $user_id)

            ->where('isViewed', '0')->get();

        $notification_count = count($notification);



        return  $notification_count;
    }



    public function userNotification(Request $request)
    {

        $all_notification = DB::table('tbl_notifiaction_log')

            ->where('user_id', Auth::user()->userId)

            ->orderBy('id', 'DESC')

            ->get();

        $all_notification = (count($all_notification) > 0) ? $all_notification : [];

        // print_r($all_notification);

        // exit;

        return view('modules.user.notifications', compact('all_notification'));
    }



    public function twoFactor()
    {

        // include_once(app_path() .'/Packages/Authenticator.php');

        $user = User::where('userId', Auth::user()->userId)->get()->first();

        $tfa = new \App\Packages\Authenticator\Authenticator();

        if ($user->tfa == '0' || empty($user->tfa)) {

            $tfa_secret = $tfa->createSecret();
        } else {

            $tfa_secret = $user->tfa_secret;
        }

        $qr_link = $tfa->GetQR("PayMama (" . $user->username . ")", $tfa_secret);

        // return $qr_link;

        if (!empty($user->tfa_codes)) {

            $codes_arr = explode(',', $user->tfa_codes);
        } else {

            $codes_arr = [];
        }

        return view('modules.user.two_factor', compact('user', 'qr_link', 'codes_arr', 'tfa_secret'));
    }



    public function UpdatetwoFactor(Request $request)
    {

        if (isset($request->user_id) && isset($request->tfa_secret)) {

            if ($request->user_id != Auth::user()->userId) {

                return redirect('/two_factor')->with('error', 'Token mismatch !!');
            }



            $user = User::where('userId', $request->user_id)->get()->first();

            if (isset($request->tfa_status) && $request->tfa_status == '1') {

                if ($request->otp == "") {

                    return redirect('/two_factor')->with('error', 'Invalid OTP !!');
                }

                $ga = new \App\Packages\Authenticator\Authenticator();

                $backup_pass = false;

                $otp = $request->otp ?? "0";

                $checkResult = $ga->verify($request->tfa_secret, $request->otp);

                if (!$checkResult) {

                    return redirect('/two_factor')->with('error', 'OTP Expired !!');
                }

                $codes = array();

                for ($i = 1; $i <= 5; $i++) {

                    $codes[] = $this->get_random_num(6);
                }

                $user->tfa = '1';

                $user->tfa_codes = implode(',', $codes);

                $user->tfa_secret = $request->tfa_secret;
            } else {

                $user->tfa = '0';

                $user->tfa_codes = "";

                $user->tfa_secret = "";
            }

            $response = $user->save();

            if ($response) {

                return redirect('/two_factor')->with('success', 'Updated Successfully !!');
            } else {

                return redirect('/two_factor')->with('error', 'Something went wrong1 !!');
            }
        }

        return redirect('/two_factor')->with('error', 'Something went wrong2 !!');
    }



    public function verify2fa()

    {

        return view('layouts.security.verify-two-factor');
    }



    public function check2fa(Request $request)

    {

        // print_r($request->all());die();

        $message = "INVALID CODE";

        if (isset($request->otp) && $request->otp && Session::has('userId') && Session::get('username')  && Session::get('password')) {

            $userId = Session::get('userId');

            $user = User::where('userId', $userId)->first();

            $roleId = $user->roleId;

            if ($user) {

                $ga = new \App\Packages\Authenticator\Authenticator();

                $backup_pass = false;

                $otp = $request->otp;

                $checkResult = $ga->verify($user->tfa_secret, $otp);

                if ($user->tfa_codes) {

                    $backup_pass = false;

                    $backup_codes = explode(',', $user->tfa_codes);

                    if (in_array($otp, $backup_codes)) {

                        $backup_pass = true;

                        $key = array_search($otp, $backup_codes);

                        unset($backup_codes[$key]);

                        $user->tfa_codes = implode(',', $backup_codes);
                    }
                }

                if ($checkResult || $backup_pass == true) {

                    $credentials = array("username" => Session::get('username'), "password" => Session::get('password'));

                    Auth::attempt($credentials);

                    $user->last_login_ip = $this->getRealIpAddr();

                    $user->save();

                    Session::forget('userId');

                    Session::forget('username');

                    Session::forget('password');

                    if ($roleId == Config::get('constants.ADMIN')) {



                        return redirect()->route('admin-home');
                    } else {

                        return redirect('/home');
                    }
                }
            }
        }

        return redirect('verify-2fa')->with('message', $message);
    }



    public function getRealIpAddr()
    {

        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {

            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {

            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {

            $ip = $_SERVER['REMOTE_ADDR'];
        }

        // return $ip;

        $ips = explode(',', $ip);

        return $ips[0];
    }



    public function get_random_num($length = 0)
    {

        $characters = str_shuffle('0123456789');

        $string = '';

        for ($p = 0; $p < $length; $p++) {

            $string .= $characters[mt_rand(0, strlen($characters) - 1)];
        }

        return $string;
    }


    public function getDistributorInfo()
    {
        $dist = User::where('userId', Auth::user()->parent_user_id)->first();


        return view('modules.user.distributor_info', compact('dist'));
    }


    public function distributorFosList(Request $request)
    {

        $userList = User::where('roleId', 3)->where('isDeleted', Config::get('constants.NOT-DELETED'))->where('parent_user_id', Auth::user()->userId);

        //filter

        if ($request->has('agentid') && $request->agentid != null) {
            $userList =  $userList->where('username', $request->agentid);
        }

        if ($request->has('agentname') && $request->agentname != null) {
            $userList =  $userList->where('first_name', $request->agentname);
        }

        if ($request->has('agentmobile') && $request->agentmobile != null) {
            $userList =  $userList->where('mobile', $request->agentmobile);
        }


        $dtPaymentType = Config::get('constants.DT_PAYMENT_TYPE');
        $bankAccounts = BankAccount::where('is_deleted', Config::get('constants.NOT-DELETED'))->get();

        $userList =  $userList->orderBy('createdDtm', 'DESC')->where('.parent_role_id', Auth::user()->roleId)->get();

        //endfilter//

        return view('modules.user.distributorfoslist', compact('userList', 'dtPaymentType', 'bankAccounts'));
    }

    public function createnewpm()
    {

        return view('modules.user.createNewPm');
    }


    public function createnewfos()
    {

        return view('modules.user.createnewfos');
    }

    public function storenewfos(Request $request)
    {


        $validator = Validator::make($request->all(), [

            'name' => 'required',

            'aadhar' => 'required',

            'email' => 'required|string|email|max:255|unique:tbl_users,email',

            'mobile' => 'required|unique:tbl_users,mobile',

            'pan' => 'required',

            'parent_user_id' => 'required',
            'parent_role_id' => 'required',



        ]);



        if ($validator->fails()) {

            // return $this->sendError($validator->errors()->first());

            // return view('')->with('msg', $validator->errors()->first());

            $error = $validator->errors()->first();

            return view('modules.user.createnewfos', compact('error'));
        }

        $otp = '';

        $mpin = rand(1000, 9999);

        $password = rand(100000, 999999);

        $loggedOtp = rand(100000, 999999);

        $roleId = Config::get('constants.FOS');

        $username = $this->generateAutoUsername($roleId);



        $statusMsg = "";

        $success = null;

        $pg_options = '{"upi":{"mode": "UPI","status":1,"charge":0,"type": "%"},"rupay_card":{"mode": "RUPAY_CARD","status":0,"charge": 0,"type": "%"},"debit_card":{"mode": "DEBIT_CARD","status": 0,"charge": 1.50,"type": "%"},"credit_card":{"mode": "CREDIT_CARD","status": 0,"charge": 1.50,"type": "%"},"prepaid_card":{"mode": "PREPAID_CARD","status": 0,"charge": 1.50,"type": "%"},"corporate_card":{"mode": "CORPORATE_CARD","status": 0,"charge": 2.80,"type": "%"},"wallet":{"mode": "WALLET","status":0,"charge": 2.30,"type": "%"},"net_banking":{"mode": "NET_BANKING","status":0,"charge": 2.30,"type": "%"}}';

        $user = User::create([

            'first_name' => $request->get('name'),

            'last_name' => '', //confirm

            'user_dob' => now(),

            'email' => $request->get('email'),

            'password' => Hash::make($password),

            'username' => $username,

            'mpin' => $mpin,

            'user_code' => '',

            'mobile' => $request->get('mobile'),

            'alternate_mob_no' => '',

            'pan_no' => $request->get('aadhar'),

            'aadhar_no' => $request->get('pan'),

            'gst_no' => '',

            'whatsapp_no' => '',

            'telegram_no' => '',

            'roleId' => $roleId,

            'min_amount_deposit' => 0,

            'min_balance' => 0,

            'max_amount_deposit' => 0,

            'min_amount_withdraw' => 0,

            'max_amount_withdraw' => 0,

            'parent_role_id' => $request->parent_role_id ?? Config::get('constants.DISTRIBUTOR'),

            'parent_user_id' => $request->parent_user_id,

            'package_id' => $request->package_id ?? '1',

            'state_id' => '',

            'district_id' => '',

            'activated_status' => Config::get('constants.ACTIVE'),

            'address' => '',

            'zip_code' => '',

            'store_name' => $request->get('business') ?? '',

            'store_category_id' => 0,

            'wallet_balance' => 0,

            'commission_id' => '',

            'pg_options' => $pg_options,

            'pg_status' => '0',

            'createdDtm' => now(),

            'logged_otp' => 0000,

            'createdBy' => 0,

        ]);

        if ($user) {


            $message = $this->prepareRegistrationMsg($mpin, $password, $username);

            if ($message) {

                $email = $request->get('email');

                $name = $request->get('name');

                $data = array(

                    'email' => $email,

                    'name' => $name,

                    'username' => $username,

                    'password' => $password,

                    'mpin' => $mpin

                );

                $send_email = Mail::send('mail.welcomefos', $data, function ($msg) use ($email, $name) {

                    $msg->to($email, $name);

                    $msg->subject('Welcome to PayMama');

                    $msg->from('hello@paymamaapp.in', 'PayMama - Business Made Easy');
                });

                $smsTemplateId = SmsTemplate::where('alias', Config::get('constants.SMS_TEMPLATE_ALIAS.USER_REGISTRATION.name'))->first();

                $this->sendSms($message, $request->get('mobile'), $smsTemplateId->template_id);

                $statusMsg = "Congratulations " . $name . ". Verification successful. Check Mobile for Login Credentials.";

                $success = 1;


                return view('modules.user.createnewfos', compact('success'));
            }
        }
    }


    public function createnewretailer()
    {

        $fos_users = User::where('roleId', Config::get('constants.FOS'))->orderBy('first_name')->get();

        return view('modules.user.createNewRetailerPm', compact('fos_users'));
    }



    public function storenewretailer(Request $request)
    {


        $validator = Validator::make($request->all(), [

            'first_name' => 'required',

            'last_name' => 'required',

            'email_id' => 'required|string|email|max:255|unique:tbl_users,email',

            'mobile_no' => 'required|unique:tbl_users,mobile',

            'parent_user_id' => 'required',
            'parent_role_id' => 'required',
            'parent_package_id' => 'required',



        ]);

        if ($validator->fails()) {

            $error = $validator->errors()->first();

            $fos_users = User::where('roleId', Config::get('constants.FOS'))->orderBy('first_name')->get();

            return view('modules.user.createNewRetailerPm', compact('error', 'fos_users'));
        }

        $otp = '';

        $mpin = rand(1000, 9999);

        $password = rand(100000, 999999);

        $loggedOtp = rand(100000, 999999);

        $roleId = Config::get('constants.RETAILER');

        $username = $this->generateAutoUsername($roleId);
       
        $statusMsg = "";

        $success = null;

        $pg_options = '{"upi":{"mode": "UPI","status":1,"charge":0,"type": "%"},"rupay_card":{"mode": "RUPAY_CARD","status":0,"charge": 0,"type": "%"},"debit_card":{"mode": "DEBIT_CARD","status": 0,"charge": 1.50,"type": "%"},"credit_card":{"mode": "CREDIT_CARD","status": 0,"charge": 1.50,"type": "%"},"prepaid_card":{"mode": "PREPAID_CARD","status": 0,"charge": 1.50,"type": "%"},"corporate_card":{"mode": "CORPORATE_CARD","status": 0,"charge": 2.80,"type": "%"},"wallet":{"mode": "WALLET","status":0,"charge": 2.30,"type": "%"},"net_banking":{"mode": "NET_BANKING","status":0,"charge": 2.30,"type": "%"}}';

        $user = User::create([

            'first_name' => $request->get('first_name') . "" . $request->get('last_name'),

            'last_name' => '', //confirm

            'user_dob' => now(),

            'email' => $request->get('email_id'),

            'password' => Hash::make($password),

            'username' => $username,

            'mpin' => $mpin,

            'user_code' => '',

            'mobile' => $request->get('mobile_no'),

            'business_name' => $request->get('business_name'),

            'alternate_mob_no' => '',

            'pan_no' => '',

            'aadhar_no' => '',

            'gst_no' => '',

            'whatsapp_no' => '',

            'telegram_no' => '',

            'roleId' => $roleId,

            'min_amount_deposit' => 1000,

            'min_balance' => 200,

            'max_amount_deposit' => 10000,

            'min_amount_withdraw' => 100,

            'max_amount_withdraw' => 10000,

            'parent_role_id' => $request->parent_role_id ?? Config::get('constants.DISTRIBUTOR'),

            'parent_user_id' => $request->parent_user_id,

            'package_id' => $request->package_id ?? '3',

            'state_id' => '',

            'district_id' => '',

            'activated_status' => Config::get('constants.ACTIVE'),

            'address' => '',

            'fos_id' => $request->fos ?? null,

            'zip_code' => '',

            'store_name' => '',

            'store_category_id' => 0,

            'wallet_balance' => 0,

            'commission_id' => '',

            'pg_options' => $pg_options,

            'pg_status' => '0',

            'createdDtm' => now(),

            'logged_otp' => 0000,

            'createdBy' => 0,

        ]);

       

       

        if ($user) {

            $message = $this->prepareRegistrationMsg($mpin, $password, $username);

            if ($message) {

                $email = $request->get('email_id');

                $name = $request->get('name');

                $data = array(

                    'email' => $email,

                    'name' => $name,

                    'username' => $username,

                    'password' => $password,

                    'mpin' => $mpin

                );

                $send_email = Mail::send('mail.welcome', $data, function ($msg) use ($email, $name) {

                    $msg->to($email, $name);

                    $msg->subject('Welcome to PayMama');

                    $msg->from('hello@paymamaapp.in', 'PayMama - Business Made Easy');
                });

                $smsTemplateId = SmsTemplate::where('alias', Config::get('constants.SMS_TEMPLATE_ALIAS.USER_REGISTRATION.name'))->first();

                $this->sendSms($message, $request->get('mobile_no'), $smsTemplateId->template_id);

                $statusMsg = "Congratulations " . $name . ". Verification successful. Check Mobile for Login Credentials.";

                $success = 1;
                $fos_users = User::where('roleId', Config::get('constants.FOS'))->orderBy('first_name')->get();

                return view('modules.user.createNewRetailerPm', compact('success', 'fos_users'));
            }
        }
    }
}
