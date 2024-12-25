<?php

class Ajax extends CI_Controller {

	public function index()
	{

		$this->load->helper('url');
		if($this->input->is_ajax_request()){

		}else {
			redirect("/");
		}
/*
		$reportid = $this->input->post('reportid');
		$param = $this->input->post('param');
		$reason = $this->input->post('reason');
		$gametitle = $this->input->post('gametitle');

		$this->load->library('myemail'); 

		$config['protocol'] = 'sendmail';
		$config['mailpath'] = '/usr/sbin/sendmail';
		$config['_encoding'] = '7bit';
		$config['charset'] = 'ISO-2022-JP';
		$config['wordwrap'] = FALSE;
		$this->myemail->initialize($config);

		$sbj = '通報がありました｜GPP';
		$from_name = mb_encode_mimeheader('GPP', "ISO-2022-JP", "UTF-8,EUC-JP,auto");
		$sbj = mb_encode_mimeheader($sbj, "ISO-2022-JP", "UTF-8,EUC-JP,auto");

		$honbun ='';
		$honbun.="ゲームタイトル：".$gametitle."\n";
		$honbun.="id：".$reportid."\n";
		$honbun.="コミュニティの種類：".$param."\n";
		$honbun.="報告理由：".$reason."\n";
		$honbun.="Copyright GPP All rights reserved\n";

		$honbun = mb_convert_encoding($honbun, "ISO-2022-JP", "UTF-8,EUC-JP,auto");

		$this->myemail->from('info@himatch.jp', $from_name);
		$this->myemail->to('info@himatch.jp');
		$this->myemail->subject($sbj);
		$this->myemail->message($honbun);
		$this->myemail->send();*/
	}

	public function bbs_reply()
	{
		$this->load->helper('url');
		if($this->input->is_ajax_request()){
		}else {
			redirect("/");
		}

		$id = $this->input->post('bbsid');
		$uid = $this->input->post('senderid');
		$reply = $this->input->post('reply');

		//2038年問題
		//$post_time = date('Y年n月j日 G時i分');
		$now = new DateTime("now");
		$post_time = $now->format('Y年n月j日 G時i分');
		$last_update = $now->format('YmdHis');
		$last_update = (int)$last_update;

		$this->load->model('Member');
		$usinfo = $this->Member->usinfo_get2($uid);

		$this->load->model('Comu');
		$bbsdata = $this->Comu->bbs_get2($id);
		$count = $bbsdata['reply_count'] + 1;
		//log_message('debug', '掲示板に返信しました');
		//log_message('debug', print_r($bbsdata, true));
		//log_message('debug', $bbsdata['reply_data']);
        $reply_word_count = mb_strlen($bbsdata['reply_data']);
        if(5 > $reply_word_count){
            $bbsdata['reply_data'] = ""; //nullだったら空白扱い
        }

		if(!empty($bbsdata['reply_data'])){
		//if($bbsdata['reply_count'] > 0){
			//DBにあるreply_dataを配列に
			$array_reply_data = json_decode($bbsdata['reply_data'], true);

			//新たな返信
			$new_array_reply_data = array
			(
				'id' => $usinfo['id'],				
				'name' => $usinfo['name'],
				'icon_name' => $usinfo['icon_name'],
				'price' => $usinfo['price'],
				'prefec' => $usinfo['prefec'],
                'identify' => $usinfo['identify_status'],		
				'message' => $reply,
				'count' => $count,
				'post_time' => $post_time,
                'deleted_flag' => $usinfo['deleted_flag']
			);
			//配列を合体
			array_push($array_reply_data,$new_array_reply_data);
			$json_replydata = json_encode($array_reply_data);
			//DB更新
			$this->Comu->reply_message_update($json_replydata,$count,$last_update,$id);
			//log_message('debug', print_r($array_reply_data, true));

			//画面描写
			$replydata = array
			(
				'id' => $usinfo['id'],				
				'name' => $usinfo['name'],
				'icon_name' => $usinfo['icon_name'],
				'price' => $usinfo['price'],
				'prefec' => $usinfo['prefec'],
                'identify' => $usinfo['identify_status'],
				'message' => $reply,
				'count' => $count,
				'post_time' => $post_time,
                'deleted_flag' => $usinfo['deleted_flag']
			);	

			//echo $replydata;
			echo json_encode($replydata);


		}else{ //返信件数がゼロ 最初の返信
/*
			$replydata = array
			(
				'id' => $usinfo['id'],				
				'name' => $usinfo['name'],
				'icon_name' => $usinfo['icon_name'],
				'message' => $reply
			);*/	
			
			$replydata = array
			(
				array
				(
					'id' => $usinfo['id'],				
					'name' => $usinfo['name'],
					'icon_name' => $usinfo['icon_name'],
					'price' => $usinfo['price'],
					'prefec' => $usinfo['prefec'],
                    'identify' => $usinfo['identify_status'],
					'message' => $reply,
					'post_time' => $post_time,
                    'deleted_flag' => $usinfo['deleted_flag']				
				)				
			);

			//カウントは最初の返信なので1
			//$count = 1;

			//jsonで保存
			$json_replydata = json_encode($replydata);

			//selializeで保存 文字列はこっちのが少ないがやめた
			//$serialize_replydata = serialize($replydata);

			//DB格納
			$this->Comu->reply_message_update($json_replydata,$count,$last_update,$id);

			$replydata = array
			(
				'id' => $usinfo['id'],				
				'name' => $usinfo['name'],
				'icon_name' => $usinfo['icon_name'],
				'price' => $usinfo['price'],
				'prefec' => $usinfo['prefec'],
                'identify' => $usinfo['identify_status'],
				'message' => $reply,
				'count' => $count,
				'post_time' => $post_time,
                'deleted_flag' => $usinfo['deleted_flag']
			);

			//echo $replydata;
			echo json_encode($replydata);
		}
	}

