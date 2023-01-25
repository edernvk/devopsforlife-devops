<?php

namespace App\Exports;

use Illuminate\Support\Facades\Storage;
use League\Csv\CannotInsertRecord;
use League\Csv\Writer;
use Spatie\SimpleExcel\SimpleExcelWriter;

class ReportExport {

    public static function generateCsv($basename, $headers, $entries) {

        $path = $basename.'.csv';
        $writer = Writer::createFromString();

        try {
            $writer->insertOne($headers);
            $writer->insertAll($entries);
        } catch (CannotInsertRecord $e) { }

        if (Storage::disk('s3')->put($path, $writer->toString())) {
            return $path;
        }

        return false;
    }

    public static function generateXlsx($basename, $headers, $entries) {

        $path = $basename.'.xlsx';
        $rows = [];

        foreach ($entries as $item) {
            $entry = array_combine($headers, $item);
            $rows[] = $entry;
        }

        $writer = SimpleExcelWriter::create($path)
            ->addRows($rows);

        if (Storage::disk('s3')->has($path)) {
            return $path;
        }

        return false;

    }
}
