<?php

//action名はctpファイルと名前を合わせる
//set()メソッドはコントローラからビューへデータを渡すためのもの
class PostsController extends AppController {
	//viewで使いたいヘルパーの宣言
	public $helpers = array('Html', 'Form','Flash');
	//コントローラからコンポーネントを使用する際は、コンポーネントの名前を配列で指定
	//FlashComponentを指定する
	//FlashComponent:フォームの処理後やデータの確認のためのメッセージ表示
	public $components = array('Flash');

	public function index() {
		$this->set('posts', $this->Post->find('all'));
		//ログインユーザーの情報を取得
		$user = $this->Auth->user();
        $this->set('users', $user);
	}

	// public function view ($id = null) {
	// 	if (!$id) {
	// 		throw new NotFoundException(__('無効な投稿です'));
	// 	}

	// 	$post = $this->Post->findById($id);
	// 	if (!$post) {
	// 		throw new NotFoundException(__('無効な投稿です'));
	// 	}
	// 	$this->set('post',$post);
	// }

	public function add() {
		//ログインユーザーの情報を取得
		$user = $this->Auth->user();
		//ログインしていない場合は、会員登録画面へリダイレクト
		if (empty($user)) {
			return $this->redirect(array('controller' => 'users', 'action' => 'add'));
		} elseif ($this->request->is('post')) {
			$this->Post->create();
			$this->request->data['Post']['user_id'] = $this->Auth->user('id');
			if ($this->Post->save($this->request->data)) {
				$this->Flash->success(__('投稿を登録しました'));
				return $this->redirect(array('action' => 'index'));
			}
			$this->Flash->error(__('投稿に失敗しました'));
		}
		
	}

	public function edit($id = null) {
		//ログインユーザーの情報を取得
		$user = $this->Auth->user();
		$post = $this->Post->findById($id);

		//ログイン者以外が編集しようとすると、投稿一覧へリダイレクト
		if ($user['id'] !== $post['Post']['user_id']) {
			return $this->redirect(array('action' => 'index')); 	
		} else {
			if (!$id) {
				throw new NotFoundException(__('無効な投稿です'));
			}

			if (!$post) {
				throw new NotFoundException(__('無効な投稿です'));
			}
			if ($this->request->is(array('post', 'put'))) {
				$this->Post->id = $id;
				//ディベロッパーツール対策
				if ($post['Post']['id'] !== $this->request->data['Post']['id']) {
					$this->Flash->error(__('不正なアクセスです'));
					return $this->redirect(array('action' => 'index'));	
				} elseif ($this->Post->save($this->request->data)) {
					$this->Flash->success(__('投稿ID %s 番の投稿が編集されました。', h($id)));
					return $this->redirect(array('action' => 'index'));
				}
				$this->Flash->error(__('投稿の編集に失敗しました'));
			}

			if (!$this->request->data) {
				$this->request->data = $post;
			}
		}
	}

	public function delete($id) {
		//ログインユーザーの情報を取得
		$user = $this->Auth->user();
		$post = $this->Post->findById($id);

		//ログイン者以外が削除しようとすると、投稿一覧へリダイレクト
		if ($user['id'] !== $post['Post']['user_id']) {
			$this->Flash->error(__('不正なアクセスです'));
			return $this->redirect(array('action' => 'index'));
		} else {
			$this->autoRender = false;
			if ($this->request->is('get')) {
				throw new MethodNotAllowedException();
			}
			
			if ($this->Post->delete($id)) {
				$this->Flash->success(__('投稿ID %s 番の投稿が削除されました。', h($id)));
			} else {
				$this->Flash->error(
					__('投稿ID %s 番の投稿の削除に失敗しました', h($id))
				);
			}
			return $this->redirect(array('action' => 'index'));
		}
	}
}
?>