<?php
App::uses('AppController', 'Controller');

class UsersController extends AppController {
	public function beforeFilter() {
		parent::beforeFilter();
		//未ログイン者が見れるページ
		$this->Auth->allow('add', 'logout', 'profile', 'forget', 'reset');
	}

	public function index() {
		$this->User->recursive = 0;
		$this->set('users', $this->paginate());
	}

	public function view($id = null) {
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		$this->set('user', $this->User->findById($id));
	}

	public function add() {
		//ログインユーザーの情報を取得
		$user = $this->Auth->user();
		//ログインしている場合は投稿一覧へリダイレクト
		if (!empty($user)) {
			return $this->redirect(array('controller' => 'posts', 'action' => 'index'));
		} elseif ($this->request->is('post')) {
			$this->User->create();
			if ($this->User->save($this->request->data)) {
				$this->Flash->success(__('会員登録が完了しました'));
				return $this->redirect(array('controller' => 'Posts', 'action' => 'index'));
			}
			$this->Flash->error(__('登録に失敗しました。再度お試しください'));
		}
	}

	public function login() {
		//ログインユーザーの情報を取得
		$user = $this->Auth->user();
		//ログインしている場合は投稿一覧へリダイレクト
		if (!empty($user)) {
			return $this->redirect(array('controller' => 'posts', 'action' => 'index'));
		} elseif ($this->request->is('post')) {
			if ($this->Auth->login()) {
				$this->Flash->success(__('ログインに成功しました'));
				return $this->redirect($this->Auth->redirect());
			} else {
				$this->Flash->error(__('メールアドレスかパスワードが正しくありません。再度入力してください。'));
			}
		}
	}

	public function logout() {
		$this->Flash->success(__('ログアウトしました'));
		return $this->redirect($this->Auth->logout());
	}

	public function profile($id = null) {
		$this->set('users', $this->User->findById($id));
		$this->set('imgname', $this->User->image);
		//ログインユーザーの情報を取得
		$session = $this->Auth->user();
		$this->set('session', $session);
	}

