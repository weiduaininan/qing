<?php
namespace app\qingadmin\controller;
use app\BaseController;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use think\facade\Db;
use think\facade\Log;

//引入文件

/**
 * 每日发送日志脚本
 */
class Scripts extends BaseController {
	public function __construct() {
		$cli = Request()->isCli();
		if (!$cli) {
			//echo "请在命令行下运行";
			//die;
		}
	}
	public function index() {
		sendMail('bj_liunan@126.com', '测试邮箱发送', '内容', '/Users/liunan/htdocs/qing/public/static/index/images/ad1.jpg', 'ad1.jpg');
		$status = sendMail('bj_liunan@126.com', '测试邮箱发送', '内容', '/Users/liunan/htdocs/qing/public/static/index/css/cart.css', 'cart.css');
		if ($status) {
			Log::write('邮件发送成功', 'mail');
		}
	}

	public function excel_out() {
		$data = [
			[1, 'jack', 10],
			[2, 'mike', 12],
			[3, 'jane', 21],
			[4, 'paul', 26],
			[5, 'kitty', 25],
			[6, 'yami', 60],
		];

		$title = ['id', 'name', 'age'];

		$spreadsheet = new Spreadsheet();
		$worksheet = $spreadsheet->getActiveSheet();

//设置工作表标题名称
		$worksheet->setTitle('工作表格1');

//表头
		//设置单元格内容
		foreach ($title as $key => $value) {
			$worksheet->setCellValueByColumnAndRow($key + 1, 1, $value);
		}

		$row = 2; //从第二行开始
		foreach ($data as $item) {
			$column = 1;

			foreach ($item as $value) {
				$worksheet->setCellValueByColumnAndRow($column, $row, $value);
				$column++;
			}
			$row++;
		}

		$fileName = '学生信息';
		$fileType = 'Xlsx';

		//1.下载到服务器
		$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
		$writer->save($fileName . '.' . $fileType);

		//2.输出到浏览器
		$writer = IOFactory::createWriter($spreadsheet, 'Xlsx'); //按照指定格式生成Excel文件
		$this->excelBrowserExport($fileName, 'Xlsx');
		$writer->save('php://output');
	}
	/**
	 * 输出到浏览器(需要设置header头)
	 * @param string $fileName 文件名
	 * @param string $fileType 文件类型
	 */
	public function excelBrowserExport($fileName, $fileType) {
		//文件名称校验
		if (!$fileName) {
			trigger_error('文件名不能为空', E_USER_ERROR);
		}

		//Excel文件类型校验
		$type = ['Excel2007', 'Xlsx', 'Excel5', 'xls'];
		if (!in_array($fileType, $type)) {
			trigger_error('未知文件类型', E_USER_ERROR);
		}

		if ($fileType == 'Excel2007' || $fileType == 'Xlsx') {
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="' . $fileName . '.xlsx"');
			header('Cache-Control: max-age=0');
		} else {
			//Excel5
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="' . $fileName . '.xls"');
			header('Cache-Control: max-age=0');
		}
	}

/************************其他导出方式******************************************************/

	//导出部分数据  需要传入 时间戳
	public function excel_some_select() {
		$start_time0 = $end_time0 = '';
		$start_time0 = input('get.start_time');
		$end_time0 = input('get.end_time');

		$search_key = input('request.search_key') ? input('request.search_key') : '';

		//2022-01-02 时间戳
		$start_time = strtotime($start_time0);
		$end_time = strtotime($end_time0);

		$where = [];

		if ($start_time && $end_time) {
			$where[] = [
				['time', 'between', [$start_time, $end_time]],
			];
		} elseif ($start_time) {
			$where[] = [
				['time', '>', $start_time],
			];
		} elseif ($end_time) {
			$where[] = [
				['time', '<', $end_time],
			];
		}

		$orderData = Db::name('order')->where('shou_mobile', 'like', '%' . $search_key . '%')->where($where)->order('id desc')->paginate(['list_rows' => 10, 'query' => request()->param()]);

		$orderData1 = Db::name('order')->where('shou_mobile', 'like', '%' . $search_key . '%')->where($where)->order('id desc')->select()->toArray();

		$this->excel_table($orderData);
	}

	//导出全部数据
	public function excel_all() {
		$orderData = Db::name('order')->select()->toArray();
		$this->excel_table($orderData);
	}

	//会员公共导出数据
	public function excel_table($orderData) {
		if (empty($orderData)) {
			return alert('导出数据为空', 'index', 5);
		}

		$table = '';
		foreach ($orderData as $k => $v) {
			$orderData[$k]['mobile'] = Db::name('user')->where('id', $v['user_id'])->value('mobile');
			if ($v['pay_method'] == 1) {
				$orderData[$k]['pay_method'] = '微信支付';
			} else {
				$orderData[$k]['pay_method'] = '支付宝支付';
			}
			$orderData[$k]['time'] = date('Y-m-d H:i', $v['time']);
			$orderData[$k]['express_code'] = Db::name('express')->where('code', $v['express_code'])->value('name');
			// if ($orderData[$k]['shou_time']) {
			// 	$orderData[$k]['shou_time'] = date('Y-m-d H:i', $v['shou_time']);
			// } else {
			// 	$orderData[$k]['shou_time'] = '没有收货';
			// }
			if ($orderData[$k]['pay_time']) {
				$orderData[$k]['pay_time'] = date('Y-m-d H:i', $v['pay_time']);
			} else {
				$orderData[$k]['pay_time'] = '没有支付';
			}

			if ($orderData[$k]['status'] == 0) {
				$orderData[$k]['status'] = '没有支付';
			} elseif ($orderData[$k]['status'] == 1) {
				$orderData[$k]['status'] = '待发货';
			} elseif ($orderData[$k]['status'] == 2) {
				$orderData[$k]['status'] = '已完成';
			} elseif ($orderData[$k]['status'] == 4) {
				$orderData[$k]['status'] = '待收货';
			} elseif ($orderData[$k]['status'] == 3) {
				$orderData[$k]['status'] = '退换货';
			}

		}
		$table .= "<table>
		<thead>
			<tr>
				<th class='name'>ID</th>
				<th class='name'>订单号</th>
				<th class='name'>会员手机号</th>
				<th class='name'>下单时间</th>
				<th class='name'>订单备注</th>
				<th class='name'>订单总价</th>
				<th class='name'>订单状态</th>
				<th class='name'>支付方式</th>
				<th class='name'>支付时间</th>
				<th class='name'>快递单号</th>
				<th class='name'>快递公司</th>
			</tr>
		</thead>
		<tbody>";
		foreach ($orderData as $v) {
			$table .= "<tr>
					<td class='name'>{$v['id']}</td>
					<td class='name'>{$v['out_trade_no']}</td>
					<td class='name'>{$v['mobile']}</td>
					<td class='name'>{$v['time']}</td>
					<td class='name'>{$v['content']}</td>
					<td class='name'>{$v['total_price']}</td>
					<td class='name'>{$v['status']}</td>
					<td class='name'>{$v['pay_method']}</td>
					<td class='name'>{$v['pay_time']}</td>
					<td class='name'>{$v['postcode']}</td>
					<td class='name'>{$v['express_code']}</td>
				</tr>";
		}
		$table .= "</tbody>
		</table>";

		//通过header头控制输出excel表格
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
		header("Content-Type:application/force-download");
		header("Content-Type:application/vnd.ms-execl");
		header("Content-Type:application/octet-stream");
		header("Content-Type:application/download");
		header('Content-Disposition:attachment;filename="用户表.xls"');
		header("Content-Transfer-Encoding:binary");
		echo $table;
	}
}
