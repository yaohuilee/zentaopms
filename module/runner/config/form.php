<?php
$config->runner->form = new stdclass();
$config->runner->form->edit['name']        = array('type' => 'string', 'required' => true, 'filter' => 'trim');
$config->runner->form->edit['desc']        = array('type' => 'string', 'required' => false, 'default' => '', 'filter' => 'trim');
$config->runner->form->edit['updatedDate'] = array('type' => 'datetime', 'required' => false, 'default' => helper::now());
