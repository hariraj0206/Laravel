<?php

namespace App\Services;

use Google\Client;
use Google\Service\Sheets;

class AppointmentSheetsService
{
    protected $client;
    protected $service;
    protected $spreadsheetId;

    public function __construct()
    {
        $this->client = new Client();
        $this->client->setApplicationName('Laravel Google Sheets');
        $this->client->setScopes([Sheets::SPREADSHEETS_READONLY]);
        $this->client->setAuthConfig(storage_path('app/appointment_sheet/supreme-hospitals-1f6d0c50f4a0.json'));
        $this->client->setAccessType('offline');

        $this->service = new Sheets($this->client);
        $this->spreadsheetId = '1DLAXVfEVCoT7oabLqbkOMVicDiZHp2haZEZzmHCp7Z4'; // Replace with your spreadsheet ID
    }

    public function getSheetData($range)
    {
        $response = $this->service->spreadsheets_values->get($this->spreadsheetId, $range);
        return $response->getValues() ?: [];
    }
}
