<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;

class AccessDeniedException extends Exception
{
    //
    public function __construct($message,$code = 0,private $item = null,)
    {
        parent::__construct($message,$code);
    }
    
    /**
     * Report the exception.
     *
     * @return void
     */
    public function report()
    {
        Log::alert($this->getMessage(),[
            'item' => $this->item,
            'authId' => auth()->id()
        ]);
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
            'name' => $this->item?->name ?? $this->item?->title,
            'description' => $this->item?->description,
        ], 422);
    }
}
