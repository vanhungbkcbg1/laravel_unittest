<?php

namespace App\Services;

use App\Repositories\ISymbolAnalyzedRepository;

class TextFileWriterService
{
    public static function write($file, array $data = [])
    {

        if (!$data) {
            return;
        }

        $handler = fopen($file, 'w+');
        fwrite($handler, $data[0]);
        $i = 1;
        while ($i < sizeof($data)) {
            fwrite($handler, PHP_EOL);
            fwrite($handler, $data[$i]);
            $i++;
        }
        fflush($handler);
        fclose($handler);
    }

}
