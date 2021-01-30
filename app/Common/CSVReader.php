<?php


namespace App\Common;


class CSVReader
{
    public function readFile($handler){

        $result =[];
        $i=0;
        while (!feof($handler)){


            $i++;
            if($i <3) {
                fgets($handler);
                continue;
            }
            $line =fgets($handler);
            if(!$line){
                continue;
            }
            $data =str_getcsv($line);

            $result[$data[0].".XSTC"] =[
                    "open"=>$data[2],
                    "high"=>$data[3],
                    'low'=>$data[4],
                    'close'=>$data[5],
                    "volume" =>$data[6]
            ];

        }

        fclose($handler);
        return $result;
    }
}