	//public function talkroom($talkroom_id)
    public function talkroom()
	{
		$this->load->helper('url');
		if($this->input->is_ajax_request()){
		}else{
			redirect("/");
		}

		$this->load->library('session');
		if($this->session->userdata('lgid') AND $this->session->userdata('lgmail') AND $this->session->userdata('lgpass')){
			$lgid = $this->session->userdata('lgid');
			$this->load->model('Member');
			$usinfo = $this->Member->usinfo_get($lgid);
		}else{
			redirect("user/login");
		}
		//Stripe
		$paymentMethodId = $this->input->post('p_id');
		$customerId = $this->input->post('customerid');
		$receiver_price = $this->input->post('receiver_price');
		$senderid = $this->input->post('senderid');		

		$execution_date = $this->input->post('execution_date');		
		$receiver_price = $this->input->post('receiver_price');
		$pay_per = $this->input->post('pay_per');
		$payment_method_now = $this->input->post('payment_method_now');		
		$usage_time = $this->input->post('usage_time');
		$expenses = $this->input->post('expenses');  

        $total_amount = ($receiver_price * $usage_time) + $expenses;
        /*
        $total_price_non_tax = $total_price;
        $total_price_tax = $total_price * 0.15;
        $total_price_tax = floor($total_price_tax);
        $total_price = $total_price_non_tax + $total_price_tax;
        $total_price = floor($total_price);*/

        //クレジットカード決済のみStripe処理
		if($payment_method_now != '銀行振込'){
            
            require 'vendor/autoload.php';
            \Stripe\Stripe::setApiKey('sk_test_51OxMuA2NouBXQI50wYtQTA1ljoIWw3UxfDUqWVjuDIaOxkIOFt8gULELLNI1QrfB0H5G00i4fzv68FWNWADjK2vK00Yxv57CNx');
            
			// 初回決済、顧客作成
			if ($paymentMethodId && !$customerId) {
				
				try {

					// 新しい顧客作成
					$customer = \Stripe\Customer::create([
						'email' => 'customer@example.com', 
						'name' => 'John Doe', 
						'description' => 'A new customer',
						'payment_method' => $paymentMethodId,
						'invoice_settings' => [
							'default_payment_method' => $paymentMethodId,
						],
					]);
					// Stripeが提供する決済フローを管理するためのオブジェクト作成
					$paymentIntent = \Stripe\PaymentIntent::create([
						'payment_method_types' => ['card'],
						'customer' =>  $customer->id,
						'amount' => $total_amount,
						'currency' => 'jpy',
						'payment_method' => $paymentMethodId,
						'confirmation_method' => 'manual',
						'confirm' => true,
					]);

					$customerId = $customer->id;

					// echo json_encode(['success' => true, 'customerId' => $customer->id, 'paymentIntent' => $paymentIntent->id]);
					//echo "Ok";
				} catch (\Stripe\Exception\CardException $e) {
    
					// Stripeが返すエラーコードを取得
					$errorCode = $e->getError()->code;

					if ($errorCode === 'expired_card') {
						echo "カードの有効期限が切れています。";
					} else {
						echo "決済エラー: " . $e->getError()->message;
					}
				}
			
			// 再利用決済、保存されたカード情報を利用した支払い
			} elseif ($customerId) {

				try {

				    // 顧客情報の取得
					$customer = \Stripe\Customer::retrieve($customerId);
					// デフォルトの支払い方法を取得
					$defaultPaymentMethod = $customer->invoice_settings->default_payment_method;
					if (!$defaultPaymentMethod) {
						echo "Break";
					}
					// Stripeが提供する決済フローを管理するためのオブジェクト作成
					$paymentIntent = \Stripe\PaymentIntent::create([
						'payment_method_types' => ['card'],
						'customer' =>  $customerId,
						'amount' => $total_amount,
						'currency' => 'jpy',
						'payment_method' => $defaultPaymentMethod,
						'confirmation_method' => 'manual',
						'confirm' => true,
					]);
					// echo json_encode(['success' => true, 'paymentIntent' => $paymentIntent->id]);
					//echo "Ok";
				} catch (\Stripe\Exception\CardException $e) {
    
					// Stripeが返すエラーコードを取得
					$errorCode = $e->getError()->code;

					if ($errorCode === 'expired_card') {
						echo "カードの有効期限が切れています。";
					} else {
						echo "決済エラー: " . $e->getError()->message;
					}
				}
			} else {
				echo "Break";
			}
		}

        //メッセージ送信
		$talkroom_id = $this->input->post('talkroomid');
		$senderid = $this->input->post('senderid');		
		$receiverid = $this->input->post('receiverid');
		$post_message = $this->input->post('message');
		$now = new DateTime("now");

		$post_time = $now->format('n月j日 G時i分');
		$post_time_for_compare = $now->format('YmdHis'); //14桁
		//log_message('debug', $post_time_for_db);
		$post_time_for_compare = (int)$post_time_for_compare;

        //予約完了
        $reserve_check = $this->input->post('reserve_check');/* Stripeで使うので上に
		$execution_date = $this->input->post('execution_date');		
		$receiver_price = $this->input->post('receiver_price');
		$pay_per = $this->input->post('pay_per');
		$payment_method_now = $this->input->post('payment_method_now');		
		$usage_time = $this->input->post('usage_time');
		$expenses = $this->input->post('expenses');        
*/
		if($reserve_check == 'yes'){
			$this->Comu->customer_id_update($senderid,$customerid);
		}

		$this->load->model('Comu');
		$talkroomdata = $this->Comu->talkroom_get($talkroom_id);

		if(!empty($talkroomdata['message'])){
			$turn = 'other';

			//DBにあるmessageを配列に
			$array_message = json_decode($talkroomdata['message'], true);

			//新たなメッセージ ※メッセージ送信者が受け手か、ログインユーザーかで分岐
            //予約完了 or メッセージ投稿
            if($reserve_check == 'yes'){
                $new_array_message = array
                (
                    'login_user_id' => $execution_date,				
                    'sender_id' => $receiver_price,
                    'receiver_id' => $pay_per,		
                    'message' => $payment_method_now,
                    'position' => $usage_time,					
                    'post_time' => $expenses	
                );
            }else{
                if($lgid == $senderid){
                    $new_array_message = array
                    (
                        'login_user_id' => $lgid,				
                        'sender_id' => $senderid,
                        'receiver_id' => $receiverid,		
                        'message' => $post_message,
                        'position' => 'right',					
                        'post_time' => $post_time	
                    );
                }else{
                    $new_array_message = array
                    (
                        'login_user_id' => $lgid,				
                        'sender_id' => $senderid,
                        'receiver_id' => $receiverid,
                        'icon_name' => $usinfo['icon_name'],			
                        'message' => $post_message,
                        'position' => 'left',					
                        'post_time' => $post_time
                    );
                }
            }

			//配列を合体
			array_push($array_message,$new_array_message);
			$json_message = json_encode($array_message);
			//DB更新
			$this->Comu->message_update($json_message,$post_message,$talkroom_id,$post_time_for_compare,$post_time);
			//log_message('debug', print_r($array_reply_data, true));

			//画面描写
            $uid = $receiverid;
            $receiver_info = $this->Member->usinfo_get2($uid);
            
            $member_id = $senderid;
            $sender_info = $this->Member->usinfo_get4($member_id);

			//クロスサイトスクリプティング対策
			$post_message = htmlspecialchars($post_message);

            if($reserve_check == 'yes'){ //予約完了画面を描写
                $total_price = ($receiver_price * $usage_time) + $expenses;
                $total_price_non_tax = $total_price; //合計金額(手数料なし)
                $total_price_tax = $total_price * 0.15;
                $total_price_tax = floor($total_price_tax);//(手数料)
                $total_price = $total_price_non_tax + $total_price_tax;
                $total_price = floor($total_price);//合計金額(手数料込み)
                
                $message = array
                (
                    'execution_date' => $execution_date,				
                    'receiver_price' => $receiver_price,
                    'pay_per' => $pay_per,		
                    'payment_method_now' => $payment_method_now,
                    'usage_time' => $usage_time,					
                    'expenses' => $expenses,
                    'total_price_non_tax' => $total_price_non_tax,
                    'total_price_tax' => $total_price_tax,				
                    'total_price' => $total_price,
                    'sender_name' => $sender_info['name'],				
                    'receiver_name' => $receiver_info['name']        
                );
            }else{ //メッセージ
                if($lgid == $senderid){
                    $message = array
                    (
                        'login_user_id' => $lgid,
                        'sender_id' => $senderid,
                        'message' => $post_message,
                        'turn' => $turn,
                        'post_time' => $post_time
                    );
                }else{
                    $message = array
                    (
                        'login_user_id' => $lgid,				
                        'sender_id' => $senderid,
                        'receiver_id' => $receiverid,
                        'icon_name' => $usinfo['icon_name'],			
                        'message' => $post_message,
                        'post_time' => $post_time
                    );
                }
            }

			echo json_encode($message);

		}else{ //メッセージがゼロ 最初のメッセージ			
			$message = array
			(
				array
				(
					'login_user_id' => $lgid,				
					'sender_id' => $senderid,
					'receiver_id' => $receiverid,
					'message' => $post_message,
					'position' => 'right',
					'post_time' => $post_time				
				)			
			);

			//カウントは最初の返信なので1
			$turn = 'first';

			//jsonで保存
			$json_message = json_encode($message);
			//DB格納
			$this->Comu->message_update($json_message,$post_message,$talkroom_id,$post_time_for_compare,$post_time);

			$post_message = htmlspecialchars($post_message);
			$message = array
			(
				'login_user_id' => $lgid,
				'message' => $post_message,
				'turn' => $turn,
				'post_time' => $post_time	
			);
			echo json_encode($message);
		}

		//メールで受け手に通知
		//メッセージを最大50文字取得
		//$post_message_for_mail = substr($post_message, 0, 50);
		$post_message_count = mb_strlen($post_message);
		$post_message_for_mail = mb_substr($post_message,0,50,'UTF-8'); //エラー解消

        if($reserve_check == 'yes'){
            //両方に
            $address1 = $receiver_info['mail'];
            $setting1 = $receiver_info['message_mail'];
            $sender_name = $sender_info['name'];

            $address2 = $sender_info['mail'];
            $setting2 = $sender_info['message_mail'];

            $this->load->library('myemail'); 

            $config['protocol'] = 'sendmail';
            $config['mailpath'] = '/usr/sbin/sendmail';
            $config['_encoding'] = '7bit';
            $config['charset'] = 'ISO-2022-JP';
            $config['wordwrap'] = FALSE;
            $this->myemail->initialize($config);

            $sbj = '[ひまっち]予約完了のお知らせ';
            //$sbj = '[ひまっち]メッセージが届きました';
            $from_name = mb_encode_mimeheader('ひまっち', "ISO-2022-JP", "UTF-8,EUC-JP,auto");
            $sbj = mb_encode_mimeheader($sbj, "ISO-2022-JP", "UTF-8,EUC-JP,auto");

            $honbun ='';
            //$honbun.=$receiver_name." 様\n\n";
            $honbun.="いつもひまっちをご利用いただき、誠にありがとうございます。\n";
            $honbun.="現在、交渉中の取引に関しまして、".$sender_name."様が予約を完了されましたので、お知らせいたします。\n\n";
            $honbun.="実行日は、".$execution_date."となります。\n\n";
            $honbun.="詳細はトークルームに記載しておりますので、一度ご確認ください。\n";
            $honbun.="よろしくお願いいたします。\n\n";
            $honbun.="-----\n";
            $honbun.="ひまっち運営チーム\n";
            $honbun.="ひまっち - https://himatch.jp/\n";

            $honbun = mb_convert_encoding($honbun, "ISO-2022-JP", "UTF-8,EUC-JP,auto");
            $this->myemail->from('info@himatch.jp', $from_name);

            if($setting1 == 'on' and $setting2 == 'on'){
                $to = array(
                    $address1,
                    $address2,
                );
                $this->myemail->to($to);
            }elseif($setting1 == 'on' and $setting2 == 'off'){
                $this->myemail->to($address1);    
            }elseif($setting1 == 'off' and $setting2 == 'on'){
                $this->myemail->to($address2);
            }else{

            }
            $this->myemail->subject($sbj);
            $this->myemail->message($honbun);
            $this->myemail->send();
        }else{
    		//送られた方にだけ届く ※ログインユーザーでない方
            if($lgid == $senderid){
                $sender_name = $sender_info['name'];
                $receiver_name = $receiver_info['name'];
                $address = $receiver_info['mail'];
                $setting = $receiver_info['message_mail'];
            }else{
                $sender_name = $receiver_info['name'];
                $receiver_name = $sender_info['name'];
                $address = $sender_info['mail'];
                $setting = $sender_info['message_mail'];
            }

            if($setting == 'on'){ //設定でメール通知がしてある
                $this->load->library('myemail'); 

                $config['protocol'] = 'sendmail';
                $config['mailpath'] = '/usr/sbin/sendmail';
                $config['_encoding'] = '7bit';
                $config['charset'] = 'ISO-2022-JP';
                $config['wordwrap'] = FALSE;
                $this->myemail->initialize($config);

                $sbj = '[ひまっち]メッセージが届きました';
                //$sbj = '[ひまっち]メッセージが届きました';
                $from_name = mb_encode_mimeheader('ひまっち', "ISO-2022-JP", "UTF-8,EUC-JP,auto");
                $sbj = mb_encode_mimeheader($sbj, "ISO-2022-JP", "UTF-8,EUC-JP,auto");

                $honbun ='';
                $honbun.=$receiver_name." 様\n\n";
                $honbun.="いつもひまっちをご利用いただきまして、誠にありがとうございます。\n";
                $honbun.=$sender_name."さんからメッセージが届きました。\n\n";
                if($post_message_count > 50){ //よさそう
                    $honbun.=$post_message_for_mail."…\n\n";
                }else{
                    $honbun.=$post_message_for_mail."\n\n";
                }
                $honbun.="必要に応じて、ご返信をお願いいたします。\n\n";
                $honbun.="-----\n";
                $honbun.="ひまっち運営チーム\n";
                $honbun.="ひまっち - https://himatch.jp/\n";
                //mb_language('Ja');
                //$honbun = mb_convert_encoding($honbun, 'UTF-8','ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN,SJIS');
                $honbun = mb_convert_encoding($honbun, "ISO-2022-JP", "UTF-8,EUC-JP,auto");
                $this->myemail->from('info@himatch.jp', $from_name);
                $this->myemail->to($address);
                //$this->myemail->to('scsfrn36911@nifty.com, tom2p3f@yahoo.co.jp');
                $this->myemail->subject($sbj);
                $this->myemail->message($honbun);
                $this->myemail->send();
            }        
        }
	}

