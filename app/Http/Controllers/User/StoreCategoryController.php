<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\StoreCategory;
use Config;
use Illuminate\Support\Facades\Validator;

class StoreCategoryController extends Controller
{
    /**
     * Store Category View
     */
    public function index(){
        $storeCategories = StoreCategory::where('is_deleted', Config::get('constants.NOT-DELETED'))->orderBy('updated_on', 'DESC')->get();
        return view('modules.user.store_category',compact('storeCategories'));
    }

    /**
     * Store Store Category here
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'store_category_name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $action_message = "";
        if ($request->get('id')) {

            $resultById = StoreCategory::find((int) $request->get('id'));

            $resultById->store_category_name = $request->get('store_category_name');

            $response = $resultById->save();

            $action_message = "Store Category Updated";
        } else {
            $response = StoreCategory::create([
                'store_category_name' => $request->get('store_category_name'),
            ]);
            $action_message = "Store Category Added";
        }

        if ($response) {
            return redirect('/store_category')->with('success', $action_message);
        }else{
            return redirect('/store_category')->with('error', "Failure!!");
        }
    }

    public function edit(Request $request)
    {
        $resultById = StoreCategory::where('id', $request->get('id'))->first();
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
            $resultById = StoreCategory::find((int) $id);
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
            $resultById = StoreCategory::find((int) $id);
            $resultById->is_deleted = Config::get('constants.DELETED');
            $response = $resultById->save();
            if ($response) {
                $result = true;
            }
        }
        return $result;
    }
}
