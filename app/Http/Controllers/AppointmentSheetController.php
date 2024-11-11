<?php

namespace App\Http\Controllers;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

use App\Services\AppointmentSheetsService;
use Illuminate\Http\Request;
use PDF;

class AppointmentSheetController extends Controller
{
    protected $googleSheetsService;

    public function __construct(AppointmentSheetsService $googleSheetsService)
    {
        $this->googleSheetsService = $googleSheetsService;
    }

    public function index(Request $request)
    {
        $range = 'Book Appointment!A:Z';  // Adjust the range according to your data structure
        $data = $this->googleSheetsService->getSheetData($range);
        
        // Paginate data with 10 items per page
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 10;
        $currentItems = array_slice($data, ($currentPage - 1) * $perPage, $perPage);
        $data = new LengthAwarePaginator($currentItems, count($data), $perPage, $currentPage, [
            'path' => $request->url(),
            'query' => $request->query()
        ]);
    
        return view('appointments.index', compact('data'));
    }

    public function show($id)
    {
        $range = 'Book Appointment!A:Z'; 
        $data = $this->googleSheetsService->getSheetData($range);
        
        $appointment = $data[$id] ?? null;

        if (!$appointment) {
            abort(404); 
        }

        return view('appointments.show', compact('appointment'));
    }
    public function downloadPDF($id)
    {
        $range = 'Book Appointment!A:Z'; 
        $data = $this->googleSheetsService->getSheetData($range);
        
        $appointment = $data[$id] ?? null;

        if (!$appointment) {
            abort(404); 
        }
    
        $pdf = PDF::loadView('appointments.pdf', compact('appointment'));
        return $pdf->download("appointment-{$id}.pdf");
    }
    
}
