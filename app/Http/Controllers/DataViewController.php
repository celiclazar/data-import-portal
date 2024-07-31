<?php

namespace App\Http\Controllers;

use App\Exports\DataExport;
use App\Exports\FilteredExport;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Inventory;
use App\Models\ImportedFile;
use Maatwebsite\Excel\Facades\Excel;

class DataViewController extends Controller
{
    public function view($id)
    {
        $importedFile = ImportedFile::findOrFail($id);
        $config = config('imports.' . $importedFile->import_type);

        $modelClass = $this->getModelClass($importedFile->import_type);
        $data = $modelClass::where('imported_file_id', $id)->paginate(10);

        return view('data.view', compact('data', 'config', 'importedFile'));
    }

    public function delete($id, $type)
    {
        $config = config('imports.' . $type);

        if (!auth()->user()->can($config['permission_required'])) {
            return back()->with('error', 'You do not have permission to delete this data.');
        }

        $modelClass = $this->getModelClass($type);
        $data = $modelClass::findOrFail($id);
        $data->delete();

        return back()->with('success', 'Data deleted successfully.');
    }

    public function export(Request $request, $id)
    {
        $filter = $request->query('filter', '');
        $importedFile = ImportedFile::findOrFail($id);
        $config = config('imports.' . $importedFile->import_type);

        $modelClass = $this->getModelClass($importedFile->import_type);
        $query = $modelClass::where('imported_file_id', $importedFile->id);

        if (!empty($filter)) {
            $query->where(function($q) use ($filter, $config) {
                foreach ($config['files'][array_key_first($config['files'])]['headers_to_db'] as $dbField) {
                    $q->orWhere($dbField, 'LIKE', '%' . $filter . '%');
                }
            });
        }

        $data = $query->get();

        return Excel::download(new FilteredExport($data, $config), 'filtered_data.xlsx');
    }

    private function getModelClass($importType)
    {
        switch ($importType) {
            case 'orders':
                return Order::class;
            case 'customer_data':
                return Customer::class;
            case 'inventory_data':
                return Inventory::class;
            default:
                throw new \Exception('Invalid import type');
        }
    }

}