	public function change_bbs_status()
	{
		$this->load->helper('url');
		if($this->input->is_ajax_request()){
		}else {
			redirect("/");
		}
		//$status = $this->input->post('status');
		$id = $this->input->post('bbsid');

		$this->load->model('Member');
		$this->load->model('Comu');

		$bbs_data = $this->Comu->bbs_get2($id);
		$status = $bbs_data['status'];

		if($status > 0){ //募集停止していたら、募集中に
			//DB update
			$status = 0;
			$this->Comu->bbs_status_update($id,$status);	

		}else{ //募集中なら、募集を停止する
			//DB update
			$status = 1;
			$this->Comu->bbs_status_update($id,$status);	
		}

		$bbs_data_array = array
		(
			'bbsid' => $id,
			'status' => $status
		);

		echo json_encode($bbs_data_array);
	}

	public function add_to_favorite()
	{
		$this->load->helper('url');
		if($this->input->is_ajax_request()){
		}else {
			redirect("/");
		}

		$lgid = $this->input->post('userid');
		$uid = $this->input->post('memberid');

		//お気に入りがあるかないか　なければ配列作成　あれば追加
		$this->load->model('Member');
		$usinfo = $this->Member->usinfo_get($lgid);
		$memberinfo = $this->Member->usinfo_get2($uid);

		//フォローするユーザーのフォロー追加処理
		if(!empty($usinfo['follow'])){
			//DBにあるfollowを配列に
			$array_follow_list = json_decode($usinfo['follow'], true);
			//新たにフォロワーを追加
			array_push($array_follow_list,$uid);
			$json_follow_list = json_encode($array_follow_list);			
			//DB更新  whereで自身のidを条件とし、followした会員のidを追加する
			$this->Member->follow_update($json_follow_list,$lgid);
			//log_message('debug', print_r($array_reply_data, true));
		}else{ //いいねがゼロ 最初の返信
			//新規配列作成
			$follow_list = array($uid);
			//jsonで保存
			$json_follow_list = json_encode($follow_list);
			//DB格納 whereで自身のidを条件とし、followした会員のidを入れる
			$this->Member->follow_update($json_follow_list,$lgid);
		}

		//フォローされる会員のフォロワー追加処理
		if(!empty($memberinfo['follower'])){
			$array_follower_list = json_decode($memberinfo['follower'], true);
			array_push($array_follower_list,$lgid);
			$json_follower_list = json_encode($array_follower_list);
			$this->Member->follower_update($json_follower_list,$uid);
		}else{	
			$follower_list = array($lgid);
			$json_follower_list = json_encode($follower_list);
			$this->Member->follower_update($json_follower_list,$uid);	
		}

		//フォロワー数を別カラムに
		$memberinfo = $this->Member->usinfo_get2($uid);
		$array_follower_list = json_decode($memberinfo['follower'], true);
		$follower_count = count($array_follower_list);		
		$this->Member->follower_count_update($follower_count,$uid);
	}

