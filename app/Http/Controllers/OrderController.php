<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\GeneralTrait;
use App\Models\Order;
use Carbon\Carbon;

class OrderController extends Controller
{
    use GeneralTrait;

    public function saveOrder(Request $request) {

        $this->validate($request, [
            'order' => 'required|array',
            'order.*.product_id' => 'required|numeric',
            'order.*.user_id' => 'required|numeric',
            'order.*.total_sale_units' => 'required|numeric',
            'order.*.sale_price' => 'required|numeric',
            'order.*.vat' => 'numeric',
            'order.*.promo_code' => 'numeric',
            'order.*.delivery_charge' => 'numeric',
            'order.*.total_price' => 'required|numeric',
            'order.*.shipping_address' => 'required|string',
            'order.*.city' => 'required|string',
            'order.*.zipcode' => 'string',
            'order.*.refernce_phone_no' => 'numeric',
            'order.*.payment_option' => 'required|in:cod,digital',
            'order.*.status' => 'required|in:created,approved,processing,shipment,delivered',
        ]);

        $order = $request->get('order');
        $newOrder = $this->processingOrder($order);

        $data = new Order;
        $data->insert($newOrder);
        
        if($data)
            return $this->getResponse(200, 'success', 'Order created', null);
        return $this->getResponse(400, 'false', 'Something went wrong!', null);
    }

    public function processingOrder($order) {
        
        $code = 'OR-'.$this->uniqueNumber(5);
        $newOrder = array();

        foreach($order as $row) {
            $newData = array(
                'code' => $code,
                'product_id' => $row['product_id'],
                'user_id' => $row['user_id'],
                'total_sale_units' => $row['total_sale_units'],
                'sale_price' => $row['sale_price'],
                'vat' => isset($row['vat']) ? $row['vat'] : null,
                'promo_code' => isset($row['promo_code']) ? $row['promo_code'] : null,
                'delivery_charge' => isset($row['delivery_charge']) ? $row['delivery_charge'] : null,
                'total_price' =>  $row['total_price'],
                'shipping_address'=> isset($row['shipping_address']) ? $row['shipping_address'] : null,
                'city' => isset($row['city']) ? $row['city'] : null,
                'zipcode' => isset($row['zipcode']) ? $row['zipcode'] : null,
                'refernce_phone_no' => isset($row['refernce_phone_no']) ? $row['refernce_phone_no'] : null,
                'payment_option' => $row['payment_option'],
                'status' => $row['status'],
                'created_at' => carbon::now(),
                'updated_at' => carbon::now(),
                'deleted_at' => null
            );

            array_push($newOrder, $newData);
        }

        return $newOrder;
    }
}
