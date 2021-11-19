<?php
/**
 * The view file for browse page of mr module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @author      Guodong Ding
 * @package     mr
 * @version     $Id: create.html.php $
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id="mainMenu" class="clearfix">
  <?php if($this->config->systemMode == 'new'):?>
  <div id="sidebarHeader">
    <div class="title">
      <?php echo $lang->mr->common;?>
    </div>
  </div>
  <?php endif;?>
  <div class="btn-toolBar pull-left">
    <?php foreach($lang->mr->statusList as $key => $label):?>
    <?php $active = $param == $key ? 'btn-active-text' : '';?>
    <?php $label = "<span class='text'>$label</span>";?>
    <?php if($param == $key) $label .= " <span class='label label-light label-badge'>{$pager->recTotal}</span>";?>
    <?php echo html::a(inlink('browse', "mode=status&param=$key"), $label, '', "class='btn btn-link $active'");?>
    <?php endforeach;?>
    <?php echo html::a(inlink('browse', "mode=assignee&param={$this->app->user->account}"), $lang->mr->assignedToMe, '', "class='btn btn-link $active'");?>
    <?php echo html::a(inlink('browse', "mode=creator&param={$this->app->user->account}"), $lang->mr->createdByMe, '', "class='btn btn-link $active'");?>
  </div>
  <div class="btn-toolbar pull-right">
    <?php common::printLink('mr', 'create', '', "<i class='icon icon-plus'></i> " . $lang->mr->create, '', "class='btn btn-primary'");?>
  </div>
</div>
<div id='mainContent'>
<?php if(empty($MRList)):?>
<div class="table-empty-tip">
  <p>
    <span class="text-muted"><?php echo $lang->noData . $lang->mr->common;?></span>
    <?php if(common::hasPriv('mr', 'create')):?>
    <?php echo html::a($this->createLink('mr', 'create'), "<i class='icon icon-plus'></i> " . $lang->mr->create, '', "class='btn btn-info'");?>
    <?php endif;?>
  </p>
</div>
<?php else:?>
  <form class='main-table' id='ajaxForm' method='post'>
    <table id='gitlabProjectList' class='table has-sort-head table-fixed'>
      <thead>
        <tr>
          <?php $vars = "mode=$mode&param=$param&objectID=$objectID&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}";?>
          <th class='w-60px  text-left'><?php common::printOrderLink('id', $orderBy, $vars, $lang->mr->id);?></th>
          <th class='w-200px text-left'><?php common::printOrderLink('title', $orderBy, $vars, $lang->mr->title);?></th>
          <th class='w-200px text-left'><?php common::printOrderLink('sourceBranch', $orderBy, $vars, $lang->mr->sourceBranch);?></th>
          <th class='w-200px text-left'><?php common::printOrderLink('targetBranch', $orderBy, $vars, $lang->mr->targetBranch);?></th>
          <th class='w-120px text-left'><?php common::printOrderLink('mergeStatus', $orderBy, $vars, $lang->mr->mergeStatus);?></th>
          <th class='w-120px text-left'><?php common::printOrderLink('approvalStatus', $orderBy, $vars, $lang->mr->approvalStatus);?></th>
          <th class='w-120px c-actions-3'><?php echo $lang->actions;?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($MRList as $MR):?>
        <tr>
          <td class='text'><?php echo $MR->id;?></td>
          <td class='text'><?php echo $MR->title;?></td>
          <td class='text'><?php echo $this->loadModel('gitlab')->apiGetSingleProject($MR->gitlabID, $MR->sourceProject)->name_with_namespace . ':' . $MR->sourceBranch;?></td>
          <td class='text'><?php echo $this->loadModel('gitlab')->apiGetSingleProject($MR->gitlabID, $MR->targetProject)->name_with_namespace . ':' . $MR->targetBranch;?></td>
          <?php if($MR->status == 'closed'):?>
            <td class='text'><?php echo zget($lang->mr->statusList, $MR->status);?></td>
          <?php else:?>
            <td class='text'><?php echo ($MR->status == 'merged') ? zget($lang->mr->statusList, $MR->status) : zget($lang->mr->mergeStatusList, $MR->mergeStatus);?></td>
          <?php endif;?>

          <?php if($MR->status == 'merged' or $MR->status == 'closed'):?>
            <td class='text'><?php echo '-';?></td> <!-- Keep page clean that make user focus to the MR not reviewed. -->
          <?php else:?>
            <td><?php echo empty($MR->approvalStatus) ? $lang->mr->approvalStatusList['notReviewed'] : $lang->mr->approvalStatusList[$MR->approvalStatus];?></td>
          <?php endif;?>
          <td class='c-actions'>
            <?php
            common::printLink('mr', 'view',   "mr={$MR->id}", '<i class="icon icon-eye"></i>', '', "title='{$lang->mr->view}' class='btn btn-info'");
            common::printLink('mr', 'edit',   "mr={$MR->id}", '<i class="icon icon-edit"></i>', '', "title='{$lang->mr->edit}' class='btn btn-info'");
            common::printLink('mr', 'diff',   "mr={$MR->id}", '<i class="icon icon-review"></i>', '', "title='{$lang->mr->viewDiff}' class='btn btn-info'");
            common::printLink('mr', 'link',   "mr={$MR->id}", '<i class="icon icon-link"></i>', '', "title='{$lang->mr->link}' class='btn btn-info'" . ($MR->linkButton == false ? 'disabled' : ''));
            common::printLink('mr', 'delete', "mr={$MR->id}", '<i class="icon icon-trash"></i>', 'hiddenwin', "title='{$lang->mr->delete}' class='btn btn-info'");
            ?>
          </td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
    <div class='table-footer'><?php $pager->show('right', 'pagerjs');?></div>
  </form>
<?php endif;?>
</div>
<?php include '../../common/view/footer.html.php';?>
