<?php $name = $request->query->get('name', 'World'); ?>

Hello, <?= htmlspecialchars($name, encoding: 'UTF-8') ?>
