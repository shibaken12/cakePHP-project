<div class="users form">
<?php echo $this->Flash->render('auth'); ?>
<?php echo $this->Form->create('User'); ?>
<fieldset>
<legend>
<?php echo __('登録されているメールアドレスとパスワードを入力してください'); ?>
</legend>

<?php 
echo $this->Form->input('mail');
echo $this->Form->input('password');
?>

</fieldset>
<?= $this->Form->end(__('ログイン')); ?>
</br><?= $this->Html->link('パスワードを忘れた方はこちら', array('action' => 'forget')); ?>
</br><?= $this->Html->link('投稿一覧に戻る', array('controller' => 'posts', 'action' => 'index')); ?>
</div>