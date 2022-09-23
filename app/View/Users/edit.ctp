<h1>プロフフィール画像と一言コメントの編集</h1>

<?php
echo $this->Form->create('User', array('type' =>'file', 'enctype' => 'mulltipart/form-data'));
echo $this->Form->input('image', array('label' => 'プロフィール画像', 'type' => 'file', 'multiple'));
echo $this->Form->input('cmt', array('rows' => '2', 'label' => '一言コメント'));
echo $this->Form->input('id', array('type' => 'hidden'));
echo $this->Form->end('プロフィールを更新する');
?>
</br><?= $this->Html->link('投稿一覧に戻る', array('controller' => 'posts', 'action' => 'index')); ?>