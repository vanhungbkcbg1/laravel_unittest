<?php

namespace App\Http\Controllers;

use App\Jobs\StockPrice;
use App\Repositories\SymbolAnalyzedRepository;

class HomeController extends Controller
{
    private $repo;

    public function __construct(SymbolAnalyzedRepository $repo)
    {
        $this->repo = $repo;
    }

    //
    public function index()
    {
        $weekOfMonth=$this->weekOfMonth();
        $weekDay = strtotime('w',strtotime('now'));
        $data = $this->repo->paginate(20, ['volume' => "desc"]);
        return view("index", ['data' => $data,'week'=>$weekOfMonth,'weekday'=>$weekDay]);

    }

    function weekOfMonth() {
//        $date=new Date();
//        $firstOfMonth = date("Y-m-01", strtotime('now'));
//        dump(intval(date("W", strtotime($firstOfMonth))));
        return intval(date("W", strtotime('now')));
//        return intval(date("W", strtotime('now'))) - intval(date("W", strtotime($firstOfMonth)));
    }

    public function download()
    {

        $this->downloadFileCophieu68();
//        $this->changePermission();

//        $this->downloadFile();
//
//        //unzip file
//        $unzip =$this->unzip();
//        if($unzip===false) {
//            session()->flash("message", "download file failed");
//            return redirect("/");
//        }
//        $this->changePermission();
//        $this->deleteFile();
        session()->flash("message", "download file success");

        //dispatch job
        StockPrice::dispatch();

        return redirect("/");
    }

    public function unzip()
    {
        $zip = new \ZipArchive();
        if ($zip->open("test.zip") === true) {
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $zip->renameIndex(0, "file.csv");
                $zip->extractTo(".", $zip->getNameIndex(0));
            }

            $zip->close();
            return true;

        }

        return false;
    }

    public function downloadFile(): void
    {
//        $url =sprintf("https://images1.cafef.vn/data/%s/CafeF.SolieuGD.Raw.%s.zip",date('Ymd'),date('dmY'));
        $url =sprintf("https://images1.cafef.vn/data/%s/CafeF.SolieuGD.Raw.%s.zip",'20201231','31122020');
        $content = file_get_contents($url);
        file_put_contents("test.zip", $content);
    }

    public function downloadFileCophieu68(): void
    {
//        $url =sprintf("https://www.cophieu68.vn/export/dailyexcel.php?date=%s",date('d-m-Y'));
//
//        $auth = base64_encode('vanhungbkcbg1@gmail.com:9c23sn');
//
//        $arrContextOptions=array(
//            "ssl"=>array(
//                "verify_peer"=>false,
//                "verify_peer_name"=>false,
//            ),
//            'http' => array(
//                'header' => "Authorization: Basic $auth"
//            )
//        );
//
//
//        $content = file_get_contents($url,false,stream_context_create($arrContextOptions));
//        file_put_contents("file.csv", $content);


        //
        $loginUrl = 'https://www.cophieu68.vn/account/login.php'; //action from the login form
        $loginFields = array('username'=>'vanhungbkcbg1@gmail.com', 'tpassword'=>'9c23sn','ajax'=>1,"login"=>1); //login form field names and values
        $remotePageUrl = $url =sprintf("https://www.cophieu68.vn/export/dailyexcel.php?date=%s",date('d-m-Y'));; //url of the page you want to save
//        $remotePageUrl = $url =sprintf("https://www.cophieu68.vn/export/dailyexcel.php?date=%s",'29-01-2021');; //url of the page you want to save

        $login = $this->getUrl($loginUrl, 'post', $loginFields); //login to the site

        $remotePage = $this->getUrl($remotePageUrl); //get the remote page
//        @chmod('file.csv',0777);
//        @chown('file.csv','www-data');
        file_put_contents("file.csv", $remotePage);



    }

    public function deleteFile(): void
    {
        unlink("test.zip");
    }

    public function changePermission(): void
    {
        chmod("file.csv", 0777);
    }

    function getUrl($url, $method='', $vars='') {
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
//        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.3) Gecko/20070309 Firefox/2.0.0.3");
        curl_setopt($ch, CURLOPT_COOKIEFILE, '/tmp/cookies.txt');

        if($method ==""){
//            $fp=fopen("file.csv","w+");
//            curl_setopt($ch, CURLOPT_FILE, $fp);
//            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        }
        $buffer = curl_exec($ch);

        if($buffer===false){
            var_dump(curl_error($ch));
        }
        curl_close($ch);
        return $buffer;
    }
}
