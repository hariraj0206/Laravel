<?php

namespace App\Http\Controllers;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

use App\Services\GoogleSheetsService;
use Illuminate\Http\Request;
use PDF;

class GoogleSheetsController extends Controller
{
    protected $googleSheetsService;

    public function __construct(GoogleSheetsService $googleSheetsService)
    {
        $this->googleSheetsService = $googleSheetsService;
    }

    public function index(Request $request)
    {
        $range = 'Sheet1!A:Z'; // Assuming the data is in columns A to Z
        $data = $this->googleSheetsService->getSheetData($range);
        
        // Paginate data with 10 items per page
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 10;
        $currentItems = array_slice($data, ($currentPage - 1) * $perPage, $perPage);
        $data = new LengthAwarePaginator($currentItems, count($data), $perPage, $currentPage, [
            'path' => $request->url(),
            'query' => $request->query()
        ]);
    
        return view('google-sheets.index', compact('data'));
    }

    public function show($id)
{
    $range = 'Sheet1!A:Z'; 
    $data = $this->googleSheetsService->getSheetData($range);
    
   
    $patient = $data[$id] ?? null;

    if (!$patient) {
        abort(404); 
    }

    return view('google-sheets.show', compact('patient'));
}

public function downloadPDF($id)
{
    $range = 'Sheet1!A:Z'; // Adjust the range according to your data structure
    $data = $this->googleSheetsService->getSheetData($range);
    
    $patient = $data[$id] ?? null;

    if (!$patient) {
        abort(404);
    }

    $pdf = PDF::loadView('google-sheets.pdf', compact('patient'));
    return $pdf->download("patient-feedback-{$id}.pdf");
}
}