	public function edit($id = null) {
		//ログインユーザーの情報を取得
		$session = $this->Auth->user();
		$user = $this->User->findById($id);

		if ($this->request->is(array('post', 'put'))) {
			//画像ファイルのバリデーション
			//画像が送られてこなかった場合
			if (empty($this->request->data['User']['image']['name'])) {
				if ($user['User']['image']) {
					$imgname = $user['User']['image'];
				} else {
					$imgname = NULL;
				}
				//画像ファイルが送られたとき
			} else {
				//画像保存先のパス
				$upload_dir = WWW_ROOT . "img/";
				//アップロードしたファイルの一時的なパスを取得
				$tmp_path = $this->request->data['User']['image']['tmp_name'];

				//MIMEタイプの取得
				$finfo = finfo_open(FILEINFO_MIME_TYPE);
				$mime_type = finfo_file($finfo, $tmp_path);
				finfo_close($finfo);

				//画像ファイルかどうかバリデーション
				if ($mime_type !== "image/jpeg" && $mime_type !== "image/jpg" && $mime_type !== "image/png") {
					$this->Flash->error(__('画像のアップロードに失敗しました。無効な画像形式です。'));
				} else {
					//ファイル拡張子の取得
					switch ($mime_type) {
					case 'image/jpeg':
						$file_ext = '.jpeg';
						break;
					case 'image/jpg':
						$file_ext = '.jpg';
						break;
					case 'image/png':
						$file_ext = '.png';
						break;
					}
					//ファイル名をランダム数字＋取得拡張子へ変更
					$imgname = rand() . $file_ext;
					//古い画像があれば削除
					if (!empty($user['User']['image'])) {
						unlink('../webroot/img/' . $user['User']['image']);
					}
					//一時ファイルを既存ディレクトリへ移動
					move_uploaded_file($tmp_path, $upload_dir . $imgname);
				}
			}

			//一言コメントのバリデーション
			if (empty($this->request->data['User']['cmt'])) {
				if (empty($user['User']['cmt'])) {
					$cmt = $user['User']['cmt'];
				} else {
					$cmt = NULL;
				}
			} else {
				$cmt = $this->request->data['User']['cmt'];
			}

			//一言コメントと画像をDBへ保存
			if ($this->User->save(array('id' => $session['id'], 'image' => $imgname, 'cmt' => $cmt))) {
				$this->Flash->success(__('プロフィールの更新に成功しました'));
			} else {
				$this->Flash->error(__('プロフィールの更新に失敗しました'));
			}

			return $this->redirect(array('controller' => 'Posts', 'action' => 'index'));
		}

		//一言コメント欄に初期値があれば格納
		if (!$this->request->data) {
			$this->request->data['User']['cmt'] = $user['User']['cmt'];
		}
	}
	public function forget() {
		if ($this->request->is('post')) {
			$mail = $this->request->data['User']['mail'];
			//メールアドレスがDBに存在するか確認
			$user = $this->User->findBymail($mail);
			//メールアドレスの登録の有無に関わらずメッセージ出力
			$this->Flash->success(__('パスワード再発行用のURLを発行しました。30分間のみ有効です。'));

			//一致するメールアドレスがあった場合の処理
			if ($user) {
				//パスワードリセットのためのトークンとリクエスト日時の取得
				$token = bin2hex(random_bytes((32)));
				$date = date('Y/m/d H:i:s');

				//トークンとリクエスト日時をDBへ登録
				$this->User->save(array(
					'id' => $user['User']['id'],
					'token' => $token,
					'reset_request' => $date)
				);

				//ユーザにアクセスしてもらうURLの宣言
				$url = 'https://procir-study.site/shibata468/cake/users/reset?token=' . $token;

				//再発行用のURLをメール送信処理
				mb_language("Japanese");
				mb_internal_encoding("UTF-8");
				$to      = $user['User']['mail'];
				$subject = 'パスワード再発行';
				$message = "パスワード再発行URLは以下の通りです。30分間のみ有効です\n" . $url;
				$headers = "From : procir@a.com\n";
				$headers .= "Content-Type : text/plain";
				mb_send_mail($to, $subject, $message, $headers);
			}
			return $this->redirect(array('controller' => 'Posts', 'action' => 'index'));
		}

	}

	public function reset() {
		if ($this->request->is('post')) {
			//GETで取得したトークンを変数へ格納（GETの場合はqueryを使う）
			$token = $this->request->query['token'];
			$password = $this->request->data['User']['password'];

			//トークンと一致するユーザーを参照
			$resetuser = $this->User->findBytoken($token);

			//ユーザ情報が取得できないとき
			if (!$resetuser) {
				$this->Flash->error(__('不正なアクセスです。再度お試しください'));
				//ユーザ情報を取得できれば
			} else {
				$date1 = new DateTime();
				$current_time = $date1->format('Y-m-d H:i:s');
				$date2 = new DateTime($resetuser['User']['reset_request']);
				$tokenperiod = $date2->modify('+30 minute')->format('Y-m-d H:i:s');
				//URLの有効期限を超えた場合は不正アクセス
				if ($current_time > $tokenperiod) {
					$this->Flash->error(__('有効期限が切れたURLです。再度お試しください'));
					//半角スペース等の入力をバリデーション
				} elseif (mb_ereg_match("^(\s|　)+$", $password)) {
					$this->Flash->error(__('新しいパスワードを入力してください'));
				} else {
					$this->User->save(array(
						'id' => $resetuser['User']['id'],
						'password' => $password,
						'token' => NULL)
					);
					$this->Flash->success(__('パスワードの更新に成功しました。ログインしてください。'));
					return $this->redirect(array('controller' => 'Posts', 'action' => 'index'));
				}
			}
		}
	}
}
