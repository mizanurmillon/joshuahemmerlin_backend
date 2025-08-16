<?php

namespace App\Http\Controllers\Api\Web;

use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DataSourceManagement;
use Illuminate\Support\Facades\Validator;

class DataSourceManagementController extends Controller
{
    use ApiResponse;
    
    public function dataSourceManagement(Request $request)
    {
        $validateData = Validator::make($request->all(), [
            'source_type' => 'required|string',
            'source_name' => 'required|string',
            'api_endpoint' => 'nullable|url',
            'auth_key' => 'nullable|string',
            'value' => 'required|string',
        ]);

        if ($validateData->fails()) {
            return $this->error($validateData->errors(), 422);
        }

        $user = auth()->user();

        
        $data = DataSourceManagement::create ([
            'user_id' => $user->id,
            'source_type' => $request->source_type,
            'source_name' => $request->source_name,
            'api_endpoint' => $request->api_endpoint,
            'auth_key' => $request->auth_key,
            'value' => $request->value,
        ]);

        return $this->success($data,'Data source added successfully', 200);
    }

    public function getDataSources(Request $request)
    {

        $user = auth()->user();

        $query = DataSourceManagement::where('user_id', $user->id);

        if($request->has('source_name')) {
            $query->where('source_name', $request->source_name);
        }

        if($request->has('status')) {
            $query->where('status', $request->status);
        }

        $data = $query->get();

        if($data->isEmpty()) {
            return $this->error([], 'Data sources not found.', 200);
        }

        return $this->success($data,'Data sources', 200);

    }

    public function getDataSource($id)
    {
        $user = auth()->user();

        $data = DataSourceManagement::where('user_id', $user->id)->where('id', $id)->first();

        if (!$data) {
            return $this->error([], 'Data source not found.', 200);
        }

        return $this->success($data,'Data source', 200);
    }

    public function updateDataSource(Request $request, $id)
    {
        $validateData = Validator::make($request->all(), [
            'source_type' => 'required|string',
            'source_name' => 'required|string',
            'api_endpoint' => 'nullable|url',
            'auth_key' => 'nullable|string',
            'value' => 'required|string',
        ]);

        if ($validateData->fails()) {
            return $this->error($validateData->errors(), 422);
        }

        $user = auth()->user();

        $data = DataSourceManagement::where('user_id', $user->id)->where('id', $id)->first();

        if (!$data) {
            return $this->error([], 'Data source not found.', 200);
        }

        $data->update([
            'source_type' => $request->source_type,
            'source_name' => $request->source_name,
            'api_endpoint' => $request->api_endpoint,
            'auth_key' => $request->auth_key,
            'value' => $request->value,
        ]);

        return $this->success($data,'Data source updated successfully', 200);
    }

    public function deleteDataSource($id)
    {
        $user = auth()->user();

        $data = DataSourceManagement::where('user_id', $user->id)->where('id', $id)->first();

        if (!$data) {
            return $this->error([], 'Data source not found.', 200);
        }

        $data->delete();

        return $this->success([], 'Data source deleted successfully', 200);
    }
}
