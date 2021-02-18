<?php
namespace app\qingadmin\controller;
use app\BaseController;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
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
	public function test() {
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

}
