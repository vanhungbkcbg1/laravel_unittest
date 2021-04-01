<?php

namespace App\Http\Controllers;

use App\Common\CSVReader;
use App\Downloader;
use App\Jobs\StockPrice;
use App\Repositories\SymbolAnalyzedRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use PHPHtmlParser\Dom;
use Sunra\PhpSimple\HtmlDomParser;

class HomeController extends Controller
{
    const TAB_HOSE ='hose';
    const TAB_UPCOM ='upcom';
    const TAB_HNX ='hnx';
    private $repo;

    public function __construct(SymbolAnalyzedRepository $repo)
    {
        $this->repo = $repo;
    }

    public function outputFile(Request $request){
        $tabActive = $request->get('tab', self::TAB_HOSE);
        if ($tabActive == self::TAB_HOSE) {
            $data = DB::table("symbol_analyzed")
                ->join('new_symbols', 'symbol_analyzed.symbol', '=', 'new_symbols.name')
                ->where('new_symbols.exchange', strtoupper(self::TAB_HOSE))
                ->get();
        }
        if ($tabActive == self::TAB_HNX) {
            $data = DB::table("symbol_analyzed")
                ->join('new_symbols', 'symbol_analyzed.symbol', '=', 'new_symbols.name')
                ->where('new_symbols.exchange', strtoupper(self::TAB_HNX))
                ->get();
        }

        if ($tabActive == self::TAB_UPCOM) {
            $data = DB::table("symbol_analyzed")
                ->join('new_symbols', 'symbol_analyzed.symbol', '=', 'new_symbols.name')
                ->where('new_symbols.exchange', strtoupper(self::TAB_UPCOM))
                ->get();
        }

        if(!$data->count()){
            return "no data";
        }


        $handler =fopen(public_path('process_data.txt'),'w+');
        $content=implode(PHP_EOL,array_map(function($item){
                return $item->symbol;
        },$data->all()));

        fwrite($handler,$content);
        fclose($handler);

        return \response()->download(public_path('process_data.txt'));
    }
    //
    public function index(Request $request)
    {
        $weekOfMonth = $this->weekOfMonth();
        $weekDay = strtotime('w', strtotime('now'));

        $tabActive = $request->get('tab', self::TAB_HOSE);
        $page = $request->get('page', 1);

        if ($tabActive == self::TAB_HOSE) {
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


        } elseif ($tabActive == self::TAB_HNX) {
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

        } elseif ($tabActive == self::TAB_UPCOM) {
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
