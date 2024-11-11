<?php

namespace App\Services;

use Google\Client;
use Google\Service\Sheets;

class GoogleSheetsService
{
    protected $client;
    protected $service;
    protected $spreadsheetId;

    public function __construct()
    {
        $this->client = new Client();
        $this->client->setApplicationName('Laravel Google Sheets');
        $this->client->setScopes([Sheets::SPREADSHEETS_READONLY]);
        $this->client->setAuthConfig(storage_path('app/google/credentials.json'));
        $this->client->setAccessType('offline');

        $this->service = new Sheets($this->client);
        $this->spreadsheetId = '1l5xhKtPi_2Rgf9jjsGnAH3rvQhD1kol5NOYnOPTvOSM'; // Replace with your spreadsheet ID
    }

    public function getSheetData($range)
    {
        $response = $this->service->spreadsheets_values->get($this->spreadsheetId, $range);
        return $response->getValues() ?: [];
    }
}