	public function cancel_favorite()
	{
		$this->load->helper('url');
		if($this->input->is_ajax_request()){
		}else {
			redirect("/");
		}

		$lgid = $this->input->post('userid');
		$uid = $this->input->post('memberid');
		$memberid = $uid;

		$this->load->model('Member');
		$usinfo = $this->Member->usinfo_get($lgid);
		$memberinfo = $this->Member->usinfo_get2($uid);

		//フォローしていた会員の処理
		$array_follow_list = json_decode($usinfo['follow'], true);
		$uid = array($uid);
		//フォロー解除
		$array_follow_list = array_diff($array_follow_list, $uid);
		//indexを詰める
		$array_follow_list = array_values($array_follow_list);
		$count = count($array_follow_list);

		if($count > 0){
			$json_follow_list = json_encode($array_follow_list);
		}else{ //0になった場合
			$json_follow_list = '';
		}
		//DB更新
		$this->Member->follow_update($json_follow_list,$lgid);

		//フォローされていた会員の処理
		$array_follower_list = json_decode($memberinfo['follower'], true);
		$lgid = array($lgid);
		$array_follower_list = array_diff($array_follower_list, $lgid);
		$array_follower_list = array_values($array_follower_list);		
		$count = count($array_follower_list);

		if($count > 0){
			$json_follower_list = json_encode($array_follower_list);
		}else{ //0になった場合
			$json_follower_list = '';
		}
		$uid = $memberid; //上で配列になっているので直す
		$this->Member->follower_update($json_follower_list,$uid);

		//フォロワー数を別カラムに
		$memberinfo = $this->Member->usinfo_get2($uid);
		if(!empty($memberinfo['follower'])){ //フォロワーが0になると、$memberinfo['follower']が空白になっている
			$array_follower_list = json_decode($memberinfo['follower'], true);
			$follower_count = count($array_follower_list);
		}else{
			$follower_count = 0;
		}
		$this->Member->follower_count_update($follower_count,$uid);
	}

