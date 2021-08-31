<?php

namespace Tests\Unit\Repositories;

use App\Models\Category;
use App\Models\ProcessHistory;
use App\Repositories\CategoryRepository;
use App\Repositories\ICategoryRepository;
use App\Repositories\IProcessHistoryRepository;
use App\Repositories\ProcessHistoryRepository;
use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
use Mockery\Mock;
use Tests\TestCase;

class ProcessHistoryRepositoryTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @var IProcessHistoryRepository $repo
     */
    private $repo;

    private $faker;

    protected $category;

    public function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $this->repo = new ProcessHistoryRepository();
        $this->faker = Faker::create();
    }

    public function testGetLastFiftyDay()
    {
        $categories = \factory(ProcessHistory::class, 100)->create();
        $data = DB::select('select * from process_history order by id desc limit 50');
        $expected =null;
        if(sizeof($data) ==50){
            $expected =$data[49]->date;
        }

        $actual =$this->repo->getLastFiftyDay();
        $this->assertEquals($expected,$actual);
    }

    public function testGetLastFiftyDayNotEnoughData()
    {
        $categories = \factory(ProcessHistory::class, 40)->create();
        $actual =$this->repo->getLastFiftyDay();
        $this->assertNull($actual);
    }
}
