<?php

namespace App\Http\Controllers;

use App\Downloader;
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
        return intval(date("W", strtotime('now')));
    }

    public function download()
    {

        Downloader::download();
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
        $url =sprintf("https://images1.cafef.vn/data/%s/CafeF.SolieuGD.Raw.%s.zip",'20201231','31122020');
        $content = file_get_contents($url);
        file_put_contents("test.zip", $content);

    }

    public function deleteFile(): void
    {
        unlink("test.zip");
    }

    public function changePermission(): void
    {
        chmod("file.csv", 0777);
    }

}
