<?php
/**
 * The moveextfiles view file of upgrade module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2022 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Shujie Tian <tianshujie@cnezsoft.com>
 * @package     upgrade
 * @version     $Id: moveextfiles.html.php 5119 2022-02-21 13:22:42Z $
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<div class='container'>
  <form method='post' action="<?php echo $this->createLink('upgrade', 'moveEXTFiles', "fromVersion=$fromVersion");?>">
    <div class='modal-dialog'>
      <div class='modal-header'>
        <strong><?php echo $lang->upgrade->compatibleEXT;?></strong>
        <?php if($result == 'success'):?>
        <div class='alert alert-info no-margin'><?php echo $lang->upgrade->moveExtFileTip?></div>
        <?php elseif($result == 'fail' and is_array($command)):?>
        <div class='alert alert-info no-margin'><?php echo $lang->upgrade->deleteDirTip?></div>
        <?php endif;?>
      </div>
      <div class='modal-body'>
        <div>
          <?php if($result == 'success'):?>
          <div class="checkbox-primary" title="<?php echo $lang->selectAll?>">
            <input type='checkbox' id='checkAll' checked><label for='checkAll'><strong><?php echo $lang->upgrade->fileName;?></strong></label>
          </div>
          <?php echo html::checkbox('files', $files, '', 'checked');?>
          <?php else:?>
          <?php
          if(is_array($command))
          {
              foreach($command as $cmd) echo "<div><code>$cmd</code></div>";
          }
          else
          {
              echo "<div><code>$command</code></div>";
          }
          ?>
          <?php endif;?>
        </div>
      </div>
      <div class='modal-footer text-center'>
        <?php if($result == 'success') echo html::submitButton($lang->upgrade->next);?>
        <?php if($result == 'fail') echo $errorMessage . ' ' . html::a('#', $this->lang->refresh, '', "class='btn btn-sm' onclick='refreshPage()'");?></div>
        <?php echo html::hidden('foo'); // Just a hidden var, to make sure $_POST is not empty.?>
      </div>
    </div>
  </form>
</div>
<?php include '../../common/view/footer.lite.html.php';?>
