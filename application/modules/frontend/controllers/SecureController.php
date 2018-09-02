<?php
namespace Karts\Frontend\Controllers;

use \Karts\Frontend\Models;
use Phalcon\Db\Column as Column;

class SecureController extends ModuleController
{
	public function echoAction()
	{
		try {
			$this->view->setRenderLevel(\Phalcon\Mvc\View::LEVEL_NO_RENDER);
			/*
				$plain_text = 'The quick brown fox jumps over the lazy dog.';
				echo "Plain-Text: {$plain_text}\nEncrypt-String: ", bin2hex($this->crypto->encrypt($plain_text)), "\n", str_repeat('-', 50), "\n";
				echo "Plain-Text: MyPassword\nHash-Value: ", $this->secure->hash('MyPassword'), "\n", str_repeat('-', 50), "\n";
			*/
			echo 'uniqid: ', sprintf('%s-%s', strtoupper(base_convert(date('Ymd'), 10, 36)), strtoupper(base_convert(uniqid(), 16, 36))), "\n", str_repeat('-', 50), "\n";
			echo "Snowflake - Distributed Unique Id Generator:\n";
			for ($i = 0; $i < 100; $i++) {
				echo atom_next_id(), "\n";
			}
			//echo date('YmdHis').rand(100000,999999);
			//echo strtoupper(base_convert(20150517182345285637, 10, 16));
			/*
			$s = new Settlements();
			$s->id = date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
			$s->publisher_id = 10000;
			$s->amount = 2144.00;
			$s->commision = 960.00;
			var_dump($s);
			if (!$s->save()) {
				foreach ($s->getMessages() as $message) {
					$this->log($message, false);
				}
			}
			*/
			/*
			$order = new Orders();
			$order->id = date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
			$order->publisher_id = 10000;
			$order->to = '隔壁老王';
			$order->telephone = '138' . mt_rand(10000000, 99999999);
			$order->province = '2100000000';
			$order->city = '2101000000';
			$order->district = '2101040000';
			$order->address = '收货地址';
			$order->remarks = '';
			$order->ip = sprintf('%u', ip2long('127.0.0.1'));
			$order->product_id = 100;
			$order->program = json_encode(["description"=>"备用装买2送1共3条","price"=>"536.00","commision"=>"240.00"]);
			$order->ua = 'N/A';
			if (!$order->save()) {
				foreach ($order->getMessages() as $message) {
					$this->log($message, false);
				}
			}
			$color = new Options();
			$size = new Options();
			
			$color->order_id = $order->id;
			$size->order_id = $order->id;
			
			$color->spec = 'COLOR';
			$color->value = 'BLACK';
			
			$size->spec = 'SIZE';
			$size->value = ['XL','XXL','XXL'][mt_rand(0, 2)];
			
			$size->save();
			$color->save();
			
			$process = new Processes();
			$process->order_id = $order->id;
			$process->save();
			*/
			//echo $this->security->getTokenKey();
			//echo $this->security->getToken();
		}
		catch (Exception $e) {
			echo $e->getMessage();
		}
	}
	
	public function captchaAction()
	{
		header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
		header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
		header('Cache-Control: no-store, no-cache, must-revalidate');
		header('Cache-Control: post-check=0, pre-check=0', false );
		header('Pragma: no-cache');
		
		$charset = 'ABCDEFGHJKLMNPQRTUVWXYbcdefghjkmnpqruvwxy346789';
		$maxpos = strlen($charset) - 1;
		$captcha = '';
		$im = imagecreate(100, 35);
		imagefilledrectangle($im, 0, 0, 100, 35, imagecolorallocate($im, 225 - mt_rand(0, 50), 225 - mt_rand(0, 50), 225 - mt_rand(0, 50)));
		for ($i = 0; $i < 4; $i++) {
			$x = 100 / 4;
			$color = imagecolorallocate($im, mt_rand(0x00, 0x70), mt_rand(0x00, 0x70), mt_rand(0x00, 0x70));
			$captcha .= substr($charset, mt_rand(0, $maxpos), 1);
			if ($i == 0) {
				$left = mt_rand(6, 8);
			}
			else if ($i == 3)
			{
				$left = $x * $i + mt_rand(-2, 0);
			}
			else {
				$left = $x * $i + mt_rand(4, 8);
			}
			imagettftext($im, 18, mt_rand(-20, 20), $left, 35 / 1.4 + mt_rand(-2, 2), $color, APP_PATH . 'public/fonts/Envy.ttf', $captcha[$i]);
		}
		
		/*
		for ($i = 0; $i < 50; $i++) {
			$x1 = mt_rand(0, 99);
			$y1 = mt_rand(0, 34);
			$x2 = $x1 + mt_rand(-2, 2);
			$y2 = $y1 + mt_rand(-2, 2);
			imageline($im, $x1, $y1, $x2, $y2, mt_rand(0xcccccc, 0xffffff));
		}
		*/
		$t = strtolower($this->dispatcher->getParam('t'));
		$this->session->set("{$t}_captcha", $captcha);
		header('Content-type: image/png');
		imagepng($im);
	}
}
