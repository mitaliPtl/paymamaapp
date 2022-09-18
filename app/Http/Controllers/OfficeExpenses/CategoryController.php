<?php

namespace App\Http\Controllers\OfficeExpenses;
use App\Category;
use App\Role;
use App\ApiLogDetail;
use App\ApiSetting;

use App\Http\Controllers\Controller;
use App\OperatorSetting;
use App\ServicesType;



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


class CategoryController extends Controller
{
    public function index(Request $request){

        $categories = Category::where('category_is_deleted', Config::get('constants.NOT-DELETED'))
                                ->get();
        return view('modules.office_expenses.category', compact('categories'));
     
    }

    public function addCategoryOfficeExpenses(Request $request){

      print_r($request->all());

        $insert_category = Category::create([
                                            'category'=> $request->category_text,
                                            'category_created_on'=> now()
                                        ]);
        if($insert_category){

            return  back()->with("success", "Category Added successfully!!"); 

        }else{
            return  back()->with("error", " Category Not Added !!"); 

        }
    }

    public function editCategoryOfficeExpenses(Request $request){
            



            $update_category = Category::where('category_id', $request->edit_category_id)
                                        ->where('category_is_deleted', Config::get('constants.NOT-DELETED'))
                                        ->update([
                                                    'category'=> $request->edit_category_text,
                                                    'category_updated_on'=> now()
                                                ]);
            if($update_category){

                return  back()->with("success", "Category Updated successfully!!"); 

            }else{
                return  back()->with("error", " Category Not Updated !!"); 

            }

    }

    public function deleteCategoryOfficeExpenses(Request $request){
       print_r($request->all());
        
        $delete_category = Category::where('category_id', $request->delete_category_id)
                                    ->update([
                                                'category_is_deleted'=> Config::get('constants.DELETED'),
                                                'category_updated_on'=> now()
                                            ]);
        if($delete_category){

            return  back()->with("success", "Category Deleted successfully!!"); 

        }else{
            return  back()->with("error", " Category Not Deleted !!"); 

        }






    }
}