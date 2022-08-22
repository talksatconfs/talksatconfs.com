<?php

namespace Domain\TalksAtConfs\Actions;

use Spatie\SimpleExcel\SimpleExcelReader;

class ImportTalksFromCsv
{
    public function handle($csvPath): void
    {
        $rows = SimpleExcelReader::create($csvPath, 'csv')->getRows();

        $rows->each(function (array $rowProperties) {
            (new ImportTalk($rowProperties['event_id'], $rowProperties))->handle();
        });
    }
}
