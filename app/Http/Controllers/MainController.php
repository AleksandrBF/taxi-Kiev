<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MainController extends Controller
{
    public function index()
    {
        $title = 'Такси Киев';

        return view('main', compact('title'));
    }

    public function form(Request $request)
    {
        $title = 'Заявка';

        $data = false;
        if ($request->isMethod('post')) {
            $data = new \stdClass();
            $data->from = $request->post('from');
            $data->to = $request->post('to');
            $data->time = $request->post('time');
            $data->distance_km = round((intval($request->post('distance')) / 1000), 1);
            $data->distance_time = $request->post('distance_time');
            $data->price = $this->calculateSum($request)['str'];
        }

        return view('accept', compact('title', 'data'));
    }

    public function calculateSum(Request $request)
    {
        $distance_m = intval($request->post('distance'));
        if ($distance_m <= 0) {
            return false;
        }

        $distance_km = round(($distance_m / 1000), 1);

        $time = date('H:i');
        if (!empty($request->post('time')) && $request->post('time') !== 'На сейчас'){
            $time = $request->post('time');
        }
        $price = $this->timePriceCorrection($time);

        $result['str'] = ($distance_km * $price) . ' грн';

        return $result;
    }

    private function timePriceCorrection($time)
    {
        $price = 7;
        $timePrice = [
            [
                'from' => '8:00',
                'to' => '10:00',
                'price' => 10,
            ],
            [
                'from' => '17:00',
                'to' => '19:00',
                'price' => 8,
            ],
        ];

        if (!empty($timePrice)){
            $time_s = strtotime($time);
            foreach ($timePrice as $item) {
                if ($time_s >= strtotime($item['from']) && $time_s <= strtotime($item['to'])) {
                    $price = $item['price'];
                }
            }
        }
        return $price;
    }
}
