<?php
/**
 * The html template file of execute method of upgrade module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     upgrade
 * @version     $Id: execute.html.php 5119 2013-07-12 08:06:42Z wyd621@gmail.com $
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<div class='container'>
  <div class='modal-dialog result-box'>
    <div class='modal-header'>
      <strong><?php echo $lang->upgrade->result;?></strong>
    </div>
    <div class='modal-body'>
      <div class='row'>
        <div class='col-md-6'>
          <div class='message mgb-10 text-center'>
            <strong><?php echo $lang->upgrade->success?></strong>
            <div><?php echo html::a('index.php', $lang->upgrade->tohome, '', "class='btn btn-primary btn-wide' id='tohome'")?></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
$(function()
{
    sessionStorage.removeItem('TID');
    initHideHome();
    finishedShow();
})
<?php
foreach($needProcess as $processKey => $value)
{
    $value = $processKey == 'search' ? 'true' : 'false';
    echo 'var ' . $processKey . "Finish = $value;\n";
}
?>

/**
 * Init hide message.
 *
 * @access public
 * @return void
 */
function initHideHome()
{
    var hide = false;
    <?php
    foreach($needProcess as $processKey => $value)
    {
        if($processKey == 'search') continue;
        echo "hide = true;\n";
        break;
    }
    ?>
    if(hide)
    {
        $('a#tohome').closest('.message').hide();
        $('.result-box').css('height', 'auto').css('position', 'relative').css('margin-top', '0px');
    }
}

/**
 * Finished show message.
 *
 * @access public
 * @return void
 */
function finishedShow()
{
    var show = true;
    <?php
    foreach($needProcess as $processKey => $value)
    {
        echo "if({$processKey}Finish == false) show = false;\n";
    }
    ?>
    if(show)
    {
        $.get('<?php echo inlink('afterExec', "fromVersion=$fromVersion&processed=yes&skipMoveFile=yes")?>');
        $('a#tohome').closest('.message').show();
    }
}

<?php if(isset($needProcess['changeEngine'])):?>
$(function()
{
    $('.col-md-6:first').append("<div id='changeEngineBox' class='alert alert-info'><p><?php echo $lang->upgrade->changeEngine;?></p><div class='info'></div></div>");
    changeEngine();
})
function changeEngine()
{
    var link     = '<?php echo inlink('ajaxChangeTableEngine')?>';
    var $infoBox = $('#changeEngineBox .info');
    $.getJSON(link, function(response)
    {
        if(response == null)
        {
            changeEngineFinish = true;
            finishedShow();
        }
        else if(response.result == 'finished')
        {
            changeEngineFinish = true;
            $infoBox.prepend("<div class='text-success'>" + response.message + "</div>");
            finishedShow();
        }
        else
        {
            if($infoBox.find('.' + response.table).length == 0) $infoBox.prepend("<div class='text-success " + response.table + "'></div>");
            $infoBox.find('.' + response.table).html(response.message);
            if(response.next) $infoBox.prepend("<div class='text-success " + response.next + "'>" + response.nextMessage + "</div>");
            changeEngine();
        }
    });
}
<?php endif;?>
<?php if(isset($needProcess['updateFile'])):?>
$(function()
{
    $('.col-md-6:first').append("<div id='resultBox' class='alert alert-info'><p><?php echo $lang->upgrade->updateFile;?></p></div>");
    updateFile('<?php echo inlink('ajaxUpdateFile')?>');
})
function updateFile(link)
{
    $.getJSON(link, function(response)
    {
        if(response == null)
        {
            updateFileFinish = true;
            finishedShow();
        }
        else if(response.result == 'finished')
        {
            $('#resultBox li span.' + response.type + '-num').html(num + response.count);
            updateFileFinish = true;
            $('#resultBox').prepend("<li class='text-success'>" + response.message + "</li>");
            finishedShow();
        }
        else
        {
            if($('#resultBox li span.' + response.type + '-num').size() == 0 || response.type != response.nextType)
            {
                $('#resultBox').prepend("<li class='text-success'>" + response.message + "</li>");
            }
            var num = parseInt($('#resultBox li span.' + response.type + '-num').html());
            $('#resultBox li span.' + response.type + '-num').html(num + response.count);
            updateFile(response.next);
        }
    });
}
<?php endif;?>
<?php if(isset($needProcess['search'])):?>
$(function()
{
    $('.col-md-6:first').append("<div class='alert alert-info'><p><?php echo $lang->upgrade->needBuild4Add;?></p></div>");
})
<?php endif;?>
</script>
<?php include '../../common/view/footer.lite.html.php';?>
