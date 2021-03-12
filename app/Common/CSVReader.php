<?php


namespace App\Common;


use Illuminate\Support\Str;

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

            $result[$data[0]] =[
                    "open"=>$data[2],
                    "high"=>$data[3],
                    'low'=>$data[4],
                    'close'=>$data[5],
                    "volume" =>$data[6],
                    "date"=>$data[1]
            ];

        }

        fclose($handler);
        return $result;
    }

    public static function readFileV2($handler){

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

            if(strlen($data[0]) >3){
                continue;
            }

            if(Str::startsWith($data[0],"^")){
                continue;
            }

            $result[$data[0]] =[
                "open"=>$data[2],
                "high"=>$data[3],
                'low'=>$data[4],
                'close'=>$data[5],
                "volume" =>$data[6],
                "name"=>$data[0]
            ];

        }

        fclose($handler);
        return $result;
    }
}
