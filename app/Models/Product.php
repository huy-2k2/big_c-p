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

    public static function create_notifi_transfer_product($title, $content, $user_id_to)
    {

        $notification = Notification::create([
            'title' => $title,
            'content' => $content
        ]);

        $notification->users()->attach($user_id_to);
        broadcast(new CreateNotifiEvent(['user_id' => $user_id_to, 'notification' => $notification, 'time' => $notification->created_at->toDateTimeString()]));
    }

    public static function transfer_product($user_id_from, $user_id_to, $product_id, $status_id_to, $note = null)
    {
        $name_from = User::find($user_id_from)->name;
        $name_to = User::find($user_id_to)->name;
        $product = (DB::table('products')->where('id', '=', $product_id)->first())->id;
        $product_detail = DB::table('products')->where('id', '=', $product_id)->first();
        $time_warranty = (DB::table('ranges')->where('id', '=', $product_detail->range_id)->first())->warranty_time;

        if ($status_id_to == 3) {
            /** ?????i l?? b??n h??ng cho ng?????i d??ng ho???c tr??? l???i s???n ph???m ???? b???o h??nh*/
            $message = 'nothing';
            if (!$note) {
                $message = 'Giao h??ng th??nh c??ng';
                $title = 'Th??ng b??o nh???n h??ng';
                $content = '?????i l?? ' . $name_from . ' ???? giao s???n ph???m ' . $product . ' cho b???n';

                self::create_notifi_transfer_product($title, $content, $user_id_to);

                DB::table('products')->where('id', '=', $product_id)
                    ->update([
                        'depot_id' => 1,
                        'status_id' => 3,
                        'customer_id' => $user_id_to,
                        'owner_id' => $user_id_to,
                        'customer_buy_time' => Carbon::now(),
                        'end_date' => Carbon::now()->addMonths($time_warranty),
                        'updated_at' => Carbon::now(),
                    ]);
            } else {
                if ($product_detail->warranty_count == 0) {
                    $message = 'Tr??? l???i ng?????i d??ng th??nh c??ng';
                    $title = 'Th??ng b??o nh???n s???n ph???m b???o h??nh';
                    $content = 'Do s???n ph???m c?? kh??ng th??? s???a ch???a ???????c n??n ?????i l??' . $name_from . ' ???? giao s???n ph???m m???i l?? ' . $product . ' cho b???n';

                    self::create_notifi_transfer_product($title, $content, $user_id_to);

                    DB::table('products')->where('id', '=', $product_id)
                        ->update([
                            'status_id' => 3,
                            'owner_id' => $user_id_to,
                            'updated_at' => Carbon::now(),
                        ]);
                } else {
                    $message = 'Tr??? l???i ng?????i d??ng th??nh c??ng';
                    $title = 'Th??ng b??o nh???n s???n ph???m b???o h??nh';
                    $content = '?????i l?? ' . $name_from . ' ???? b???o h??nh s???n ph???m ' . $product . 'th??nh c??ng v?? tr??? l???i cho b???n';

                    self::create_notifi_transfer_product($title, $content, $user_id_to);

                    DB::table('products')->where('id', '=', $product_id)
                        ->update([
                            'status_id' => 3,
                            'owner_id' => $user_id_to,
                            'updated_at' => Carbon::now(),
                        ]);
                }
            }

            return $message;
        } else if ($status_id_to == 4) {
            /**?????i l?? nh???n sp c???n b???o h??nh t??? ng?????i d??ng */
            if ($product_detail->out_of_warranty == 1) {
                return 'S???n ph???m h???t h???n b???o h??nh';
            }

            $title = 'Th??ng b??o b???o h??nh s???n ph???m';
            $content = 'Ng?????i mua ' . $name_from . ' ???? y??u c???u b???o h??nh s???n ph???m ' . $product . ' v???i l?? do: ' . $note['reason'] . '. Vui l??ng li??n h??? l???i v?? th???c hi???n b???o h??nh';
            $message = 'Y??u c???u b???o h??nh th??nh c??ng';

            self::create_notifi_transfer_product($title, $content, $user_id_to);

            DB::table('products')->where('id', '=', $product_id)
                ->update([
                    'status_id' => 4,
                    'owner_id' => $user_id_to,
                    'updated_at' => Carbon::now(),
                ]);

            DB::table('warranty_products')->insert([
                'product_id' => $product_id,
                'status' => 0,
                'reason' => $note['reason'],
                'product_error_id' => $note['error_id'],
                'created_at' => Carbon::now(),
                'batch_id' => $note['batch_id']
            ]);

            return $message;
        } else if ($status_id_to == 5) {
            /**?????i l?? chuy???n sp ?????n ttbh */
            $title = 'Th??ng b??o b???o h??nh s???n ph???m';
            $content = '?????i l?? ' . $name_from . ' ???? y??u c???u b???o h??nh s???n ph???m ' . $product  . '. Vui l??ng th???c hi???n b???o h??nh';
            $message = 'Chuy???n ?????n trung t??m b???o h??nh th??nh c??ng';

            self::create_notifi_transfer_product($title, $content, $user_id_to);

            DB::table('products')->where('id', '=', $product_id)
                ->update([
                    'warranty_id' => $user_id_to,
                    'status_id' => 5,
                    'owner_id' => $user_id_to,
                    'updated_at' => Carbon::now(),
                ]);

            return $message;
        } else if ($status_id_to == 7) {
            /**?????i l?? nh???n s???n ph???m s???a xong t??? trung t??m b???o h??nh */
            $title = 'Th??ng b??o b???o h??nh s???n ph???m th??nh c??ng';
            $content = 'Trung t??m b???o h??nh ' . $name_from . ' ???? b???o h??nh th??nh c??ng s???n ph???m ' . $product  . '. Vui l??ng ki???m tra v?? giao l???i cho kh??ch h??ng';
            $message = 'B???o h??nh th??nh c??ng';

            self::create_notifi_transfer_product($title, $content, $user_id_to);

            $current_warranty_count = (DB::table('products')->where('id', '=', $product_id)->first())->warranty_count;
            DB::table('products')->where('id', '=', $product_id)
                ->update([
                    'status_id' => 7,
                    'owner_id' => $user_id_to,
                    'warranty_count' => $current_warranty_count + 1,
                    'updated_at' => Carbon::now(),
                ]);

            DB::table('warranty_products')->where('product_id', '=', $product_id)
                ->update([
                    'status' => 1,
                    'updated_at' => Carbon::now(),
                ]);

            return $message;
        } else if ($status_id_to == 8) {
            /**TTBh chuy???n sp ko s???a ???????c ?????n c?? s??? s???n xu???t */
            $new_product_id = DB::table('products')->where([
                ['factory_id', '=', $product_detail->factory_id],
                ['range_id', '=', $product_detail->range_id],
                ['status_id', '=', 2]
            ])
                ->first();

            if (!$new_product_id) {
                /**Th??ng b??o ?????n c?? s??? s???n xu???t */
                $title1 = 'Th??ng b??o c?? s???n ph???m h???ng nh??ng kh??ng th??? s???a v?? nh?? m??y c??ng h???t';
                $content1 = 'Trung t??m b???o h??nh ' . $name_from . ' ???? b??o s???n ph???m ' . $product  . ' kh??ng th??? s???a ch???a. Nh?? m??y ch??a c?? s???n ph???m m???i, vui l??ng ch???';

                /**Th??ng b??o ?????n ?????i l?? */
                $title2 = 'Th??ng b??o c?? s???n ph???m h???ng nh??ng kh??ng th??? s???a';
                $content2 = 'Trung t??m b???o h??nh ' . $name_from . ' ???? b??o s???n ph???m ' . $product  . ' kh??ng th??? s???a ch???a. Nh?? m??y ch??a c?? s???n ph???m m???i, vui l??ng ch???';

                self::create_notifi_transfer_product($title1, $content1, $user_id_to);
                self::create_notifi_transfer_product($title2, $content2, $product_detail->agent_id);
                $message = 'Chuy???n v??? c?? s??? s???n xu???t th???t b???i';

                return $message;
            }

            /**Th??ng b??o ?????n c?? s??? s???n xu???t */
            $title1 = 'Th??ng b??o c?? s???n ph???m h???ng nh??ng kh??ng th??? s???a';
            $content1 = 'Trung t??m b???o h??nh ' . $name_from . ' ???? b??o s???n ph???m ' . $product  . ' kh??ng th??? s???a ch???a. ???? t??? ?????ng cung c???p cho ?????i l?? s???n ph???m m???i';

            /**Th??ng b??o ?????n ?????i l?? */
            $title2 = 'Th??ng b??o c?? s???n ph???m h???ng nh??ng kh??ng th??? s???a';
            $content2 = 'Trung t??m b???o h??nh ' . $name_from . ' ???? b??o s???n ph???m ' . $product  . ' kh??ng th??? s???a ch???a. V?? nh?? m??y ???? cung c???p cho ?????i l?? s???n ph???m m???i. Vui l??ng ki???m tra v?? chuy???n l???i cho ng?????i d??ng';

            self::create_notifi_transfer_product($title1, $content1, $user_id_to);
            self::create_notifi_transfer_product($title2, $content2, $product_detail->agent_id);
            $message = 'Chuy???n v??? c?? s??? s???n xu???t th??nh c??ng';

            DB::table('products')->where('id', '=', $new_product_id->id)
                ->update([
                    'depot_id' => 1,
                    'agent_id' => $product_detail->agent_id,
                    'customer_id' => $product_detail->customer_id,
                    'status_id' => 7,
                    'warranty_id' => $product_detail->warranty_id,
                    'owner_id' => $product_detail->agent_id,
                    'customer_buy_time' => Carbon::now(),
                    'end_date' => Carbon::now()->addMonths($time_warranty),
                    'updated_at' => Carbon::now(),
                ]);

            DB::table('products')->where('id', '=', $product_id)
                ->update([
                    'customer_id' => null,
                    'status_id' => 8,
                    'owner_id' => $user_id_to,
                    'customer_buy_time' => null,
                    'updated_at' => Carbon::now(),
                ]);

            DB::table('warranty_products')->where('product_id', '=', $product_id)->delete();

            return $message;
        } else if ($status_id_to == 6) {
            /**Sp c???n tri???u h???i */
            $message = 'Thu h???i th??nh c??ng';
            DB::table('products')->where('id', '=', $product_id)
                ->update([
                    'is_recall' => 1,
                    'updated_at' => Carbon::now(),
                ]);

            return $message;
        }
    }

    public static function check_product_exist($product_id)
    {
        $check = DB::table('products')->where('id', $product_id)->first();
        if ($check) {
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

    public function range() {
        return $this->belongsTo(Range::class);
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

    public static function getRangeName($products) {
        foreach($products as $product) {
            $product['name'] = $product->range->name;
            unset($product['factory_id'], $product['agent_id'], $product['warranty_count'],
            $product['warranty_id'], $product['status_id'], $product['batch_id'], 
            $product['depot_id'], $product['owner_id'], $product['customer_id'], $product['created_at'],
            $product['updated_at'], $product['customer_buy_time'], $product['range_id'], $product['out_of_warranty'],
            $product['end_date'], $product['is_recall']);
        }
        return $products;
    }

    public static function getAgentName($products) {
        foreach($products as $product) {
            $product['name'] = $product->agent->user->name;
            unset($product['factory_id'], $product['agent_id'], $product['warranty_count'],
            $product['warranty_id'], $product['status_id'], $product['batch_id'], 
            $product['depot_id'], $product['owner_id'], $product['customer_id'], $product['created_at'],
            $product['updated_at'], $product['customer_buy_time'], $product['range_id'], $product['out_of_warranty'],
            $product['end_date'], $product['is_recall']);
        }
        return $products;
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
            $product['updated_at'], $product['customer_buy_time'], $product['range_id'], $product['out_of_warranty'],
            $product['end_date'], $product['is_recall']);
        }
        return $products;
    }

    public static function excel_export_product_by_month($products, $timeline = "")
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
            $product['updated_at'], $product['customer_buy_time'], $product['range_id'], $product['out_of_warranty'],
            $product['end_date'], $product['is_recall']);
        }
        return $products;
    }

    public static function excel_export_product_by_quarter($products, $timeline = "")
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
            $product['updated_at'], $product['customer_buy_time'], $product['range_id'], $product['out_of_warranty'],
            $product['end_date'], $product['is_recall']);
        }
        return $products;
    }

    public static function excel_export_product_by_year($products, $timeline = "")
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
            $product['updated_at'], $product['customer_buy_time'], $product['range_id'], $product['out_of_warranty'],
            $product['end_date'], $product['is_recall']);
        }
        return $products;
    }

    public static function products($warrantyProducts) {
        $products = [];
        foreach ($warrantyProducts as $warrantyProduct) {
            if ($warrantyProduct->range_id != null)
            {
                $product = $warrantyProduct->range_id;
                array_push($products, $product);
            }
        }
        return $products;
    }

    public static function excel_export_defective($products) {
        $list_products = [];
        foreach ($products as $modelProduct) 
        {
            $product = $modelProduct[0];
            unset($product['factory_id'], $product['agent_id'], $product['warranty_count'],
            $product['warranty_id'], $product['status_id'], $product['batch_id'], 
            $product['depot_id'], $product['owner_id'], $product['customer_id'], $product['created_at'],
            $product['updated_at'], $product['customer_buy_time'], $product['range_id'], $product['out_of_warranty'],
            $product['end_date'], $product['is_recall'], $product['id']);
            $list_products[] = $product;
        }
        return $list_products;
    }

    public static function getProductFactory($warrantyProducts) 
    {
        $products = [];
        foreach ($warrantyProducts as $warrantyProduct) {
            if ($warrantyProduct->factory != null)
            {
                $product = $warrantyProduct->factory->user_id;
                array_push($products, $product);
            }
        }
        return $products;
    }

    public static function getProductAgent($warrantyProducts) 
    {
        $products = [];
        foreach ($warrantyProducts as $warrantyProduct) {
            if ($warrantyProduct->agent != null)
            {
                $product = $warrantyProduct->agent->user_id;
                array_push($products, $product);
            }
        }
        return $products;
    }
}
