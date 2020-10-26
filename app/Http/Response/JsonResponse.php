<?php
namespace App\Http\Response;

trait JsonResponse{
        public function JsonResponse($code,$msg,$data=[]){
                $data = [
                    'code'=>$code,
                    'msg'=>$msg,
                    'data'=>$data,
                ];
             return response()->json($data);die;
        }



}





