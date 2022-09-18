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

class APIOffersNoticeController extends Controller
{
    public function index(Request $request){
         $offerType = $request->type;
        if($offerType=="OFFER")
        {
          $get_id=1;
        }
        elseif($offerType=="NOTICE")
        {
          $get_id=2;
        }
        else
        {
           $get_id=3; 
        }
       
      
        $all_offers_notice = OffersNotice::leftJoin('tbl_files', 'tbl_notice.image','=','tbl_files.id')
                                            ->where('notice_type', $get_id)
                                            ->where('notice_isDeleted',0)
                                            ->orderBy('tbl_notice.notice_id', 'DESC')
                                            ->get();
                               
        $loggedInRole = $request->role_id;
        $all_offers_notice = $this->modified_RT_DT($all_offers_notice, $loggedInRole);
        $offersnoticeTH = Config::get('constants.OFFERS_NOTICE_RT_DT_TD');
        $all_offers_notice = $this->modifyOffersNotice($offersnoticeTH, $all_offers_notice);
         
        if($all_offers_notice){
            $statusMsg = "Success!!";
            return $this->sendSuccess($all_offers_notice, $statusMsg);
        }else{
            return $this->sendError("No Record Found !!");
        }

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


    public function modifyOffersNotice($tableHeads, $reportList)
    {
        $result = [];
        if ($reportList) {
            foreach ($reportList as $repInd => $report) {
                $keyList = [];
                $reportList[$repInd]['mobile'] = "";
                $reportList[$repInd]['response'] = "";
                foreach ($tableHeads as $headInd => $head) {
                    // $keyList['id'] = $report['id'];

                    if (isset($report[$head['label']])) {
                        $label_val = $report[$head['label']];

                        if ($head['label'] == "notice_type") {
                           if($report[$head['label']] == Config::get('constants.OFFERS_NOTICE_TYPE.OFFER')){
                                $label_val ="OFFER";
                           }elseif($report[$head['label']] == Config::get('constants.OFFERS_NOTICE_TYPE.NOTICE')){
                                $label_val ="NOTICE";
                           }
                            // $label_val = User::getStoreNameById($report[$head['label']]);
                        }

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
                        //     // $label_val = $this->getResponseByOrdId($report['order_id']);
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



}