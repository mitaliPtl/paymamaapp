<?php

namespace App\Http\Controllers\Other;

use App\Http\Controllers\Controller;
use App\SmsTemplate;
use Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SmsController extends Controller
{
    /**
     * SMS Template View
     */
    public function index()
    {
        $smsTemplateAlias = Config::get('constants.SMS_TEMPLATE_ALIAS');
        $smsTemplates = SmsTemplate::where('is_deleted', Config::get('constants.NOT-DELETED'))->get();
        $smsTemplates = $this->modifySmsTemplateList($smsTemplates);
        return view('modules.other.sms_template', compact('smsTemplates', 'smsTemplateAlias'));
    }

    /**
     * Modify SMS template list response
     */
    public function modifySmsTemplateList($list)
    {
        $result = [];
        if (isset($list)) {

            foreach ($list as $i => $template) {
                if ($template->alias == Config::get('constants.SMS_TEMPLATE_ALIAS.USER_REGISTRATION')) {
                    $template['allowed_tags'] = "USER_REGISTRATION";
                } else if ($template->alias == Config::get('constants.SMS_TEMPLATE_ALIAS.KYC_APPROVAL')) {
                    $template['allowed_tags'] = "KYC_APPROVAL";
                } else if ($template->alias == Config::get('constants.SMS_TEMPLATE_ALIAS.BALANCE_ADDED')) {
                    $template['allowed_tags'] = "BALANCE_ADDED";
                }
            }

            $result = $list;
        }

        return $result;
    }

    /**
     *
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sms_name' => 'required|string|max:255',
            'alias' => 'required|string|max:255',
            'template' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $action_message = "";
        if ($request->get('id')) {

            $resultById = SmsTemplate::find((int) $request->get('id'));

            $resultById->sms_name = $request->get('sms_name');
            $resultById->alias = $request->get('alias');
            $resultById->template = $request->get('template');

            $response = $resultById->save();

            $action_message = "SMS Template Updated";
        } else {
            $response = SmsTemplate::create([
                'sms_name' => $request->get('sms_name'),
                'alias' => $request->get('alias'),
                'template' => $request->get('template'),
            ]);
            $action_message = "SMS Template Added";
        }

        if ($response) {
            return back()->with('success', $action_message);
        }
    }

    public function edit(Request $request)
    {
        $resultById = SmsTemplate::where('id', $request->get('id'))->first();
        return $resultById;
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
            $resultById = SmsTemplate::find((int) $id);
            $resultById->activated_status = $setStatus;
            $response = $resultById->save();
            if ($response) {
                $result = true;
            }
        }
        return $result;
    }

    /**
     * Change Service Type delete status
     */
    public function changeDeleteStatus(Request $request)
    {
        $id = $request->get('id');

        $result = false;
        if ($id) {
            $resultById = SmsTemplate::find((int) $id);
            $resultById->is_deleted = Config::get('constants.DELETED');
            $response = $resultById->save();
            if ($response) {
                $result = true;
            }
        }
        return $result;
    }
}
