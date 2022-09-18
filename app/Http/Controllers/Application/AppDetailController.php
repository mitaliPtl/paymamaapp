<?php

namespace App\Http\Controllers\Application;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ApplicationDetail;

class AppDetailController extends Controller
{
    /**
     * Application Detail API
     */
    public function appDtlApi(){
        $result = [];

        $appDetail = ApplicationDetail::select(['name','alias','value'])->get(); 

        if (isset($appDetail) && count($appDetail) > 0) {

            foreach ($appDetail as $key => $dtl) {
                $result[$dtl->alias] = $dtl->value;
            }

            $statusMsg = "Success!!";
            return $this->sendSuccess($result, $statusMsg);
        } else {
            return $this->sendError("No records found!!");
        }
    }

    public function appDtlApi_new(){
        $result = [];

        $appDetail = ApplicationDetail::select(['name','alias','value'])->get(); 

        if (isset($appDetail) && count($appDetail) > 0) {

            foreach ($appDetail as $key => $dtl) {
                $result[$dtl->alias]['name'] = $dtl->name;
                $result[$dtl->alias]['value'] = $dtl->value;
            }
            
            $statusMsg = "Success!!";
            return $this->sendSuccess($result, $statusMsg);
        } else {
            return $this->sendError("No records found!!");
        }
    }
}
