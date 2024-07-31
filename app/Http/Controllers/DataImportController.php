<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Inventory;
use App\Models\Supplier;
use App\Models\ImportedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Imports\Csv;
use Maatwebsite\Excel\Imports\Excel as ExcelImport;
use Illuminate\Support\Collection;


class DataImportController extends Controller
{
    public function index()
    {
        $config = config('imports');
        return view('data-import.index', compact('config'));
    }

    public function import(Request $request)
    {
        $importType = $request->input('importType');
        $config = config('imports');

        if (!array_key_exists($importType, $config)) {
            return back()->with('error', 'Invalid import type.');
        }

        $importConfig = $config[$importType];

        if (!$request->user()->can($importConfig['permission_required'])) {
            return back()->with('error', 'You do not have permission to import this data.');
        }

        $files = [];
        foreach ($importConfig['files'] as $fileKey => $fileConfig) {
            if ($request->hasFile($fileKey)) {
                $file = $request->file($fileKey);

                $validator = Validator::make(
                    [$fileKey => $file],
                    [$fileKey => 'file|mimes:csv,xlsx']
                );

                if ($validator->fails()) {
                    return back()->with('error', 'Invalid file type for ' . $fileConfig['label']);
                }

                $files[$fileKey] = $file;
            } else {
                return back()->with('error', 'File for ' . $fileConfig['label'] . ' is required.');
            }
        }

        DB::beginTransaction();

        try {

            $importedFile = new ImportedFile();
            $importedFile->filename = $this->generateFilename($files);
            $importedFile->import_type = $importType;
            $importedFile->save();

            foreach ($files as $fileKey => $file) {
                if ($file->getClientOriginalExtension() === 'csv') {
                    $this->importCsv($file, $importConfig['files'][$fileKey]['headers_to_db'], $importType, $importedFile->id);
                } else {
                    $this->importXlsx($file, $importConfig['files'][$fileKey]['headers_to_db'], $importType, $importedFile->id);
                }
            }


            DB::commit();
            return back()->with('success', 'Data imported successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error importing data: ' . $e->getMessage());
        }
    }

    private function importCsv($file, $headersToDb, $importType, $importedFileId)
    {
        $filePath = $file->getRealPath();
        $fileHandle = fopen($filePath, 'r');

        $headers = fgetcsv($fileHandle);

        $missingHeaders = array_diff(array_keys($headersToDb), $headers);
        if (!empty($missingHeaders)) {
            throw new \Exception('Missing headers: ' . implode(', ', $missingHeaders));
        }

        while (($row = fgetcsv($fileHandle)) !== false) {
            $data = array_combine($headers, $row);
            $mappedData = [];
            foreach ($headersToDb as $csvHeader => $dbField) {
                $mappedData[$dbField] = $data[$csvHeader];
            }
            $mappedData['imported_file_id'] = $importedFileId;
            $this->saveData($mappedData, $importType);
        }

        fclose($fileHandle);
    }

    private function importXlsx($file, $headersToDb, $importType, $importedFileId)
    {
        $filePath = $file->getRealPath();
        $fileExtension = $file->getClientOriginalExtension();

        if ($fileExtension === 'xlsx') {
            $spreadsheet = Excel::toArray([], $filePath, null, \Maatwebsite\Excel\Excel::XLSX)[0];
        } elseif ($fileExtension === 'csv') {
            $spreadsheet = Excel::toArray([], $filePath, null, \Maatwebsite\Excel\Excel::CSV)[0];
        } else {
            throw new \Exception('Unsupported file type: ' . $fileExtension);
        }

        $headers = array_shift($spreadsheet);

        $missingHeaders = array_diff(array_keys($headersToDb), $headers);
        if (!empty($missingHeaders)) {
            throw new \Exception('Missing headers: ' . implode(', ', $missingHeaders));
        }

        foreach ($spreadsheet as $row) {
            $data = array_combine($headers, $row);
            $mappedData = [];
            foreach ($headersToDb as $csvHeader => $dbField) {
                $mappedData[$dbField] = $data[$csvHeader] ?? null;
            }
            $mappedData['imported_file_id'] = $importedFileId;
            $this->saveData($mappedData, $importType);
        }
    }

    private function saveData($data, $importType)
    {
        switch ($importType) {
            case 'orders':
                Order::create($data);
                break;
            case 'customer_data':
                Customer::create($data);
                break;
            case 'inventory_data':
                if (isset($data['item_id'])) {
                    Inventory::create($data);
                } elseif (isset($data['supplier_id'])) {
                    Supplier::create($data);
                }
                break;
            default:
                throw new \Exception('Unknown import type.');
        }
    }

    private function generateFilename($files)
    {
        $fileNames = array_map(function($file) {
            return $file->getClientOriginalName();
        }, $files);

        return implode(', ', $fileNames);
    }
}
