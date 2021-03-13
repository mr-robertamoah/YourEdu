<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;

class CommissionException extends Exception
{
    //
    public $data;

    public function __construct($message,$code = 0, $data = null) 
    {
        $this->data = $data;

        parent::__construct($message,$code);
    }
    
    /**
    * Report the exception.
    *
    * @return void
    */
   public function report()
   {
        Log::alert($this->getMessage(), [
           'data' => $this->data]);
   }

   /**
    * Render the exception into an HTTP response.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
   public function render($request)
   {
       return response()->json([
           'message' => $this->getMessage(),
       ], 422);
   }
}