	public function review_authority()
	{
		$this->load->helper('url');
		if($this->input->is_ajax_request()){
		}else {
			redirect("/");
		}

		$id = $this->input->post('senderid');
		$uid = $this->input->post('receiverid');

		$this->load->model('Member');
		$usinfo = $this->Member->usinfo_get2($uid);//レビューされる側
		//$count = $usinfo['review_count'] + 1; それぞれのレビュー数なので違う

		$this->load->model('Comu');

		if(!empty($usinfo['reviewer_data'])){ //誰かが過去に予約した
			//追加前の連想配列を用意
			$array_reviewer_data = json_decode($usinfo['reviewer_data'], true);

			//キーに自分のidがあるか確認
			if(array_key_exists($id, $array_reviewer_data)){
				//2回目以降なので、過去のカウントに1追加
				$array_reviewer_data[$id] = $array_reviewer_data[$id] + 1;
			}else{
				//自分は初めて予約した
				//自分の予約回数を1に ※idはユニークなので被らない
				$array_reviewer_data[$id] =  1;
			}			
		}else{ //レビューされる人が初めて予約された
			$count = 1;
			//配列の初期化
			$array_reviewer_data = array
			(
				$id => $count			
			);
		}
		//DB挿入のためjsonに
		$json_reviewer_data = json_encode($array_reviewer_data);
		//DB更新
		$this->Comu->reviewer_data_update($json_reviewer_data,$uid);
	}

