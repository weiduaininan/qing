<?php
namespace app\qingadmin\controller;

use think\facade\Db;

class Number extends Base {

	//列表
	public function order() {
		return view();
	}

	public function getOrder() {
		for ($i = 0; $i < 12; $i++) {
			if ($i == 0) {
				$time12_start = strtotime(date('Y-m-01', strtotime("-" . $i . 'month')));
				$time12_end = strtotime(date('Y-m-t', strtotime("-" . $i . 'month')));
				$date12 = date('Ym', strtotime("-" . $i . ' month'));
			}
			if ($i == 1) {
				$time11_start = strtotime(date('Y-m-01', strtotime("-" . $i . 'month')));
				$time11_end = strtotime(date('Y-m-t', strtotime("-" . $i . 'month')));
				$date11 = date('Ym', strtotime("-" . $i . ' month'));
			}
			if ($i == 2) {
				$time10_start = strtotime(date('Y-m-01', strtotime("-" . $i . 'month')));
				$time10_end = strtotime(date('Y-m-t', strtotime("-" . $i . 'month')));
				$date10 = date('Ym', strtotime("-" . $i . ' month'));
			}
			if ($i == 3) {
				$time9_start = strtotime(date('Y-m-01', strtotime("-" . $i . 'month')));
				$time9_end = strtotime(date('Y-m-t', strtotime("-" . $i . 'month')));
				$date9 = date('Ym', strtotime("-" . $i . ' month'));
			}
			if ($i == 4) {
				$time8_start = strtotime(date('Y-m-01', strtotime("-" . $i . 'month')));
				$time8_end = strtotime(date('Y-m-t', strtotime("-" . $i . 'month')));
				$date8 = date('Ym', strtotime("-" . $i . ' month'));
			}
			if ($i == 5) {
				$time7_start = strtotime(date('Y-m-01', strtotime("-" . $i . 'month')));
				$time7_end = strtotime(date('Y-m-t', strtotime("-" . $i . 'month')));
				$date7 = date('Ym', strtotime("-" . $i . ' month'));
			}
			if ($i == 6) {
				$time6_start = strtotime(date('Y-m-01', strtotime("-" . $i . 'month')));
				$time6_end = strtotime(date('Y-m-t', strtotime("-" . $i . 'month')));
				$date6 = date('Ym', strtotime("-" . $i . ' month'));
			}
			if ($i == 7) {
				$time5_start = strtotime(date('Y-m-01', strtotime("-" . $i . 'month')));
				$time5_end = strtotime(date('Y-m-t', strtotime("-" . $i . 'month')));
				$date5 = date('Ym', strtotime("-" . $i . ' month'));
			}
			if ($i == 8) {
				$time4_start = strtotime(date('Y-m-01', strtotime("-" . $i . 'month')));
				$time4_end = strtotime(date('Y-m-t', strtotime("-" . $i . 'month')));
				$date4 = date('Ym', strtotime("-" . $i . ' month'));
			}
			if ($i == 9) {
				$time3_start = strtotime(date('Y-m-01', strtotime("-" . $i . 'month')));
				$time3_end = strtotime(date('Y-m-t', strtotime("-" . $i . 'month')));
				$date3 = date('Ym', strtotime("-" . $i . ' month'));
			}
			if ($i == 10) {
				$time2_start = strtotime(date('Y-m-01', strtotime("-" . $i . 'month')));
				$time2_end = strtotime(date('Y-m-t', strtotime("-" . $i . 'month')));
				$date2 = date('Ym', strtotime("-" . $i . ' month'));
			}
			if ($i == 11) {
				$time1_start = strtotime(date('Y-m-01', strtotime("-" . $i . 'month')));
				$time1_end = strtotime(date('Y-m-t', strtotime("-" . $i . 'month')));
				$date1 = date('Ym', strtotime("-" . $i . ' month'));
			}

		}

		$where[] = ['status', '<>', 0];
		$number1 = Db::name('order')->whereBetween('time', [$time1_start, $time1_end])->where($where)->sum('total_price');
		$number2 = Db::name('order')->whereBetween('time', [$time2_start, $time2_end])->where($where)->sum('total_price');
		$number3 = Db::name('order')->whereBetween('time', [$time3_start, $time3_end])->where($where)->sum('total_price');
		$number4 = Db::name('order')->whereBetween('time', [$time4_start, $time4_end])->where($where)->sum('total_price');
		$number5 = Db::name('order')->whereBetween('time', [$time5_start, $time5_end])->where($where)->sum('total_price');
		$number6 = Db::name('order')->whereBetween('time', [$time6_start, $time6_end])->where($where)->sum('total_price');
		$number7 = Db::name('order')->whereBetween('time', [$time7_start, $time7_end])->where($where)->sum('total_price');
		$number8 = Db::name('order')->whereBetween('time', [$time8_start, $time8_end])->where($where)->sum('total_price');
		$number9 = Db::name('order')->whereBetween('time', [$time9_start, $time9_end])->where($where)->sum('total_price');
		$number10 = Db::name('order')->whereBetween('time', [$time10_start, $time10_end])->where($where)->sum('total_price');
		$number11 = Db::name('order')->whereBetween('time', [$time11_start, $time11_end])->where($where)->sum('total_price');
		$number12 = Db::name('order')->whereBetween('time', [$time12_start, $time12_end])->where($where)->sum('total_price');

		$number = [$number1, $number2, $number3, $number4, $number5, $number6, $number7, $number8, $number9, $number10, $number11, $number12];
		$date = [$date1, $date2, $date3, $date4, $date5, $date6, $date7, $date8, $date9, $date10, $date11, $date12];
		return json(['number' => $number, 'date' => $date]);
	}

}
