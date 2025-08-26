<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class PostController extends Controller
{
    use ApiResponse;
    
    public function __invoke()
    {

        return $this->ok("Desde invoke");
    }
}
