<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ExcelDataController extends Controller
{
    public function getExcelData()
    {
        try {
            // Try multiple possible paths
            $paths = [
                base_path('data.xlsx'),
                base_path('../data.xlsx'),
                storage_path('../data.xlsx'),
                public_path('../data.xlsx'),
            ];
            
            $filePath = null;
            foreach ($paths as $path) {
                if (file_exists($path)) {
                    $filePath = $path;
                    break;
                }
            }
            
            if (!$filePath) {
                return response()->json(['error' => 'File not found at any path'], 404);
            }
            
            $spreadsheet = IOFactory::load($filePath);
            $sheet = $spreadsheet->getActiveSheet();
            
            $data = [];
            $headers = [];
            
            foreach ($sheet->getRowIterator() as $index => $row) {
                $rowData = [];
                foreach ($row->getCellIterator() as $cell) {
                    $rowData[] = $cell->getValue();
                }
                
                if ($index === 1) {
                    $headers = $rowData;
                } else {
                    $row_assoc = array_combine($headers, $rowData);
                    // Gunakan Lokasi sebagai key
                    if (!empty($row_assoc['Lokasi'])) {
                        $data[$row_assoc['Lokasi']] = $row_assoc;
                    }
                }
            }
            
            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
