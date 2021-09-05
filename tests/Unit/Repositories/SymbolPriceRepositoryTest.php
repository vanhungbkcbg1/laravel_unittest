<?php

namespace Tests\Unit\Repositories;

use App\Models\Category;
use App\Models\NewSymbol;
use App\Models\Symbol;
use App\Models\SymbolPrice;
use App\Repositories\CategoryRepository;
use App\Repositories\ICategoryRepository;
use App\Repositories\ISymbolPriceRepository;
use App\Repositories\SymbolPriceRepository;
use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Faker\Factory as Faker;
use Mockery\Mock;
use Tests\TestCase;

class SymbolPriceRepositoryTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @var ISymbolPriceRepository $repo
     */
    private $repo;

    private $faker;

    protected $category;

    public function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $this->repo=new SymbolPriceRepository();
        $this->faker=Faker::create();
        $this->category=[
            "name"=>$this->faker->name,
            "description"=>$this->faker->name
        ];
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }

    /**
     * test insert repository
     */
    public function testGetAverageFifteenDay(){
      \factory(SymbolPrice::class,2)->create();
      \factory(SymbolPrice::class,10)->create([

          "symbol"=>"ABC"
      ]);

      //create symbol
      $symbol=  \factory(NewSymbol::class)->create([
          "name"=>"ABC"
      ]);


        $today = date('Y-m-d');
        $fromDate= date('Y-m-d',strtotime('-5 weekday'));
      $all=$this->repo->getAll();
      $dataFilled =$all->filter(function($item) use ($today,$fromDate){
          return $item->date < $today && $item->date >= $fromDate;
      });
      $total =0 ;
      foreach ($dataFilled as $item){
          if($item->symbol =="ABC"){
              $total += $item->volume;
          }
      }

      $expect =$dataFilled->count() >0?$total/$dataFilled->count():0;

      $actual =$this->repo->getAverageFifteenDay($symbol,$fromDate);

      $this->assertEquals($expect,$actual);
    }

    public function testDeleteOldData(){

        $obj=new SymbolPrice();
        $obj->date ='2021-04-10';
        $obj->symbol ='test';
        $obj->price =10;
        $obj->volume =100;
        $obj->save();

       $date=new \DateTime();
       $this->repo->deleteOldData($date);

       $this->assertDatabaseMissing('symbol_prices',['id'=>$obj->id]);
    }
}
