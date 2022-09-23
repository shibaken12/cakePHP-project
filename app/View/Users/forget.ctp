<h1>パスワードリセット</h1>
<p>メールアドレスを入力してください。パスワード再発行用のURLを送付します。</p>

<?php
echo $this->Form->create('User');
echo $this->Form->input('mail');
echo $this->Form->end('メール送信');
?>
</br><?= $this->Html->link('投稿一覧に戻る', array('controller' => 'posts', 'action' => 'index')); ?>