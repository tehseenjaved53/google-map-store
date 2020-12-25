<div class="wrap">

  <h1 id="add-new-site">Import Data From Knack Database</h1>

  <?php if(is_numeric($_GET['message'])){$message = message_code($_GET['message']);?>

  <style>

.notice-success{display:block !important;border-top: 2px solid rgba(0,0,0,.1);}

</style>

  <div style="border-left-color:<?php echo $message[0]?>" class="updated notice notice-success is-dismissible">

    <p><?php echo $message[1]?></p>

  </div>

  <?php  

  } ?>

  <form method="post" action="">
  	<table class="form-table">

      <tbody>

        <tr class="form-field form-required">

          <td>

          	<span><strong>Google Api Key</strong>:</span> &nbsp;&nbsp;&nbsp;

            <input type="text" placeholder="Google API Key" style="background-color: #fff;border: 1px solid #ccc;width:50%;" value="<?php echo get_option( 'op_google_api_key'); ?>" required="required" name="op_google_api_key" > &nbsp;&nbsp;&nbsp;

            <input type="submit" class="button button-primary" name="import_data" value="Import Data"> 

          </td>

        </tr>

        

      </tbody>

    </table>
    <input type="hidden" name="page" value="import_knack_data" />
  </form>
  
  <br /><br /><hr style="width:100%;"><br /><br />
  <form method="post" enctype="multipart/form-data" class="data_grid_list" onsubmit="">

  <a href="<?php echo admin_url('/network/admin.php?page=import_knack_data&show_data=yes'); ?>">Show All Data</a>

  <input type="hidden" name="page" value="import_knack_data" />

    <table class="form-table">

      <tbody>

        <tr class="form-field form-required">

          <td>

          	<span><strong>Select CSV</strong>:</span> &nbsp;&nbsp;&nbsp;

            <input type="file" placeholder="Select PDF File" id="pdf_file" style="background-color: #fff;border: 1px solid #ccc;" value="" name="upload_attachment" > &nbsp;&nbsp;&nbsp;

            <input type="submit" class="button button-primary" name="import_data" value="Import Data"> 

          </td>

        </tr>

        

      </tbody>

    </table>

  </form>

</div>

