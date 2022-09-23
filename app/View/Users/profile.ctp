<h1>ユーザ情報</h2>

<table>
<tr>
<th>ユーザ名</th>
<th>プロフィール画像</th>
<th>メールアドレス</th>
<th>一言コメント</th>
</tr>

<!-- ここから、$posts配列をループして、投稿記事の情報を表示 -->

<tr>
<td><?php echo $users['User']['username']; ?>
</td>

<?php if (empty($users['User']['image'])) : ?>
<td>未登録</td>

<?php else : ?>
<td>
<?= $this->Html->image($users['User']['image'], array('width'=>'200','height'=>'200')); ?>
</td>
<?php endif; ?>

<td>
<?= $users['User']['mail']; ?>
</td>

<td>
<?= $users['User']['cmt']; ?>
</td>
</tr>

</table>

<?php if (empty($session)) : ?>
<?= $this->Html->link('投稿一覧に戻る', array('controller' => 'posts', 'action' => 'index')); ?>

<?php elseif ($session['id'] == $users['User']['id']) : ?>
</br><?= $this->Html->link('自分のプロフィールを編集する', array('action' => 'edit', $users['User']['id'])); ?>
</br><?= $this->Html->link('投稿一覧に戻る', array('controller' => 'posts', 'action' => 'index')); ?>

<?php else : ?>
<?= $this->Html->link('投稿一覧に戻る', array('controller' => 'posts', 'action' => 'index')); ?>

<?php endif; ?>
