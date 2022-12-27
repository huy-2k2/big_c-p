<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use DateTime;
use App\Events\CreateNotifiEvent;
use App\Models\User;
use App\Models\Notification;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['depot_id', 'batch_id', 'agent_id', 'customer_id', 'factory_id', 'status', 'warranty_id'];

    public static function count_quantity_product($names, $id_values)
    {
        $count_result = DB::table('products')->where(function ($query) use ($names, $id_values) {
            for ($i = 0; $i < count($names); $i++) {
                $query->where($names[$i], '=', $id_values[$i]);
            }
        })->get()->count();

        return $count_result;
    }

    public static function get_product($names, $id_values)
    {
        $result = DB::table('products')->where(function ($query) use ($names, $id_values) {
            for ($i = 0; $i < count($names); $i++) {
                $query->where($names[$i], '=', $id_values[$i]);
            }
        })->get();

        return $result;
    }

    public static function create_notifi_transfer_product($title, $content, $user_id_to) {
        $notification = Notification::create([
            'title' => $title,
            'content' => $content
        ]);

        $notification->users()->attach($user_id_to);
        broadcast(new CreateNotifiEvent(['user_id' => $user_id_to, 'notification' => $notification, 'time' => $notification->created_at->toDateTimeString()]));
    }

    public static function transfer_product($user_id_from, $user_id_to, $product_id, $status_id_to, $note=null) {
        $name_from = (DB::table('users')->where('id', '=', $user_id_from)->first())->name;
        $name_to = (DB::table('users')->where('id', '=', $user_id_to)->first())->name;
        $product = (DB::table('products')->where('id', '=', $product_id)->first())->id;

        if($status_id_to == 3) { /** Đại lý bán hàng cho người dùng hoặc trả lại sản phẩm đã bảo hành*/
            $title = 'Thông báo nhận hàng';
            $content = 'Đại lý '. $name_from. ' đã giao sản phẩm '. $product . ' cho bạn';
            $message = 'Giao hàng thành công';
                        
            $notifi_function = new Product();
            $notifi_function::create_notifi_transfer_product($title, $content, $user_id_to);

            DB::table('products') -> where('id', '=', $product_id)
                        ->update([
                            'depot_id' => 1,
                            'status_id' => 3,
                            'customer_id' => $user_id_to,
                            'owner_id' => $user_id_to,
                            'customer_buy_time' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ]);

            return $message;
        } else if($status_id_to == 4) { /**Đại lý nhận sp cần bảo hành từ người dùng */
            $title = 'Thông báo bảo hành sản phẩm';
            $content = 'Người mua '. $name_from. ' đã yêu cầu bảo hành sản phẩm '. $product . ' với lý do: '. $note['reason'] .'. Vui lòng liên hệ lại và thực hiện bảo hành';
            $message = 'Yêu cầu bảo hành thành công';

            $notifi_function = new Product();
            $notifi_function::create_notifi_transfer_product($title, $content, $user_id_to);
            
            DB::table('products') -> where('id', '=', $product_id)
                        ->update([
                            'status_id' => 4,
                            'owner_id' => $user_id_to,
                            'updated_at' => Carbon::now(),
                        ]); 
            
            DB::table('warranty_products')->insert([
                'product_id' => $product_id,
                'status' => 0,
                'reason' => $note['reason'],
                'created_at' => Carbon::now()
            ]);

            return $message;
        } else if($status_id_to == 5) { /**Đại lí chuyển sp đến ttbh */
            $title = 'Thông báo bảo hành sản phẩm';
            $content = 'Đại lý '. $name_from. ' đã yêu cầu bảo hành sản phẩm '. $product  .'. Vui lòng thực hiện bảo hành';
            $message = 'Chuyển đến trung tâm bảo hành thành công';
            
            $notifi_function = new Product();
            $notifi_function::create_notifi_transfer_product($title, $content, $user_id_to);

            DB::table('products') -> where('id', '=', $product_id)
                        ->update([
                            'warranty_id' => $user_id_to,
                            'status_id' => 5,
                            'owner_id' => $user_id_to,
                            'updated_at' => Carbon::now(),
                        ]); 

            return $message;
        } else if($status_id_to == 7) { /**Đại lý nhận sản phẩm sửa xong từ trung tâm bảo hành */
            $current_warranty_count = (DB::table('products') -> where('id', '=', $product_id)->first())->warranty_count;
            DB::table('products') -> where('id', '=', $product_id)
                        ->update([
                            'status_id' => 7,
                            'owner_id' => $user_id_to,
                            'warranty_count' => $current_warranty_count + 1,
                            'updated_at' => Carbon::now(),
                        ]); 
        } else if($status_id_to == 8) { /**TTBh chuyển sp ko sửa được đến cơ sở sản xuất */
            DB::table('products') -> where('id', '=', $product_id)
                        ->update([
                            'status_id' => 8,
                            'owner_id' => $user_id_to,
                            'updated_at' => Carbon::now(),
                        ]); 

            //cần sửa: thêm sản phẩm mới cho người dùng
        } else if($status_id_to == 6) { /**Sp cần triệu hồi */
            DB::table('products') -> where('id', '=', $product_id)
                        ->update([
                            'status_id' => 8,
                            'owner_id' => $user_id_to,
                            'updated_at' => Carbon::now(),
                        ]); 
        }
    }

    public static function check_product_exist($product_id) {
        $check = DB::table('products')->where('id', $product_id)->first();
        if($check) {
            return true;
        } else {
            return false;
        }
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class, 'batch_id');
    }

    public function depot()
    {
        return $this->belongsTo(Depot::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function warranty()
    {
        return $this->belongsTo(Warranty::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function factory()
    {
        return $this->belongsTo(Factory::class, 'factory_id', 'user_id');
    }

    public function agent() 
    {
        return $this->belongsTo(Agent::class, 'agent_id', 'user_id');
    }

    public static function get_with_pivot_condition($table, $name)
    {
        return Self::whereHas($table, function (Builder $query) use ($name) {
            $query->where('name', $name);
        })->get();
    }

    public function getCreatedAtAttribute($date)
    {
        if ($date != null) {
            $format = new DateTime($date);
            $format->format('Y-m-d H:i:s');
            return $format;
        }
        return null;
    }
    
    public function getUpdatedAtAttribute($date)
    {
        if ($date != null) {
            $format = new DateTime($date);
            $format->format('Y-m-d H:i:s');
            return $format;
        }
        return null;
    }

    public function getCustomerBuyTimeAttribute($date)
    {
        if ($date != null) {
            $format = new DateTime($date);
            $format->format('Y-m-d H:i:s');
            return $format;
        }
        return null;
    }

    public static function excel_export($products, $timeline = "")
    {
        foreach ($products as $product) {
            $product['status'] = $product->status->name;
            $product['rangeName'] = $product->batch->range->name;
            $product['rangeProperty'] = $product->batch->range->property;
            if (!empty($product->factory->user)) {
                $product['factory'] = $product->factory->user->name;
            }
            if (!empty($product->agent->user)) {
                $product['agent'] = $product->agent->user->name;
            } else {
                $product['agent'] = null;
            }
            if ($timeline == "customer_by_time") {
                $product['date'] = $product->customer_buy_time;
            } else {
                $product['date'] = $product->created_at;
            }
            
            unset($product['factory_id'], $product['agent_id'], $product['warranty_count'],
            $product['warranty_id'], $product['status_id'], $product['batch_id'], 
            $product['depot_id'], $product['owner_id'], $product['customer_id'], $product['created_at'],
            $product['updated_at'], $product['customer_buy_time']);
        }
        return $products;
    }

    public static function excel_export_product_by_month($products, $timeline = "") {
        foreach ($products as $product) {
            $product['status'] = $product->status->name;
            $product['rangeName'] = $product->batch->range->name;
            $product['rangeProperty'] = $product->batch->range->property;
            if (!empty($product->factory->user)) {
                $product['factory'] = $product->factory->user->name;
            }
            if (!empty($product->agent->user)) {
                $product['agent'] = $product->agent->user->name;
            } else {
                $product['agent'] = null;
            }

            if ($timeline == "customer_by_time") {
                $timestamp = $product->customer_buy_time;
                $month = date_format($timestamp, 'M');
                $product['month'] = $month;
            } else {
                $timestamp = $product->created_at;
                $month = date_format($timestamp, 'M');
                $product['month'] = $month;
            }
            unset($product['factory_id'], $product['agent_id'], $product['warranty_count'],
            $product['warranty_id'], $product['status_id'], $product['batch_id'], 
            $product['depot_id'], $product['owner_id'], $product['customer_id'], $product['created_at'],
            $product['updated_at'], $product['customer_buy_time']);
        }
    return $products;
    }

    public static function excel_export_product_by_quarter($products, $timeline = "") {
        foreach ($products as $product) {
            $product['status'] = $product->status->name;
            $product['rangeName'] = $product->batch->range->name;
            $product['rangeProperty'] = $product->batch->range->property;
            if (!empty($product->factory->user)) {
                $product['factory'] = $product->factory->user->name;
            }
            if (!empty($product->agent->user)) {
                $product['agent'] = $product->agent->user->name;
            } else {
                $product['agent'] = null;
            }
            
            if ($timeline == "customer_by_time") {
                $timestamp = $product->customer_buy_time;
                $month = date_format($timestamp, 'M');
                $product['month'] = $month;
            } else {
                $timestamp = $product->created_at;
                $month = date_format($timestamp, 'M');
                $product['month'] = $month;
            }

            unset($product['factory_id'], $product['agent_id'], $product['warranty_count'],
            $product['warranty_id'], $product['status_id'], $product['batch_id'], 
            $product['depot_id'], $product['owner_id'], $product['customer_id'], $product['created_at'],
            $product['updated_at'], $product['customer_buy_time']);
        }
    return $products;
    }

    public static function excel_export_product_by_year($products, $timeline = "") {
        foreach ($products as $product) {
            $product['status'] = $product->status->name;
            $product['rangeName'] = $product->batch->range->name;
            $product['rangeProperty'] = $product->batch->range->property;
            if (!empty($product->factory->user)) {
                $product['factory'] = $product->factory->user->name;
            }
            if (!empty($product->agent->user)) {
                $product['agent'] = $product->agent->user->name;
            } else {
                $product['agent'] = null;
            }
            
            if ($timeline == "customer_by_time") {
                $product['date'] = $product->customer_buy_time;
            } else {
                $product['date'] = $product->created_at;
            }
            
            unset($product['factory_id'], $product['agent_id'], $product['warranty_count'],
            $product['warranty_id'], $product['status_id'], $product['batch_id'], 
            $product['depot_id'], $product['owner_id'], $product['customer_id'], $product['created_at'],
            $product['updated_at'], $product['customer_buy_time']);
        }
    return $products;
    }
}
