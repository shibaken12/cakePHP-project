<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * @link          https://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 */

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		https://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {
	public $components = array(
		'DebugKit.Toolbar',
        'Flash',
        'Auth' => array(
            'loginRedirect' => array(
                'controller' => 'posts',
                'action' => 'index'
            ),
            'logoutRedirect' => array(
                'controller' => 'posts',
                'action' => 'index'
            ),
            'authenticate' => array(
                'Form' => array(
                    //パスワードのハッシュ化処理
                    'passwordHasher' => 'Blowfish',
                    //メールアドレスでログインするためのカラム設定切り替え
                    'fields' => array(
                        'username' => 'mail', 
                        'password' => 'password'
                    )
                )
			)
        )
    );

    public function beforeFilter() {
        //ログアウト状態でも閲覧可能かページの指定
        $this->Auth->allow('index', 'view', 'add');
    }
}
