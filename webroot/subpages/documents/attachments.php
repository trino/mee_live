<?php
 if($this->request->session()->read('debug')){ echo "<span style ='color:red;'>subpages/documents/attachments.php #INC131</span>";}
 ?>
<?php
$is_disabled = '';//there is no place for attachments
if(isset($disabled)) { $is_disabled = 'disabled="disabled"'; }

echo '<form role="form" enctype="multipart/form-data" action="' . $this->request->webroot . 'documents/addattachment/' . $cid . '/' . $did . '" method="post" id="form_tab'. $dx->id.'">';

?>


    <div class="row">
    <input type="hidden" class="document_type" name="document_type" value="<?php echo $dx->title;?>"/>
    <input type="hidden" name="sub_doc_id" value="<?php echo $dx->id;?>" class="sub_docs_id" id="af" />
    <div class="form-group col-md-12" style="margin-top:20px;">
            <label class="control-label col-md-3">Title</label>
            <div class="col-md-6">
                <input type="text" class="form-control" name="title" <?php echo $is_disabled;?> value="<?php if(isset($mod->title)){echo $mod->title;} ?>" />
            </div>
           
           
    </div>
     
    </div>


 <div class="addattachment<?php echo $dx->id;?> form-group col-md-12"></div>
  </form>
 <div class="clearfix"></div>
<script>
$(function()
{
    $('#form_tab<?php echo $dx->id;?>').find("a").show();
 });
     
    
</script>


