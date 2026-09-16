<?php
$config->runner->form = new stdclass();
$config->runner->form->edit['name']       = array('type' => 'string', 'required' => true, 'filter' => 'trim');
$config->runner->form->edit['desc']       = array('type' => 'string', 'required' => false, 'default' => '', 'filter' => 'trim');
$config->runner->form->edit['labels']     = array('type' => 'array', 'required' => false, 'default' => array(), 'filter' => 'join');
$config->runner->form->edit['newLabels']  = array('type' => 'array', 'required' => false, 'default' => array(), 'filter' => 'join');
$config->runner->form->edit['editedDate'] = array('type' => 'datetime', 'required' => false, 'default' => helper::now());
