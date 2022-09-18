<?php

namespace App\Http\Controllers\Other;

use App\File;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FileUploadController extends Controller
{
    /**
     * Upload File
     */
    public function fileUpload(Request $req)
    {
        $req->validate([
            'file' => 'required|mimes:png,jpg,jpeg,csv,txt,xlx,xls,pdf|max:2048',
        ]);

        $fileModel = new File;

        if ($req->file()) {
            $fileName = time() . '_' . $req->file->getClientOriginalName();
           // $filePath = $req->file('file')->storeAs('uploads', $fileName, 'public');
          $filePath=$req->file->move(public_path().'/storage/uploads', $fileName);
           //  $filePath = $req->file('file')->storeAs('public/storage/uploads', $fileName);
            $fileModel->name = time() . '_' . $req->file->getClientOriginalName();
            // $fileModel->file_path = '/storage/' . $filePath;
           // $fileModel->file_path = '/storage/app/public/' . $filePath;
            $fileModel->file_path = '/storage/uploads/'.$fileName;
            $response = $fileModel->save();
            if ($response) {
                return response()->json($fileModel);
            }

        }
    }

    /**
     * Upload File thorugh API
     */
     public function fileUploadByApi(Request $req)
     {  
        
 
         $fileModel = new File;
 
         if ($req->file()) {
             $fileName = time() . '_' . $req->file->getClientOriginalName();
           // $filePath = $req->file('file')->storeAs('uploads', $fileName, 'public');
          $filePath=$req->file->move(public_path().'/storage/uploads', $fileName);
           //  $filePath = $req->file('file')->storeAs('public/storage/uploads', $fileName);
            $fileModel->name = time() . '_' . $req->file->getClientOriginalName();
            // $fileModel->file_path = '/storage/' . $filePath;
           // $fileModel->file_path = '/storage/app/public/' . $filePath;
            $fileModel->file_path = '/storage/uploads/'.$fileName;
             $response = $fileModel->save();
             if ($response) {
                 $success['file'] = $fileModel;
                 $statusMsg = "File uploaded successfully!!";
                return $this->sendSuccess($success, $statusMsg);
             }else{
                return $this->sendError('Failure!!',$response);
             }
 
         }
     }
}
