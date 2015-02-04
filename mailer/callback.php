<?php
	include_once "mailer.php";

	class Callback extends Mailer {

		protected function validate () {
			if (!isset ($this->data['name']) || $this->data['name'] == '') {
				$this->errors['name'] = 'Вы забыли указать имя';
			} elseif (!$this->isName($this->data['name'])) {
				$this->errors['name'] = 'Длина имени '.$this->data['name'].' должна быть более 3-х символов';
			}

			if (!isset ($this->data['phone']) || $this->data['phone'] == '') {
				$this->errors['phone'] = 'Вы забыли указать телефон';
			} elseif (!$this->isPhone($this->data['phone'])) {
				$this->errors['phone'] = 'Проверьте, правильно ли вы ввели номер';
			}

			if (!isset ($this->data['email']) || $this->data['email'] == '') {
				$this->errors['email'] = 'Вы забыли указать email';
			} elseif (!$this->isMail($this->data['email'])) {
				$this->errors['email'] = 'Проверьте, правильно ли вы ввели E-mail';
			}
			
			if (!isset ($this->data['count'])) {
				$this->data['count'] = '1';
			}

			if (!isset ($this->data['address'])) {
				$this->data['address'] = '';
			}

			$this->data['date'] = date('d.m.Y');

			if (empty($this->errors)) {
				return true;
			} else {
				return false;
			}
		}
		protected function prepareMail () {
			$mes = '<h1>Получено новое сообщение формы Обратной связи</h1>';
			$mes .= '<i>'.$this->data['date'].'</i>';
			$mes .= '<style>table tr td {border:1px solid #ccc; padding:3px;} table { border-collapse: collapse;}</style>';
			$mes .= '<table cellpadding="0" cellspacing="0" border="0" width="100%">';
			$mes .= '<tr><td colspan="2"><strong>Контактные данные</strong></td></tr>';
			$mes .= '<tr><td>Имя</td><td>' . $this->data['name'] . '</td></tr>';
			$mes .= '<tr><td>Телефон</td><td>' . $this->data['phone'] . '</td></tr>';
			$mes .= '<tr><td>Количество</td><td>' . $this->data['count'] . '</td></tr>';
			$mes .= '<tr><td>Email</td><td>' . $this->data['email'] . '</td></tr>';
			$mes .= '<tr><td colspan="2"><strong>Адрес:</strong></td></tr>';
			$mes .= '<tr><td colspan="2">' . $this->data['address'] . '</td></tr>';
			$mes .= '</table>';

			$this->mail['subject'] = 'Получено новое сообщение формы Обратной связи';
			$this->mail['from_user'] = $this->data['name'];
			$this->mail['from_email'] = $this->data['email'];
			$this->mail['to_email'] = 'staheev@inbox.ru';
			$this->mail['message'] = $mes;
		}
	}

	$mailer = new Callback ();