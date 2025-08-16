<?php

namespace App\Http\Controllers\Api;

use App\Models\DataSource;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DataSourcesController extends Controller
{
    use ApiResponse;
    
    public function getDataSources()
    {
        $data = DataSource::all();
        
        if($data->isEmpty()) {
            return $this->error([], 'Data sources not found.', 200);
        }
        
        return $this->success($data,'Data sources', 200);
    }
}
