<div class="wrap"><br />

  <h1 id="add-new-site">Update Data From Knack Database</h1><br /><br />
  <?php global $wpdb;if(is_numeric($_GET['message'])){$message = message_code($_GET['message']);?>
  <style>

.notice-success{display:block !important;border-top: 2px solid rgba(0,0,0,.1);}

</style>
  <div style="border-left-color:<?php echo $message[0]?>" class="updated notice notice-success is-dismissible">
    <p><?php echo $message[1]?></p>
  </div>
  <?php  

  } ?>
  <?php $sql = $wpdb->get_results("select * from op_knack_db "); ?>
  <form method="post" enctype="multipart/form-data" class="data_grid_list" onsubmit="">
    
    <input type="hidden" name="page" value="update_knack_data" />
    <table class="wp-list-table widefat fixed striped ">
    <?php if(isset($_GET['edit']) and is_numeric($_GET['edit'])){ ?><colgroup><col width="20%" /><col width="80%" /></colgroup><?php } ?>
      <?php if(!isset($_GET['edit']) and $_GET['edit'] == ''){ ?>
      <thead>
<tr class="form-field form-required">
        <td><span><strong>Franchise Number</strong></span></td>
        <td><span><strong>Owner Name</strong></span></td>
        <td><span><strong>Website Title</strong></span></td>
        <td><span><strong>Update Title</strong></span></td>
        <td><span><strong>Zip Code</strong></span></td>
        <td><span><strong>Dated</strong></span></td>
        <td><strong>Action</strong></td>
      </tr>
</thead>
      <tbody>
        <?php if(!empty($sql)){ 
	  	foreach($sql as $data){?>
        <tr class="form-field form-required">
        
          <td><span class="listdata"><?php echo $data->franchise_number ?></span></td>
          <td><span class="listdata"><?php echo $data->owner_name ?></span></td>
          <td><span class="listdata"><?php echo $data->website_title ?></span></td>
          <td><span class="listdata"><?php echo $data->updated_title ?></span></td>
          <td><span class="listdata"><?php echo $data->zip ?></span></td>
          <td><span class="listdata"><?php echo date('F d, Y', strtotime($data->dated)) ?></span></td>
          <td><strong><a href="<?php echo admin_url('/network/admin.php?page=update_knack_data&edit='.$data->id); ?>">Edit</a></strong></td>
        </tr>
        <?php }
	
	
	 }else{ ?>
        <tr class="form-field form-required">
          <td colspan="7"><span class="listdata">
            <h4 style="text-align:center" class="error_location">Record Not Found.</h4>
            </span></td>
        </tr>
        <?php } ?>
      </tbody>
      <?php }else{ 
		
		$row = $wpdb->get_row("Select * from op_knack_db where id = '".$_GET['edit']."' "); 
		if(!empty($row)){
			echo '<tr><td></td><td><br /><input type="submit" class="op_button button button-primary" name="update_data" value="Save Data"> &nbsp;&nbsp;&nbsp;
						  <a class="op_button button button-primary" style="background:#d84d4d !important" href="'.admin_url('/network/admin.php?page=update_knack_data').'">Cancel Update</a><br /><br /></td></tr>';
			foreach($row as $key => $val){
			
			if($key == 'id'){?>
        <tr>
          <td colspan="2"><input type="hidden" value="<?php echo $val; ?>" name="<?php echo $key ?>" ></td>
         </tr>
       <?php }
	   		elseif($key == 'notes'){?>
        <tr>
          <td><span class="label"><strong><?php echo str_replace('_', ' ', $key) ?></strong>:</span></td>
          <td> <textarea class="op_text" name="<?php echo $key ?>" rows="8" ><?php echo $val; ?></textarea>
            &nbsp;&nbsp;&nbsp; </td>
         </tr>
       <?php }
	   		else{?>
        <tr>
          <td><span class="label"><strong><?php echo str_replace('_', ' ', $key) ?></strong>:</span></td>
          <td> <input type="text" class="op_text" value="<?php echo $val; ?>" name="<?php echo $key ?>" >
            &nbsp;&nbsp;&nbsp; </td>
         </tr>
       <?php }
		}
			echo '<tr><td></td><td ><br /><br /><input type="submit" class="op_button button button-primary" name="update_data" value="Save Data">&nbsp;&nbsp;&nbsp;
						  <a class="op_button button button-primary" style="background:#d84d4d !important" href="'.admin_url('/network/admin.php?page=update_knack_data').'">Cancel Update</a><br /><br /></td></tr>';
		}
			?>
        <?php } ?>
    </table>
  </form>
</div>
<style>.op_button{font-size: 18px !important;padding: 8px 30px !important;height: auto !important;font-weight: 500;}.op_text{background-color: #fff;border: 1px solid #ccc;width:50%;padding: 8px;font-size: 15px;}span.label {text-transform: capitalize;font-size: 15px;}</style>