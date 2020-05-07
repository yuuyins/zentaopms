<?php
$config->my = new stdclass();
$config->my->editprofile = new stdclass();
$config->my->editprofile->requiredFields = 'account,realname';

$config->my->dynamicCounts = 14; 
$config->my->todoCounts    = 10; 
$config->my->taskCounts    = 10; 
$config->my->bugCounts     = 10; 
$config->my->storyCounts   = 10; 

$config->mobile = new stdclass();
$config->mobile->todoBar  = array('today', 'yesterday', 'thisWeek', 'lastWeek', 'all');
$config->mobile->taskBar  = array('assignedTo', 'openedBy');
$config->mobile->bugBar   = array('assignedTo', 'openedBy', 'resolvedBy');
$config->mobile->storyBar = array('assignedTo', 'openedBy', 'reviewedBy');

$config->mobile->quickAccessList = array('project', 'doc', 'report', 'train', 'ops', 'oa', 'feedback', 'more');

$config->mobile->quickAccessIconList = array();
$config->mobile->quickAccessIconList['project']  = 'menu_project';
$config->mobile->quickAccessIconList['doc']      = 'menu_file';
$config->mobile->quickAccessIconList['report']   = 'menu_statistic';
$config->mobile->quickAccessIconList['train']    = 'menu_train';
$config->mobile->quickAccessIconList['ops']      = 'menu_ops';
$config->mobile->quickAccessIconList['oa']       = 'menu_oa';
$config->mobile->quickAccessIconList['feedback'] = 'menu_feedback';
$config->mobile->quickAccessIconList['more']     = 'menu_more';
