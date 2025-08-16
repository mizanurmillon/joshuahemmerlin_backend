<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ShopwareServices;
use Illuminate\Http\Request;

class ShopwareController extends Controller
{
    function testConnection()
    {
            $connection = new ShopwareServices();
        return $connection->testConnection();

    }
}
