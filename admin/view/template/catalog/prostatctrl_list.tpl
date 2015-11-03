<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <?php if ($success) { ?>
  <div class="success"><?php echo $success; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/product.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').attr('action', '<?php echo $update; ?>'); $('#form').submit();" class="button"><span><?php echo $button_update; ?></span></a></div>
   </div>
  <div class="content">
    <form method="post" enctype="multipart/form-data" id="form">
      <table class="list">
        <thead>
          <tr>
            <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
			<td class="left"><?php echo $column_username; ?></td>
            <td class="left"><?php echo $column_vendor_name; ?></td>
			<td class="left"><?php echo $column_company; ?></td>
			<td class="left"><?php echo $column_flname; ?></td>
			<td class="left"><?php echo $column_telephone; ?></td>
			<td class="left"><?php echo $column_email; ?></td>
			<td class="left"><?php echo $column_due_date; ?></td>
			<td class="right"><?php echo $column_total; ?></td>			
            <td class="left" width="18%"><?php echo $column_status; ?></td>			
          </tr>
        </thead>
        <tbody>
          <?php if ($users_info) { ?>
          <?php foreach ($users_info as $user_info) { ?>
          <tr>
            <td style="text-align: center;"><?php if ($user_info['selected']) { ?>
              <input type="checkbox" name="selected[]" value="<?php echo $user_info['user_id']; ?>" checked="checked" />
              <?php } else { ?>
              <input type="checkbox" name="selected[]" value="<?php echo $user_info['user_id']; ?>" />
              <?php } ?></td>
			<td class="left"><?php echo $user_info['username']; ?></td>
			<td class="left"><?php echo $user_info['vendor_name']; ?></td>
			<td class="left"><?php echo $user_info['company']; ?></td>
			<td class="left"><?php echo $user_info['flname']; ?></td>
			<td class="left"><?php echo $user_info['telephone']; ?></td>
			<td class="left"><?php echo $user_info['email']; ?></td>
			<td class="left"><?php echo $user_info['due_date']; ?></td>
			<td class="right"><?php echo $user_info['total_products']; ?></td>
			<td class="left"><select name="user_status<?php echo $user_info['user_id']; ?>">
				<?php if ($user_info['status']) { ?>
					<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
					<option value="0"><?php echo $text_disabled; ?></option>
				<?php } else { ?>
					<option value="1"><?php echo $text_enabled; ?></option>
					<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
				<?php } ?>
			</select></td>			
          </tr>
          <?php } ?>
          <?php } else { ?>
          <tr>
            <td class="center" colspan="10"><?php echo $text_no_results; ?></td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    </form>
    <div class="pagination"><?php echo $pagination; ?></div>
  </div>
</div>
<?php echo $footer; ?>