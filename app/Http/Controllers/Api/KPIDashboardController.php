<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\KPIDashboard;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class KPIDashboardController extends Controller
{
    use ApiResponse;

    function index(Request $request)
    {
        $query = KPIDashboard::query();

        if ($request->has('data_sources_id')) {
            $query->where('data_sources_id', $request->data_sources_id);
        }

        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $data = $query->latest()->get();

        if ($data->isEmpty()) {
            return $this->error([], 'No KPI data found', 404);
        }

        return $this->success($data, 'KPI data retrieved successfully', 200);
    }

    function store(Request $request)
    {
        //validator
        $validator = validator($request->all(), [
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10480',
            'data_sources_id' => 'required|integer|exists:data_sources,id',
            'data' => 'required|string',
        ]);
        if ($validator->fails()) {
            return $this->error($validator->errors(), $validator->errors()->first(), 422);
        }


        try {
            //image upload
            if ($request->hasFile('image')) {
                $path = uploadImage($request->image, 'kpi-dashboard');
            }

            $data = KPIDashboard::create(
                [
                    'name' => $request->name,
                    'image' => $path ?? null,
                    'data_sources_id' => $request->data_sources_id,
                    'data' => $request->data,
                ]
            );
            $data->load('dataSource');

            return $this->success($data, 'KPI data stored successfully', 201);

        }catch (\Exception $e){
            return $this->error($e->getMessage(), 'Failed to store KPI data', 500);
        }


    }

    function update(Request $request)
    {
        //validator
        $validator = validator($request->all(), [
            'id' => 'required|integer|exists:k_p_i_dashboards,id',
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10480',
            'data_sources_id' => 'required|integer|exists:data_sources,id',
            'data' => 'required|string',
        ]);
        if ($validator->fails()) {
            return $this->error($validator->errors(), $validator->errors()->first(), 422);
        }
        try {
            $data = KPIDashboard::find($request->id);
            if (!$data) {
                return $this->error([], 'KPI data not found', 404);
            }
            //image upload
            if ($request->hasFile('image')) {
                $path = uploadImage($request->image, 'kpi-dashboard');
                //delete old image
                if ($data->image) {
                    deleteImage($data->image);
                }
                $data->update(['image' => $path]);
            }

            $data->update([
                'name' => $request->name,
                'data_sources_id' => $request->data_sources_id,
                'data' => $request->data,
            ]);
            $data->load('dataSource');

            return $this->success($data, 'KPI data updated successfully', 200);
        }catch (\Exception $e){
            return $this->error($e->getMessage(), 'Failed to update KPI data', 500);
        }


    }

    function edit($id)
    {
        $data = KPIDashboard::find($id);
        if (!$data) {
            return $this->error([], 'KPI Dashboard not found', 404);
        }
        $data->load('dataSource');
        return $this->success($data, 'KPI Dashboard retrieved successfully', 200);
    }

    function destroy($id)
    {
        $data = KPIDashboard::find($id);
        if (!$data) {
            return $this->error([], 'KPI Dashboard not found', 404);
        }
        //delete image
        if ($data->image) {
            deleteImage($data->image);
        }
        $data->delete();
        return $this->success([], 'KPI Dashboard deleted successfully', 200);
    }

}
