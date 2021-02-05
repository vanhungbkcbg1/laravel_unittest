<?php


namespace App;


class Downloader
{
    public static function download()
    {
        $loginUrl = 'https://www.cophieu68.vn/account/login.php'; //action from the login form
        $loginFields = array('username'=>'vanhungbkcbg1@gmail.com', 'tpassword'=>'9c23sn','ajax'=>1,"login"=>1); //login form field names and values
        $remotePageUrl = $url =sprintf("https://www.cophieu68.vn/export/dailyexcel.php?date=%s",date('d-m-Y'));; //url of the page you want to save

        static::getUrl($loginUrl, 'post', $loginFields); //login to the site

        $remotePage = static::getUrl($remotePageUrl); //get the remote page
        file_put_contents(public_path("file.csv"), $remotePage);

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
}
