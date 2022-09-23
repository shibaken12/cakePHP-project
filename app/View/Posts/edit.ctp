<h1>投稿の編集</h1>
<?php
echo $this->Form->create('Post');
echo $this->Form->input('title');
echo $this->Form->input('body', array('rows' => '3'));
echo $this->Form->input('id', array('type' => 'hidden'));
echo $this->Form->end('投稿を更新する');
?>
</br><?= $this->Html->link('投稿一覧に戻る', array('action' => 'index')); ?>
