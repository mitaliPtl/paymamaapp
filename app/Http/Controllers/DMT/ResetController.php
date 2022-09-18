<?php
namespace App\Http\Controllers\DMT;


use App\DMTSender;

class ResetController
{
  public function resetlimit()
    {
            $requestBody =  [
                          "available_limit"=>200000,
                        ];
            return $sender_update=DMTSender::where('id','>=',0)->update($requestBody);
            
    }
}

?>