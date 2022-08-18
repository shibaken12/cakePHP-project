<?php

class Post extends AppModel {
    public $validate = array(
        'title' => array(
            'rule' => 'notBlank'
        ), 
        'body' => array(
            'rule' => 'notBlank'
        )
    );

    public function isOwnedBy($post, $user) {
        return $this->field('id', array(
            'id' => $post, 'user_id' => $user)) !== false;
    }

    // 論理削除用記述
    public $actsAs = array('Utils.SoftDelete');

    //SQLをjoinさせる
    public $belongsTo = ['User'];
}
