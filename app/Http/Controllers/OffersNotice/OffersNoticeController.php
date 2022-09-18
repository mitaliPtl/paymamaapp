<?php

namespace App\Http\Controllers\OffersNotice;
use App\Http\Controllers\Controller;
use App\OperatorSetting;
use App\OffersNotice;
use App\ServicesType;
use App\TransactionDetail;
use App\User;
use App\Role;
use Auth;
use Config;
use DB;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class OffersNoticeController extends Controller
{
    public function index(Request $request){
        
       
        $all_offers_notice = OffersNotice::leftJoin('tbl_files', 'tbl_notice.image','=','tbl_files.id')
                                            ->where('notice_isDeleted', Config::get('constants.NOT-DELETED'))
                                            ->orderBy('tbl_notice.notice_id', 'DESC')
                                            ->get();
        
        $offers = $all_offers_notice;
        $loggedInRole = Auth::user()->roleId;
        $serviceType = "OFFERS_NOTICE";
        $offernoticeListTH = $this->setTableHeader($loggedInRole, $serviceType);
        $all_offers_notice = $this->modifyOffersNoticeList($offernoticeListTH, $all_offers_notice);
        // print_r($all_offers_notice);
        // exit();
        $all_roles = Role::where('is_deleted',  Config::get('constants.NOT-DELETED'))
                            ->where('roleId',  '!=',Config::get('constants.ADMIN'))
                            ->get();
        $all_type = Config::get('constants.OFFERS_NOTICE_TYPE');

        // exit();
        // return view('modules.offersnotice.offersnotice');
        return view('modules.offersnotice.offersnotice', compact('all_offers_notice', 'offernoticeListTH', 'all_roles', 'all_type', 'offers'));
    }

     /**
     * Modify Offers Notice List
     */
    public function modifyOffersNoticeList($tableHeads, $reportList)
    {
        $result = [];
        if ($reportList) {
            foreach ($reportList as $repInd => $report) {
                $keyList = [];
                // $reportList[$repInd]['mobile'] = "";
                // $reportList[$repInd]['response'] = "";
                foreach ($tableHeads as $headInd => $head) {
                    // $keyList['notice_id'] = $report['notice_id'];

                    if (isset($report[$head['label']])) {
                        $label_val = $report[$head['label']];
                        
                        if ($head['label'] == "notice_visible") {
                            // $label_val = $this->getUserNameById($report[$head['label']]);
                           $label_val = json_decode($report[$head['label']], true);
                            // $label_val = Role::getNameById($report[$head['label']]);
                        }

                        if ($head['label'] == "notice_type") {
                            // $label_val = $this->getUserNameById($report[$head['label']]);
                            if ($report[$head['label']] == 1) {
                                $label_val = 'OFFER';
                                
                            }

                            if ($report[$head['label']] == 2) {
                                $label_val = 'NOTICE';
                                
                            }
                            
                            if ($report[$head['label']] == 3) {
                                $label_val = 'ALERT';
                                
                            }

                            
                        }

                        // if ($head['label'] == "user_id") {
                        //     // $label_val = $this->getUserNameById($report[$head['label']]);
                        //     $label_val = User::getStoreNameById($report[$head['label']]);
                        // }

                        // if ($head['label'] == "mobile") {
                        //     $label_val = $this->getUserMobileById($report['user_id']);
                        // }

                        // if ($head['label'] == "operator_id") {
                        //     $label_val = $this->getOperatorNameById($report[$head['label']]);
                        // }

                        // if ($head['label'] == "api_id") {
                        //     $label_val = $this->getApiSettingNameById($report[$head['label']]);
                        // }

                        // if ($head['label'] == "response") {
                        //     $label_val = $this->getResponseByOrdId($report['order_id']);
                        // }

                        // if ($head['label'] == "service_id") {
                            // $label_val = $this->getResponseByOrdId($report['order_id']);
                        //     $label_val = isset(ServicesType::where('service_id', $report[$head['label']])->pluck('service_name')[0]) ? ServicesType::where('service_id', $report[$head['label']])->pluck('service_name')[0] : '';
                        // }

                        // if ($head['label'] == "trans_date") {
                        //     $label_val = substr($report['trans_date'], 0, 8);
                        // }

                        $keyList[$head['label']] = $label_val;
                    } else {

                        $keyList[$head['label']] = "";
                    }
                }
                array_push($result, $keyList);
                $keyList = [];

            }
        }

        return $result;
    }


      /**
     * Get Table Header
     */
    public function setTableHeader($loggedInRole, $serviceType = null)
    {
        $offersnoticesTH = [];
        $strAppend = "";
        $strAppend = "_ADMIN_TD";
        if ($loggedInRole == Config::get('constants.ADMIN')) {

            $strAppend = "_ADMIN_TD";

        } else if ($loggedInRole == Config::get('constants.DISTRIBUTOR')) {

            $strAppend = "_DIS_TD";

        } else if ($loggedInRole == Config::get('constants.RETAILER')) {

            $strAppend = "_RT_TD";
        }

        if ($serviceType) {
            $tableConstHD = "constants." . $serviceType . $strAppend;
            $offersnoticesTH = Config::get($tableConstHD);
        }

        return $offersnoticesTH;
    }

    public function addOffersNotice(Request $request){
      
        // print_r($request->all());
        // exit();
            // $path='';
            // $imagePath='';

            // $request->validate([
            //     'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            //   ]);
      
            // //   $image = new Image;
      
            //   if ($request->file('file')) {
            //     $imagePath = $request->file('file');
            //     $imageName = $imagePath->getClientOriginalName();
      
            //     $path = $request->file('file')->storeAs('uploads/offersnotice', $imageName, 'public');
            //   }
            //   $path = '/storage/app/public/'.$path;
                
                $offersnotice_description = '';
                if(!empty($request->description) && $request->description ){
                    $offersnotice_description = $request->description;
                }


                $insert_offersnotice = OffersNotice:: create([

                                        'notice_title'=>$request->title,
                                        'notice_description'=>$request->description,
                                        'image'=>$request->uploaded_file_id,
                                        'notice_type'=>$request->offersnotice_type,
                                        'notice_visible'=>json_encode($request->offersnotice_role),
                                        'created_on'=>now(),
                                        ]);

                 
                if($insert_offersnotice){

                    return  back()->with("success", "Offer Added successfully!!"); 
        
                }else{
                    return  back()->with("error", " Offer Not Added !!"); 
        
                }
    }

    public function editOffersNotice(Request $request){
          
        $offersnotice_description = '';
        if(!empty($request->edit_description) && $request->edit_description ){
            $offersnotice_description = $request->edit_description;
        }

        
            if(!empty($request->uploaded_file_id) && $request->uploaded_file_id){
                $upadate_offersnotice = OffersNotice::
                where('notice_id', $request->edit_offersnotice_id)
                ->update([ 
                    'notice_title'=>$request->edit_title,
                    'notice_description'=>$offersnotice_description,
                    'image'=>$request->uploaded_file_id,
                    'notice_type'=>$request->edit_offersnotice_type,
                    'notice_visible'=>json_encode($request->edit_offersnotice_role),
                    'updated_on'=> now()
                    ]);  

            }else{
                $upadate_offersnotice = OffersNotice::
                where('notice_id', $request->edit_offersnotice_id)
                ->update([
                    'notice_title'=>$request->edit_title,
                    'notice_description'=>$offersnotice_description,
                    'notice_type'=>$request->edit_offersnotice_type,
                    'notice_visible'=>$request->edit_offersnotice_role,
                    'updated_on'=> now()
                    ]);  
            }
       
        if($upadate_offersnotice){

            return  back()->with("success", "Offer/Notice Updated successfully!!"); 

        }else{
            return  back()->with("error", " Offer/Notice Not Updated !!"); 

        } 
    } 
    
    public function deleteOffersNotice(Request $request){
       
        $delete_offersnotice = OffersNotice::
                        where('notice_id', $request->delete_offersnotice_id)
                        ->update([
                            'notice_isDeleted'=>Config::get('constants.DELETED'),
                            'updated_on'=> now()
                            ]);  
        if($delete_offersnotice){

            return  back()->with("success", "Offer/Notice Deleted successfully!!"); 

        }else{
            return  back()->with("error", " Offer/Notice Not Deleted !!"); 

        } 


    
    }


    public function getOffersNotice_RT_DT(Request $request){
       
        $offerType = $request->type;
        $get_id = 'constants.OFFERS_NOTICE_TYPE.'.$offerType;
       
        $all_offers_notice = OffersNotice::leftJoin('tbl_files', 'tbl_notice.image','=','tbl_files.id')
                                            ->where('notice_type', Config::get($get_id))
                                            ->where('notice_isDeleted', Config::get('constants.NOT-DELETED'))
                                            ->orderBy('tbl_notice.notice_id', 'DESC')
                                            ->get();
        $loggedInRole = Auth::user()->roleId;
        $all_offers_notice = $this->modified_RT_DT($all_offers_notice, $loggedInRole);
        
       
        // print_r($all_offers_notice);
        return view('modules.offersnotice.offersnotice_RT_DT', compact('all_offers_notice', 'offerType'));
    }

    public function modified_RT_DT($records, $role_id){
        $result = [];
        $index = 0;
        foreach ($records as $record_key => $record_value) {
           $row =  json_decode($record_value['notice_visible'], true);

           if (in_array($role_id, $row))
           {
                $result[$index] = $record_value;
                $index++;
           }
        }

        return $result;
    }

   

    
}