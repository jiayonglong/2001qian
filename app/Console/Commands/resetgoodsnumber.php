<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class resetgoodsnumber extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'resetgoodsnumber';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '订单未支付吵吵0分钟修改订单状态';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        
    }
}
