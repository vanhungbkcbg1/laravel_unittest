<?php


namespace App;


use App\Common\CSVReader;
use simplehtmldom_1_5\simple_html_dom;
use function simplehtmldom_1_5\file_get_html;

class Downloader
{
    public static function download($date='')
    {
       static::login();

       if(!$date){
           $remotePageUrl = $url =sprintf("https://www.cophieu68.vn/export/dailyexcel.php?date=%s",date('d-m-Y'));; //url of the page you want to save
       } else {
           $remotePageUrl = $url =sprintf("https://www.cophieu68.vn/export/dailyexcel.php?date=%s",$date);; //url of the page you want to save
       }

        $remotePage = static::getUrl($remotePageUrl); //get the remote page
//        chmod(public_path("file.csv"),0775);
        file_put_contents(public_path("file.csv"), $remotePage);

    }

    private static function login(){
        $loginUrl = 'https://www.cophieu68.vn/account/login.php'; //action from the login form
        $loginFields = array('username'=>'vanhungbkcbg1@gmail.com', 'tpassword'=>'9c23sn','ajax'=>1,"login"=>1); //login form field names and values

        static::getUrl($loginUrl, 'post', $loginFields); //login to the site
    }

    private static function  getUrl($url, $method='', $vars='') {
        $ch = curl_init();
        if ($method == 'post') {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $vars);
        }
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_COOKIEJAR, '/tmp/cookies.txt');
        curl_setopt($ch, CURLOPT_COOKIEFILE, '/tmp/cookies.txt');

        $buffer = curl_exec($ch);

        curl_close($ch);
        return $buffer;
    }


    public static function downloadWebsite(){

        CSVReader::readFileV2();
//        $remotePageUrl = "https://www.cophieu68.vn/stockdaily.php?stcid=2&submit=Go";
//        return static::getUrl($remotePageUrl);
    }

}
