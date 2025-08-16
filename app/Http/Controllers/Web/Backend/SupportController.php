<?php
namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use App\Models\SupportForm;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SupportController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = SupportForm::latest()->get();
            if (! empty($request->input('search.value'))) {
                $searchTerm = $request->input('search.value');
                $data->where('name', 'LIKE', "%$searchTerm%");
            }
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('message', function ($data) {
                    $message       = $data->message;
                    $short_message = $data->message;
                    return '<p>' . $short_message . '</p>';
                })
                ->addColumn('status', function ($data) {
                    if ($data->status == 'pending') {
                        return '<span class="badge badge-danger">Pending</span>';
                    } else {
                        return '<span class="badge badge-success">solved</span>';
                    }
                })
                ->addColumn('action', function ($data) {
                    $disabled = $data->status == "solved" ? 'disabled' : '';
                    return '<div class="text-center"><div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                            <button href="#" onclick="showConfirm(' . $data->id . ')" type="button" class="text-white btn btn-primary" title="Edit" ' . $disabled . '>
                                <span>Solved</span>
                            </button>
                            <a href="#" onclick="showDeleteConfirm(' . $data->id . ')" type="button" class="text-white btn btn-danger" title="Delete">
                              <i class="bi bi-trash"></i>
                            </a>
                            </div></div>';
                })
                ->rawColumns(['message', 'action', 'status'])
                ->make(true);
        }
        return view('backend.layouts.support.index');
    }

    public function destroy($id)
    {

        $data = SupportForm::find($id);

        $data->delete();

        return response()->json([
            't-success' => true,
            'message'   => 'Deleted successfully.',
        ]);
    }

    public function sloved(Request $request)
    {
        $data         = SupportForm::find($request->id);
        $data->status = 'solved';
        $data->save();
        return response()->json([
            'success' => true,
            'message' => 'This support has been solved.',
        ]);
    }
}
