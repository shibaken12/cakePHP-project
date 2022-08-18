<?php

App::uses('AppModel', 'Model');
App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');

class User extends AppModel {
    //save()メソッドを呼び出した時に実施される処理の定義
    public $validate = array(
        'username' => array(
            'required' => array(
                'rule' =>'notBlank',
                'message' => '名前を入力してください'
            )
        ),
        'mail' => array(
            'rule1' => array(
                'rule' => 'email',
                'message' => 'メールアドレスを入力してください'
            ),
            'rule2' => array(
                'rule' => 'isUnique',
                'message' => 'このメールアドレスは既に登録されています'
            )
        ),
        'password' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'パスワードを入力してください'
            )
        ),
        'mimetype' => array(
            'rule' => array(
                'mimeType', array(
                'image/jpeg', 'image/png', 'image/jpg'
                ),  
                'message' => array( '無効な画像形式です')
            )
        )
    );

    //パスワードのハッシュ化
    public function beforeSave($options = array()) {
        if (isset($this->data[$this->alias]['password'])) {
            $passwordHasher = new BlowfishPasswordHasher();
            $this->data[$this->alias]['password'] = $passwordHasher->hash(
                $this->data[$this->alias]['password']
            );
        }
        return true;
    }
}

?>