	/**
	 * stripe用の顧客詳細情報取得
	 *
	 */
	public function get_customer()
	{
		$this->load->helper('url');
		if ($this->input->is_ajax_request()) {
		} else {
			redirect("/");
		}

		$this->load->library('session');
		if($this->session->userdata('lgid') AND $this->session->userdata('lgmail') AND $this->session->userdata('lgpass')){
			$lgid = $this->session->userdata('lgid');
			$this->load->model('Member');
			$usinfo = $this->Member->usinfo_get($lgid);
		}else{
			redirect("user/login");
		}

		$customerid = $this->input->post('customerid');
		if (!$customerid) {
			http_response_code(400);
			echo json_encode(['success' => false, 'error' => 'Customer ID is required']);
			exit;
		}

		require 'vendor/autoload.php';
		\Stripe\Stripe::setApiKey('sk_test_51OxMuA2NouBXQI50wYtQTA1ljoIWw3UxfDUqWVjuDIaOxkIOFt8gULELLNI1QrfB0H5G00i4fzv68FWNWADjK2vK00Yxv57CNx');

		try {

			// 顧客情報を取得
			$customer = \Stripe\Customer::retrieve($customerid);
			// デフォルトの支払い方法を取得
			$defaultPaymentMethodId = $customer->invoice_settings->default_payment_method;
			if (!$defaultPaymentMethodId) {
				echo json_encode(['success' => true, 'defaultPaymentMethod' => null]);
				exit;
			}
			// 支払い方法の詳細を取得
			$paymentMethod = \Stripe\PaymentMethod::retrieve($defaultPaymentMethodId);
			// カード情報を含むレスポンスを返す
			echo json_encode([
				'success' => true,
				'defaultPaymentMethod' => [
					'id' => $paymentMethod->id,
					'card' => [
						'brand' => $paymentMethod->card->brand, // カードブランド
						'last4' => $paymentMethod->card->last4, // カード番号の末尾4桁
						'exp_month' => $paymentMethod->card->exp_month, // 有効期限（月）
						'exp_year' => $paymentMethod->card->exp_year, // 有効期限（年）
					],
				],
			]);
		} catch (\Stripe\Exception\ApiErrorException $e) {
			http_response_code(500);
			echo json_encode(['success' => false, 'error' => $e->getMessage()]);
		} catch (Exception $e) {
			http_response_code(400);
			echo json_encode(['success' => false, 'error' => $e->getMessage()]);
		}
	}

