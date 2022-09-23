<h1>投稿一覧</h1>
<p><?= $this->Html->link('新規投稿をする', array('action' => 'add')); ?></p>
<?php if (!isset($users)) : ?>
<h2><?= 'ログアウト中。ログインしてください'; ?></h2>
<p align="right">
<?= $this->Html->link('会員登録はこちら', array('controller' => 'users', 'action' => 'add')); ?>
</br><?= $this->Html->link('ログインはこちら', array('controller' => 'users', 'action' => 'login')); ?>
</p>

<?php else : ?>
<!-- findで値を取ってきていないため間にテーブル名不要　-->
<h2><?= $users['username'] . 'さんがログイン中'; ?></h2>
<p align="right">
<?= $this->Html->link('ログアウトはこちら', array('controller' => 'users', 'action' => 'logout')); ?>
</p>
<?php endif; ?>

<table>
<tr>
<th>投稿Id</th>
<th>投稿者名</th>
<th>タイトル</th>
<th>削除・編集リンク</th>
<th>本文</th>
<th>投稿日時</th>
</tr>

<!-- ここから、$posts配列をループして、投稿記事の情報を表示 -->

<?php foreach ($posts as $post): ?>
<tr>
<td><?php echo $post['Post']['id']; ?></td>

<td>
<?php
echo $this->Html->link($post['User']['username'],array(
        'controller' => 'users', 
        'action' => 'profile',
        $post['User']['id']));
?>
</td>

<td>
<?= $post['Post']['title']; ?>
</td>

<td>
<?php if (empty($users)) : ?>
<?php elseif ($users['id'] == $post['Post']['user_id']) : ?>
<?= $this->Form->postLink('削除',array('action' => 'delete', $post['Post']['id']),array('confirm' => '本当に削除しますか?')); ?>

</br><?= $this->Html->link('編集', array('action' => 'edit', $post['Post']['id'])); ?>

<?php endif; ?>
</td>

<td>
<?php echo $post['Post']['body']; ?>
</td>

<td>
<?php echo $post['Post']['created']; ?>
</td>

</tr>
<?php endforeach; ?>

</table>