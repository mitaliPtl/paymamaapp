<?php

namespace App\Http\Controllers\Complaint;
use App\Template;
use App\Role;
use App\ApiLogDetail;
use App\ApiSetting;
use App\City;
use App\Http\Controllers\Controller;
use App\OperatorSetting;
use App\ServicesType;
use App\SmsTemplate;
use App\State;
use App\StoreCategory;
use App\TransactionDetail;
use App\User;
use App\WalletTransactionDetail;
use Auth;
use Config;
use DB;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use PDF;
use Illuminate\Support\Facades\Validator;


class TemplateController extends Controller
{
    public function index(Request $request){
            
        
        $templates = Template::leftJoin('tbl_services_type', 'tbl_template.service_id', '=', 'tbl_services_type.service_id')
                        ->leftJoin('tbl_roles', 'tbl_template.role_id', '=', 'tbl_roles.roleId')
                        ->where('tbl_template.isDeleted', Config::get('constants.NOT-DELETED'));
                        
        if (isset($request->selected_service) && $request->selected_service) {
            $templates = $templates
                            ->where('tbl_template.service_id', $request->selected_service);

        }

        $templates = $templates->get();
        $services = ServicesType::where('tbl_services_type.is_deleted', Config::get('constants.NOT-DELETED'))
                            ->get();  
        $roles = Role::where('tbl_roles.is_deleted', Config::get('constants.NOT-DELETED'))
                            ->get();        
        return view('modules.complaint.template', compact('templates', 'services', 'roles'));

        
    }

    public function addTemplate(Request $request){
        
        $add_template = Template::create([
                                    'service_id' => $request->template_service,
                                    'role_id'=>$request->template_role,
                                    'template'=>$request->template_text,
                                    'timing'=>$request->default_time,
                                    'isDeleted'=>Config::get('constants.NOT-DELETED'),
                                    'created_on' => now()
                                ]);
        if($add_template){

            return  back()->with("success", "Template Added successfully!!"); 

        }else{
            return  back()->with("error", " Template Not Added !!"); 

        }

    }

    public function editTemplate(Request $request){

        $update_template =Template::
                        where('template_id', $request->edit_temp_id)
                        ->update([ 'service_id'=> $request->edit_temp_service,
                        'role_id'=>$request->edit_temp_role,
                        'template'=>$request->edit_temp_text,
                        'timing'=>$request->edit_default_time,
                        'template_updated'=>  now()]); 

        if($update_template){

            return  back()->with("success", "Template Updated Successfully!!"); 

        }else{
            return  back()->with("error", " Template Not Updated  !!"); 

        }

    }

    public function deleteTemplate(Request $request){
        $delate_template =Template::
                        where('template_id', $request->delete_temp_id)
                        ->update([ 'isDeleted'=> Config::get('constants.DELETED'),
                        'template_updated'=>  now()]); 

        if($delate_template){

            return  back()->with("success", "Template Updated Successfully!!"); 

        }else{
            return  back()->with("error", " Template Not Updated  !!"); 

        }
                
    }


}
