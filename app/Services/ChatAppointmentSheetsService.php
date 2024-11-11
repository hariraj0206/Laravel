<?php

namespace App\Services;

use Google\Client;
use Google\Service\Sheets;

class ChatAppointmentSheetsService
{
    protected $client;
    protected $service;
    protected $spreadsheetId;

    public function __construct()
    {
        $this->client = new Client();
        $this->client->setApplicationName('Laravel Google Sheets');
        $this->client->setScopes([Sheets::SPREADSHEETS_READONLY]);
        $this->client->setAuthConfig(storage_path('app/chat_appointment/chat_appointment.json'));
        $this->client->setAccessType('offline');

        $this->service = new Sheets($this->client);
        $this->spreadsheetId = '1PuCuQoJDI3h6K1cQsTjfgze3zzKiacq-0TE87pLyDcU'; // Replace with your spreadsheet ID
    }

    public function getSheetData($range)
    {
        $response = $this->service->spreadsheets_values->get($this->spreadsheetId, $range);
        return $response->getValues() ?: [];
    }
}
