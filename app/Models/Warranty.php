<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;

class Warranty extends Model
{
    use HasFactory;
    protected $fillable = ['id'];

    public function product()
    {
        return $this->hasMany(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id');
    }
    
    public static function check_warranty_exist($warranty_id) {
        $check = DB::table('warranties')->where('user_id', $warranty_id)->first();
        if($check) {
            return true;
        } else {
            return false;
        }
    }
    
    public static function product_recall($batch_id) {
        $all_product_with_batch_id = DB::table('products')->where('batch_id', $batch_id)->get();
        
        $agents_id = DB::table('products')->select('agent_id')
                        ->where([
                            ['batch_id', '=', $batch_id],
                            ['agent_id', '!=', null]
                        ])
                        ->groupBy('agent_id')->get();

        $customers_id = DB::table('products')->select('customer_id')
                        ->where([
                            ['batch_id', '=', $batch_id],
                            ['customer_id', '!=', null]
                        ])
                        ->groupBy('customer_id')->get();

        $warranties_id = DB::table('products')->select('warranty_id')
                        ->where([
                            ['batch_id', '=', $batch_id],
                            ['warranty_id', '!=', null]
                        ])
                        ->groupBy('warranty_id')->get();

        $factory_id = (DB::table('products')->where('batch_id', $batch_id)->first())->factory_id;
        
        $title = 'Thông báo thu hồi sản phẩm';
        $content = 'Do sản phẩm của lô hàng '. $batch_id  .' có sự cố, nên BigCorp đã yêu thu hồi sản phẩm. Rất xin lỗi vì sự cố này. Chúng tôi sẽ khắc phục và trả lại sản phẩm trong thời gian sớm nhất';
        
        foreach($agents_id as $id) {
            Product::create_notifi_transfer_product($title, $content, $id->agent_id);
        }

        foreach($warranties_id as $id) {
            Product::create_notifi_transfer_product($title, $content, $id->warranty_id);
        }

        foreach($customers_id as $id) {
            Product::create_notifi_transfer_product($title, $content, $id->customer_id);
        }

        Product::create_notifi_transfer_product($title, $content, $factory_id);
        
        foreach($all_product_with_batch_id as $product) {
            Product::transfer_product($product->factory_id, $product->factory_id, $product->id, 6);
        }

        return 'Thu hồi sản phẩm thành công';
    }

    public static function return_product_recall($batch_id) {
        $all_product_with_batch_id = DB::table('products')->where('batch_id', $batch_id)->get();
        
        $agents_id = DB::table('products')->select('agent_id')
                        ->where([
                            ['batch_id', '=', $batch_id],
                            ['agent_id', '!=', null]
                        ])
                        ->groupBy('agent_id')->get();

        $customers_id = DB::table('products')->select('customer_id')
                        ->where([
                            ['batch_id', '=', $batch_id],
                            ['customer_id', '!=', null]
                        ])
                        ->groupBy('customer_id')->get();

        $warranties_id = DB::table('products')->select('warranty_id')
                        ->where([
                            ['batch_id', '=', $batch_id],
                            ['warranty_id', '!=', null]
                        ])
                        ->groupBy('warranty_id')->get();

        $factory_id = (DB::table('products')->where('batch_id', $batch_id)->first())->factory_id;
        
        $title = 'Thông báo hoàn thành bảo hành';
        $content = 'Các sản phẩm thuộc lô hàng '. $batch_id  .' đã bảo hành xong sau sự cố thu hồi gần nhất. Xin cảm ơn';
        
        foreach($agents_id as $id) {
            Product::create_notifi_transfer_product($title, $content, $id->agent_id);
        }

        foreach($warranties_id as $id) {
            Product::create_notifi_transfer_product($title, $content, $id->warranty_id);
        }

        foreach($customers_id as $id) {
            Product::create_notifi_transfer_product($title, $content, $id->customer_id);
        }

        Product::create_notifi_transfer_product($title, $content, $factory_id);
        
        $products = DB::table('products')
                    ->where('batch_id', $batch_id)
                    ->get();
                    
        foreach($products as $product) {
            if($product -> status_id == 4 || $product -> status_id == 5) {
                DB::table('products') -> where('id', '=', $product->id)
                        ->update([
                            'is_recall' => 0,
                            'status_id' => 7,
                            'owner_id' => $product->agent_id,
                            'warranty_count' => $product->warranty_count + 1,
                            'updated_at' => Carbon::now(),
                        ]); 

                DB::table('warranty_products')
                ->where([
                    ['product_id', '=', $product->id],
                    ['status', '=', 0],
                ])
                ->update([
                    'status' => 1,
                    'updated_at' => Carbon::now(),
                ]);
            } else {
                DB::table('products') -> where('id', '=', $product->id)
                        ->update([
                            'is_recall' => 0,
                            'updated_at' => Carbon::now(),
                        ]); 
            }
            
        }

        return 'Trả sản phẩm thu hồi thành công';
    }
}
