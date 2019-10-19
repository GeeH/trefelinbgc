<?php declare(strict_types=1);
require_once('vendor/autoload.php');

use Google\Spreadsheet\DefaultServiceRequest;
use Google\Spreadsheet\ServiceRequestFactory;

function getFixtures(): array
{
    $sheet = getGoogleSheet();

    $fixtures = [];
    foreach ($sheet->getListFeed([])->getEntries() as $entry) {
        $row = $entry->getValues();
        $row['date'] =
            DateTimeImmutable::createFromFormat(
                'D jS F Y',
                $row['date']
            );
        if ($row['date']->getTimestamp() >= time()) {
            $fixtures[] = $row;
            if (count($fixtures) === 3) {
                break;
            }
        }
    }

    $results = [];
    foreach (array_reverse($sheet->getListFeed([])->getEntries()) as $entry) {
        $row = $entry->getValues();
        $row['date'] =
            DateTimeImmutable::createFromFormat(
                'D jS F Y',
                $row['date']
            );
        if($row['result'] !== '') {
            $results[] = $row;
        }
        if (count($results) === 3) {
            break;
        }
    }


    return ['fixtures' => $fixtures, 'results' => $results];
}

function getGoogleSheet(): \Google\Spreadsheet\Worksheet
{
    putenv('GOOGLE_APPLICATION_CREDENTIALS=' . __DIR__ . '/client_secret.json');
    $client = new Google_Client;
    $client->useApplicationDefaultCredentials();

    $client->setApplicationName("Trefelin Fixtures");
    $client->setScopes(['https://www.googleapis.com/auth/drive', 'https://spreadsheets.google.com/feeds']);


    $accessToken = $client->fetchAccessTokenWithAssertion()["access_token"];

    ServiceRequestFactory::setInstance(
        new DefaultServiceRequest($accessToken)
    );

    $spreadsheet = (new Google\Spreadsheet\SpreadsheetService)
        ->getSpreadsheetFeed()
        ->getByTitle('Trefelin Fixtures');

    $worksheets = $spreadsheet->getWorksheetFeed()->getEntries();
    $worksheet = $worksheets[0];

    return $worksheet;
}