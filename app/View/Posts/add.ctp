<h1>新規投稿</h1>
<?php
//create():フォームの開始タグ
//引数はモデル名を指定することができる
echo $this->Form->create('Post');
echo $this->Form->input('title');
echo $this->Form->input('body', array('rows' => '3'));
echo $this->Form->end('投稿する');
?>
</br><?= $this->Html->link('投稿一覧に戻る', array('action' => 'index')); ?>