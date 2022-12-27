<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;use Carbon\Carbon;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class CheckEndDate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'product:checkEndDate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $products_sold_and_have_date = 
                DB::table('products')
                    ->where([
                        ['out_of_warranty', '=', 0],
                        ['customer_buy_time', '!=', null]
                    ])->get();
            foreach($products_sold_and_have_date as $product) {
                $title = 'Có sản phẩm hết hạn bảo hành';
                $content = 'Sản phẩm' . $product->id . 'đã hết hạn bảo hành';

                if(!Carbon::parse($product->end_date)->isFuture()) {
                    Product::create_notifi_transfer_product($title, $content, $product->customer_id);
                    DB::table('products')
                    ->where('id', $product->id)
                    ->update([
                        'out_of_warranty' => 1,
                    ]);
                } 
            }
    }
}
