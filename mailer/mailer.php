<?php 

	abstract class Mailer {
		protected $data = array ();
		protected $mail = array ();
		protected $errors = array ();
		protected $response = array ();
		
		abstract protected function validate ();
		abstract protected function prepareMail ();
		
		protected function getPostData () {
			$this->data = $_POST;
		}
			
		protected function sendMail () {
			$subject = "=?UTF-8?B?".base64_encode($this->mail['subject'])."?=";
			$from_user = "=?UTF-8?B?".base64_encode($this->mail['from_user'])."?=";
			$from_email = $this->mail['from_email'];
			$to_email = $this->mail['to_email'];
			$message = $this->mail['message'];

			$headers = "From: $from_user <$from_email>\r\n".
						"MIME-Version: 1.0" . "\r\n" .
						"Content-type: text/html; charset=UTF-8" . "\r\n";
						
			return mail($to_email, $subject, $message, $headers);
		}
		
		protected function sendResponse () {
			header("Content-Type: application/json");
			echo json_encode($this->response);
		}

		protected function isPhone ($phone) {
			$reg = '/^[0-9\-\(\)\s\+]{7,20}$/';
			if (preg_match($reg, $phone)) {
				return true;
			}
			
			return false;
		}
		
		protected function isMail ($mail) {
			$reg = '/[^\x00-\x20()<>@,;:\\".[\]\x7f-\xff]+(?:\.[^\x00-\x20()<>@,;:\\".[\]\x7f-\xff]+)*\@[^\x00-\x20()<>@,;:\\".[\]\x7f-\xff]+(?:\.[^\x00-\x20()<>@,;:\\".[\]\x7f-\xff]+)+/i';
			
			if (preg_match($reg, $mail) && mb_strlen($mail, 'utf-8') >= 5) {
				return true;
			}
			
			return false;
		}
		
		protected function isName ($name) {
			if (mb_strlen($name, 'utf-8') >= 2) {
				return true;
			}
			
			return false;
		}
		
		protected function onSuccess () {
			$this->response['success'] = 'Данные успешно отправлены. В ближайшее время менеджер свяжется с вами!';
			$this->sendResponse ();
		
		}
		
		protected function onError () {
			$this->response['errors'] = $this->errors;
			$this->sendResponse ();
		}
		
		public function __construct () {
			$this->getPostData ();
			if ($this->validate()) {
				$this->prepareMail ();
				if ($this->sendMail ()) {
					$this->onSuccess ();
				} else {
					$this->errors[] = 'Отправка сообщения не удалась';
					$this->onError ();
				}
			} else {
				$this->onError ();
			}
		}
	}