	public function transfer()
	{
		$this->load->helper('url');
		if($this->input->is_ajax_request()){
		}else {
			redirect("/");
		}

		$uid = $this->input->post('userid');
		$reward = $this->input->post('reward');
		$reward_int = intval($reward);
		$transfer_amount = $reward_int - 450;

		$this->load->model('Member');
		$usinfo = $this->Member->usinfo_get2($uid);

		//reward_flagをon(出金処理中)に
		$this->Member->reward_on($uid);

		//自分にメール送信
		$this->load->library('myemail'); 

		$config['protocol'] = 'sendmail';
		$config['mailpath'] = '/usr/sbin/sendmail';
		$config['_encoding'] = '7bit';
		$config['charset'] = 'ISO-2022-JP';
		$config['wordwrap'] = FALSE;
		$this->myemail->initialize($config);

		$sbj = '[ひまっち]出金申請がありました';
		//$sbj = '[ひまっち]メッセージが届きました';
		$from_name = mb_encode_mimeheader('ひまっち', "ISO-2022-JP", "UTF-8,EUC-JP,auto");
		$sbj = mb_encode_mimeheader($sbj, "ISO-2022-JP", "UTF-8,EUC-JP,auto");

		$honbun ='';
		//$honbun.=$receiver_name." 様\n\n";
		$honbun.="出金申請がありました。\n\n";
		$honbun.="ユーザーid：".$uid."\n";
		$honbun.="名前：".$usinfo['name']."\n";
		$honbun.="年齢：".$usinfo['age']."\n";
		$honbun.="報酬額：".$reward."\n";
		$honbun.="振込金額(報酬額-手数料450円)：".$transfer_amount."\n\n";
		$honbun.="-----\n";
		$honbun.="ひまっち運営チーム\n";
		$honbun.="ひまっち - https://himatch.jp/\n";
		//mb_language('Ja');
		//$honbun = mb_convert_encoding($honbun, 'UTF-8','ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN,SJIS');
		$honbun = mb_convert_encoding($honbun, "ISO-2022-JP", "UTF-8,EUC-JP,auto");
		$this->myemail->from('info@himatch.jp', $from_name);
		$this->myemail->to('scsfrn36911@nifty.com');
		$this->myemail->subject($sbj);
		$this->myemail->message($honbun);
		$this->myemail->send();


		//DB挿入のためjsonに
		//$json_reviewer_data = json_encode($array_reviewer_data);
		//DB更新
		//$this->Comu->reviewer_data_update($json_reviewer_data,$uid);
	}

