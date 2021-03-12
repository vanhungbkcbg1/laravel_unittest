<?php

namespace App\Http\Controllers;

use App\Common\CSVReader;
use App\Downloader;
use App\Jobs\StockPrice;
use App\Repositories\SymbolAnalyzedRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PHPHtmlParser\Dom;
use Sunra\PhpSimple\HtmlDomParser;

class HomeController extends Controller
{
    private $repo;

    public function __construct(SymbolAnalyzedRepository $repo)
    {
        $this->repo = $repo;
    }

    //
    public function index(Request $request)
    {
        $weekOfMonth = $this->weekOfMonth();
        $weekDay = strtotime('w', strtotime('now'));

        $tabActive = $request->get('tab', 'hose');
        $page = $request->get('page', 1);

        if ($tabActive == 'hose') {
            $hose = DB::table("symbol_analyzed")
                ->join('new_symbols', 'symbol_analyzed.symbol', '=', 'new_symbols.name')
                ->orderBy('symbol_analyzed.volume', 'desc')
                ->where('new_symbols.exchange', 'HOSE')
                ->select('symbol_analyzed.*')
                ->paginate(20, ["*"], 'page', $page)
                ->appends(["tab" => "hose"]);

            $hnx = DB::table("symbol_analyzed")
                ->join('new_symbols', 'symbol_analyzed.symbol', '=', 'new_symbols.name')
                ->orderBy('symbol_analyzed.volume', 'desc')
                ->where('new_symbols.exchange', 'HNX')
                ->select('symbol_analyzed.*')
                ->paginate(20, ['*'], 'page', 1)
                ->appends(["tab" => "hnx"]);

            $upcom = DB::table("symbol_analyzed")
                ->join('new_symbols', 'symbol_analyzed.symbol', '=', 'new_symbols.name')
                ->orderBy('symbol_analyzed.volume', 'desc')
                ->where('new_symbols.exchange', 'UPCOM')
                ->select('symbol_analyzed.*')
                ->paginate(20, ['*'], 'page', 1)
                ->appends(["tab" => "upcom"]);


        } elseif ($tabActive == 'hnx') {
            $hose = DB::table("symbol_analyzed")
                ->join('new_symbols', 'symbol_analyzed.symbol', '=', 'new_symbols.name')
                ->orderBy('symbol_analyzed.volume', 'desc')
                ->where('new_symbols.exchange', 'HOSE')
                ->select('symbol_analyzed.*')
                ->paginate(20, ["*"], 'page', 1)
                ->appends(["tab" => "hose"]);
            $hnx = DB::table("symbol_analyzed")
                ->join('new_symbols', 'symbol_analyzed.symbol', '=', 'new_symbols.name')
                ->orderBy('symbol_analyzed.volume', 'desc')
                ->where('new_symbols.exchange', 'HNX')
                ->select('symbol_analyzed.*')
                ->paginate(20, ['*'], 'page', $page)
                ->appends(["tab" => "hnx"]);

            $upcom = DB::table("symbol_analyzed")
                ->join('new_symbols', 'symbol_analyzed.symbol', '=', 'new_symbols.name')
                ->orderBy('symbol_analyzed.volume', 'desc')
                ->where('new_symbols.exchange', 'UPCOM')
                ->select('symbol_analyzed.*')
                ->paginate(20, ['*'], 'page', 1)
                ->appends(["tab" => "upcom"]);

        } elseif ($tabActive == 'upcom') {
            $hose = DB::table("symbol_analyzed")
                ->join('new_symbols', 'symbol_analyzed.symbol', '=', 'new_symbols.name')
                ->orderBy('symbol_analyzed.volume', 'desc')
                ->where('new_symbols.exchange', 'HOSE')
                ->select('symbol_analyzed.*')
                ->paginate(20, ["*"], 'page', 1)
                ->appends(["tab" => "hose"]);
            $hnx = DB::table("symbol_analyzed")
                ->join('new_symbols', 'symbol_analyzed.symbol', '=', 'new_symbols.name')
                ->orderBy('symbol_analyzed.volume', 'desc')
                ->where('new_symbols.exchange', 'HNX')
                ->select('symbol_analyzed.*')
                ->paginate(20, ['*'], 'page', 1)
                ->appends(["tab" => "hnx"]);

            $upcom = DB::table("symbol_analyzed")
                ->join('new_symbols', 'symbol_analyzed.symbol', '=', 'new_symbols.name')
                ->orderBy('symbol_analyzed.volume', 'desc')
                ->where('new_symbols.exchange', 'UPCOM')
                ->select('symbol_analyzed.*')
                ->paginate(20, ['*'], 'page', $page)
                ->appends(["tab" => "upcom"]);
        }

        return view("index", ['hose' => $hose, 'hnx' => $hnx, 'upcom' => $upcom, 'tabActive' => $tabActive, 'week' => $weekOfMonth, 'weekday' => $weekDay]);

    }

    function weekOfMonth()
    {
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
        $url = sprintf("https://images1.cafef.vn/data/%s/CafeF.SolieuGD.Raw.%s.zip", '20201231', '31122020');
        $content = file_get_contents($url);
        file_put_contents("test.zip", $content);

    }

    public function downloadWebsite()
    {
        $handler = fopen("test.csv", 'r');

        $data = CSVReader::readFileV2($handler);

        dump($data);

        die();
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
