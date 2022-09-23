
<h1>パスワードリセット</h1>
<p>新しいパスワードを入力してください</p>

<?php
echo $this->Form->create('User');
echo $this->Form->input('password');
echo $this->Form->input('token', array('type' => 'hidden'));
echo $this->Form->end('パスワード更新');
?>
</br><?= $this->Html->link('投稿一覧に戻る', array('controller' => 'posts', 'action' => 'index')); ?>