	public function change_confirm_status()
	{
		$this->load->helper('url');
		if($this->input->is_ajax_request()){
		}else {
			redirect("/");
		}
        
		$uid = $this->input->post('userid');
		$talkroomid = $this->input->post('talkroomid');

		$this->load->model('Member');
		$usinfo = $this->Member->usinfo_get2($uid);

        //該当ユーザーの「予約中の取引(transaction_infoカラム)」を配列にする
		$transaction_info = json_decode($usinfo['transaction_info'], true);

        //該当talkroom_idの最新の予約を振込確認済みにする
        if(!empty($transaction_info)){ //空なんてことはないだろうが一応
            $lastKey = null;

            // 条件に一致する最後の配列を探す
            foreach ($transaction_info as $key => $item) {
                if ($item["talkroom_id"] == $talkroomid && $item["transfer_confirm"] == 2) {
                    $lastKey = $key;
                }
            }

            // 条件に一致する最後の配列のtransfer_confirmを1に変更
            if ($lastKey !== null) {
                $transaction_info[$lastKey]["transfer_confirm"] = '1';
            }
            //json形式にして、DBに保存
            $json_transaction = json_encode($transaction_info);
            $lgid = $uid;
            $this->load->model('Comu');
            $this->Comu->transaction_update($lgid,$json_transaction);
        }
        //if (is_array($array_follow) === true) {
	}
/*
    public function payment() {
		$this->load->helper('url');
        $this->load->library('Stripe_lib'); 

		if($this->input->is_ajax_request()){
		}else {
			redirect("/");
		}

        if (!empty($_POST['stripeToken'])) {
            $token = $_POST['stripeToken'];
        }*/
        //$token = $this->input->post('stripeToken'); // Stripe のトークン
        /*$amount = 5000;
        $currency = 'usd';

        // 決済処理
        $charge = $this->stripe_lib->createCharge($amount, $currency, 'Test Payment nandemoii', $token);

        if ($charge) {
            echo 'Payment Successful! Charge ID: ' . $charge->id;
        } else {
            echo 'Payment Failed!';
        }
    }*/
}
