<?php

namespace App\Http\Controllers;
use App\Services\GoogleSheetsService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

    protected $googleSheetsService;

    public function __construct(GoogleSheetsService $googleSheetsService)
    {
        $this->googleSheetsService = $googleSheetsService;
    }
    public function index()
    {
        $range = 'Sheet1!A:Z'; // Assuming the data is in columns A to D
        $data = $this->googleSheetsService->getSheetData($range);
        return view('dashboard', compact('data'));
    }
}