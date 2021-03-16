<?php

namespace App\Http\Controllers;

use App\Common\CSVReader;
use App\Downloader;
use App\Jobs\StockPrice;
use App\Repositories\IDayOffRepository;
use App\Repositories\SymbolAnalyzedRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PHPHtmlParser\Dom;
use Sunra\PhpSimple\HtmlDomParser;

class DayOffController extends Controller
{
    private $repo;

    public function __construct(IDayOffRepository $repo)
    {
        $this->repo = $repo;
    }

    //
    public function index(Request $request)
    {
        $dayOffs= $this->repo->getAll();
        return view("dayoff", ['data'=>$dayOffs]);
    }

    public function newAction(Request $request){


        $request->validate([
            'day_off'=>'required|date_format:d/m/Y'
        ]);
        $day =date_create_from_format('d/m/Y',$request->input('day_off'))->format('Y-m-d');

        if($this->repo->findByDay($day)->count()>0){
            $request->session()->flash("error","duplicate day off");
            return redirect('/day-offs');
        }

        $this->repo->create([
            'day'=>$request->input('day_off')
        ]);

        $request->session()->flash("message","create dayoff successful");
        return redirect('/day-offs');
    }
}
