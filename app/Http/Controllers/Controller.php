<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function successMessage($data)
    {
        return response()->json(['status' => 'success','message' => $data]);
    }

    public function errorMessage()
    {
        return response()->json(['status' => 'error','message' => 'Something Went Wrong!']);
    }
}
