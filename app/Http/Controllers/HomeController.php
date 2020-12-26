<?php

namespace App\Http\Controllers;

use App\Repositories\SymbolAnalyzedRepository;
use Illuminate\Http\Request;

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
        $data = $this->repo->paginate(20, ['volume' => "desc"]);
        return view("index", ['data' => $data]);
    }

    public function download()
    {

        $this->downloadFile();

        //unzip file
        $unzip =$this->unzip();
        if($unzip===false) {
            session()->flash("message", "download file failed");
            return redirect("/");
        }
        $this->changePermission();
        $this->deleteFile();
        session()->flash("message", "download file success");
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
        $url =sprintf("https://images1.cafef.vn/data/%s/CafeF.SolieuGD.Raw.%s.zip",date('Ymd'),date('dmY'));
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
