<?php


namespace Tests\Unit\Common;


use App\Common\CSVReader;
use Tests\TestCase;

class CSVReaderTest extends TestCase
{

    protected static $handler;

    /**
     * @beforeClass
     */
    public static function setUpTest()
    {

        //run before test class

        self::$handler = fopen("test.csv", "w+");
        fwrite(self::$handler,implode(",",["BID", "20201212", "5.4", "5.5", "5.3", "5.41", "130570"]));
        fwrite(self::$handler,PHP_EOL);
        fwrite(self::$handler,implode(",",["BID", "20201212", "5.4", "5.5", "5.3", "5.41", "130570"]));
        fwrite(self::$handler,PHP_EOL);
        fwrite(self::$handler,implode(",",["BID", "20201212", "5.4", "5.5", "5.3", "5.41", "130570"]));
        fclose(self::$handler);

        self::$handler = fopen("test.csv", "r");


    }

    /**
     * @afterClass
     */
    public static function endClass()
    {
        //run when finish test
        unlink("test.csv");
    }

    public function testReadFile()
    {


        $reader = new CSVReader();
        $expected = [
            "BID" => [
                "open" => 5.4,
                "high" => 5.5,
                "low" => 5.3,
                'close' => 5.41,
                'volume' => 130570
            ]
        ];

        $actual =$reader->readFile(self::$handler);

//        $this->assertSame(array_diff($expected,$actual),array_diff($actual,$expected));
        $this->assertEquals($expected,$actual);
    }
}
