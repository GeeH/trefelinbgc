<?php declare(strict_types=1);

use Google\Spreadsheet\DefaultServiceRequest;
use Google\Spreadsheet\ServiceRequestFactory;

function getFixtures($getAll = false): array
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
        if ($row['date']->getTimestamp() >= time() || $getAll) {
            $row['date'] = $row['date']->format('jS M');
            $fixtures[] = $row;

            if (count($fixtures) === 3 && !$getAll) {
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
        if ($row['result'] !== '') {
            $row['date'] = $row['date']->format('jS M');
            $results[] = $row;
        }
        if (count($results) === 3 && !$getAll) {
            break;
        }
    }

    return ['fixtures' => $fixtures, 'results' => $results];
}

function getGoogleSheet(): \Google\Spreadsheet\Worksheet
{
    if (!getenv('GOOGLE_AUTH_CONFIG')) {
        putenv('GOOGLE_AUTH_CONFIG=' . file_get_contents(__DIR__ . '/../client_secret.json'));
    };

    $client = new Google_Client();
    $client->setAuthConfig(json_decode(getenv('GOOGLE_AUTH_CONFIG'), true));
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