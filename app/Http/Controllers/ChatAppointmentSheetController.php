<?php

namespace App\Http\Controllers;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

use App\Services\ChatAppointmentSheetsService;
use Illuminate\Http\Request;
use PDF;

class ChatAppointmentSheetController extends Controller
{
    protected $chatSheetsService;

    public function __construct(ChatAppointmentSheetsService $chatSheetsService)
    {
        $this->chatSheetsService = $chatSheetsService;
    }

    public function index(Request $request)
    {
        $range = 'Sheet1!A:Z';  // Adjust the range according to your data structure
        $data = $this->chatSheetsService->getSheetData($range);
        
        // Paginate data with 10 items per page
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 10;
        $currentItems = array_slice($data, ($currentPage - 1) * $perPage, $perPage);
        $data = new LengthAwarePaginator($currentItems, count($data), $perPage, $currentPage, [
            'path' => $request->url(),
            'query' => $request->query()
        ]);
    
        return view('chat_appointments.index', compact('data'));
    }

    public function show($id)
    {
        $range = 'Sheet1!A:Z'; 
        $data = $this->chatSheetsService->getSheetData($range);
        
        // Ensure $data is a valid array
        if (!is_array($data)) {
            abort(500, 'Failed to retrieve data from Google Sheets.');
        }
    
        // Check if $id is within array bounds
        $chat_appointments = $data[$id] ?? null;
    
        if (!$chat_appointments) {
            abort(404, 'Appointment not found.');
        }
    
        return view('chat_appointments.show', compact('chat_appointments'));
    }
    
    public function downloadPDF($id)
    {
        $range = 'Sheet1!A:Z'; 
        $data = $this->chatSheetsService->getSheetData($range);
        
        // Ensure $data is a valid array
        if (!is_array($data)) {
            abort(500, 'Failed to retrieve data from Google Sheets.');
        }
    
        // Check if $id is within array bounds
        $chat_appointments = $data[$id] ?? null;
    
        if (!$chat_appointments) {
            abort(404, 'Appointment not found.');
        }
        
        $pdf = PDF::loadView('chat_appointments.pdf', compact('chat_appointments'));
        return $pdf->download("appointment-{$id}.pdf");
    }
}
