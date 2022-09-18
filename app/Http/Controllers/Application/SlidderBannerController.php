<?php

namespace App\Http\Controllers\Application;

use App\File;
use App\Http\Controllers\Controller;
use App\Role;
use App\SlidderBannerDetail;
use Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SlidderBannerController extends Controller
{
    /**
     * Page View
     */
    public function index()
    {
        $slidderBanners = SlidderBannerDetail::orderBy('created_at','DESC')->orderBy('updated_at','DESC')->get();
        $slidderBanners = $this->mofifySlidderBnrList($slidderBanners);

        $allRoles = Role::where('is_deleted', Config::get('constants.NOT-DELETED'))->get();
        return view('modules.application.slidder_banner', compact('slidderBanners', 'allRoles'));
    }

    /**
     * Modify Slidder Banner List Data
     */
    public function mofifySlidderBnrList($list)
    {
        $response = [];

        if (isset($list) && $list) {
            foreach ($list as $key => $banner) {
                $filePathList = File::whereIn('id', $banner->image_file_ids)->pluck('file_path');
                $banner['file_path_list'] = $filePathList;
            }

            $response = $list;
        }

        return $response;
    }

    /**
     * Store Sidebar Deatails Banner
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'role_id' => 'required',
            'platform' => 'required',
            'location' => 'required',
            'image_file_ids' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->with('error', $validator->messages()->first());
        }

        $action_message = "";
        if ($request->get('slidder_banner_id') && $request->slidder_banner_id) {

            $resultById = SlidderBannerDetail::find((int) $request->get('slidder_banner_id'));

            $resultById->role_id = $request->get('role_id');
            $resultById->platform = $request->get('platform');
            $resultById->location = $request->get('location');
            $resultById->image_file_ids = $request->image_file_ids ? explode(',', $request->image_file_ids ) : [];

            $response = $resultById->save();

            $action_message = "Slidder Banner Updated";
        } else {
            $response = SlidderBannerDetail::create([
                'role_id' => $request->get('role_id'),
                'platform' => $request->get('platform'),
                'location' => $request->get('location'),
                'image_file_ids' => $request->image_file_ids ? explode(',', $request->image_file_ids ) : [],
            ]);
            $action_message = "Slidder Banner Added";
        }

        if ($response) {
            return back()->with('success', $action_message);
        } else {
            return back()->with('error', "Failure");
        }
    }

    /**
     * Get edit data by Id
     */
    public function edit(Request $request)
    {
        $resultById = SlidderBannerDetail::find((int) $request->get('slidder_banner_id'));

        if (isset($resultById) && $resultById) {
            $filePathList = File::whereIn('id', $resultById->image_file_ids)->pluck('file_path');
            $resultById['file_path_list'] = $filePathList;
        }

        return $resultById;
    }

    /**
     * Delete Slidder Banner by Id
     */
    public function deleteSlidderBanner(Request $request)
    {
        $response = null;

        $resultById = SlidderBannerDetail::find((int) $request->get('id'));

        $response = $resultById->delete();

        $action_message = "Deleted Successfully!!";

        if ($response) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get Slidder Banner Details API
     */
    public function getSlidderBannerDtlsApi(Request $request){
        $slidderBanners = SlidderBannerDetail::select(['platform','location','location','image_file_ids'])
        ->where('role_id',$request->role_id)
        ->orderBy('created_at','DESC')
        ->orderBy('updated_at','DESC')->get();

        if(isset($slidderBanners) && count($slidderBanners) > 0){

            foreach ($slidderBanners as $key => $value) {
                $filePathList = File::whereIn('id', $value->image_file_ids)->pluck('file_path');
                $value['file_path_list'] = $filePathList;
            }
            
            $slidderBanners = $slidderBanners->makeHidden([
                'image_file_ids'
            ]);

            $msg = "Success!!";
            return $this->sendSuccess($slidderBanners,$msg);
        }else{
            return $this->sendError("Data not found!!");
        }
    }

    public function getSlidderBannerDtlsWithLinkApi(Request $request){
        $slidderBanners = SlidderBannerDetail::select('platform','location','redirect_link','image_file_ids')
                        ->where('role_id',$request->role_id);
        if (isset($request->type) && $request->type) {
            $slidderBanners = $slidderBanners->where('platform',$request->type);
        }

        $slidderBanners = $slidderBanners->orderBy('created_at','DESC')
                        ->orderBy('updated_at','DESC')
                        ->get();
        // print_r($slidderBanners);
        $records = [];
       
        if(isset($slidderBanners) && count($slidderBanners) > 0){
           
            foreach ($slidderBanners as $key => $value) {
                $records[$key]['location'] = $value->location;
                $file_id_arr = (isset($value->image_file_ids)>0 && $value->image_file_ids )? $value->image_file_ids : [] ;

                $link_arr = (isset($value->redirect_link)>0 && $value->redirect_link )? json_decode($value->redirect_link, true) : [] ;
                if (count($file_id_arr)>0  ) {
                   foreach ($file_id_arr as $file_key => $file_value) {
                        $filePathList = File::where('id', $file_value)->get()->first();
                        $records[$key]['data'][$file_key]['file_path'] = $filePathList->file_path;
                        $records[$key]['data'][$file_key]['url'] =  (count($link_arr)>0)? (isset($link_arr[$file_key]) && $link_arr[$file_key])? $link_arr[$file_key]: '' : '';
                   }

                }
                
                // $value['file_path'] = $filePathList;
                // $records[$key]['file_path'] = $filePathList;
                // $records[$key]['url'] =  
                
            }
            
            $slidderBanners = $slidderBanners->makeHidden([
                'image_file_ids'
            ]);

            $msg = "Success!!";
            return $this->sendSuccess($records,$msg);
        }else{
            return $this->sendError("Data not found!!");
        }
    }

    public function getSlidderBannerWeb(Request $request){

    }

    public function redirectBanner(Request $request){
        // print_r($request->all());
        if(isset($request->redirect_banner_id) && $request->redirect_banner_id){
            $bnnr_slider = SlidderBannerDetail::where('id', $request->redirect_banner_id)
                            ->update(['redirect_link'=> json_encode($request->redirect_link), 'updated_at'=>now() ]);
            print_r($bnnr_slider);
            if ($bnnr_slider) {
                return back()->with('success', 'Slider Link Added Successfully!!');
            } 
        }
        return back()->with('error', "Failure");
        
    }
}
