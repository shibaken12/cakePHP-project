<div class="users form">
<?php echo $this->Form->create('User'); ?>
<fieldset>
<legend><?php echo __('会員登録画面'); ?></legend>
<?php 
echo $this->Form->input('username');
echo $this->Form->input('mail');
echo $this->Form->input('password');
?>
</fieldset>
<?php echo $this->Form->end(__('登録')); ?>
<?= $this->Html->link('ログインはこちら', array('action' => 'login')); ?>
</br><?= $this->Html->link('投稿一覧に戻る', array('controller' => 'posts', 'action' => 'index')); ?>
</div>
