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
      <h1><img src="view/image/order.png" alt="" /> <?php echo $heading_title; ?></h1>
	  <div class="buttons"><a onclick="$('#form').attr('action', '<?php echo $sendEmail; ?>'); $('#form').submit();" class="button"><span><?php echo $button_sendEmail; ?></span></a><a onclick="$('#form').attr('action', '<?php echo $update; ?>'); $('#form').submit();" class="button"><span><?php echo $button_update; ?></span></a><a onclick="$('#form').attr('action', '<?php echo $delete; ?>'); $('#form').submit();" class="button"><span><?php echo $button_delete; ?></span></a></div>
	 </div>
    <div class="content">
      <form action="" method="post" enctype="multipart/form-data" id="form">
        <table class="list" id="history">
          <thead>
            <tr>
	            <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
				<td class="left"><?php echo $column_contract_id; ?></td>
				<td class="left"><?php echo $column_username; ?></td>
                <td class="left"><?php echo $column_vendor_name; ?></td>
                <td class="left"><?php echo $column_signup_plan; ?></td>
				<td class="left"><?php echo $column_signup_amount; ?></td>
				<td class="left"><?php echo $column_status; ?></td>
				<td class="left"><?php echo $column_remaining_days; ?></td>
				<td class="left"><?php echo $column_date_start; ?></td>
				<td class="left"><?php echo $column_date_end; ?></td>				
                <td class="left"><?php echo $column_paid_status; ?></td>
            </tr>
          </thead>
          <tbody>
             <?php if ($histories) { ?>
              <?php foreach ($histories as $signup_history) { ?>
                <tbody id="history_<?php echo $signup_history['signup_id']; ?>">
				  <tr>
					<td style="text-align: center;">
					<?php if ($signup_history['selected']) { ?>
					  <input type="checkbox" name="selected[]" value="<?php echo $signup_history['signup_id']; ?>" checked="checked" />
					  <?php } else { ?>
					  <input type="checkbox" name="selected[]" value="<?php echo $signup_history['signup_id']; ?>" />
					<?php } ?>
					</td>
					<td class="right"><?php echo $signup_history['signup_id']; ?></td>
					<td class="left"><input type="hidden" name="user_id<?php echo $signup_history['signup_id']; ?>" value="<?php echo $signup_history['user_id']; ?>" /><?php echo $signup_history['username']; ?></td>
					<td class="left"><?php echo $signup_history['vendor_name']; ?></td>
					<td class="left"><?php echo $signup_history['signup_plan']; ?></td>
					<td class="left"><?php echo $signup_history['signup_fee']; ?></td>
					<td class="left"><?php echo $signup_history['status']; ?></td>
					<td class="left"><input type="hidden" name="remaining_days<?php echo $signup_history['signup_id']; ?>" value="<?php echo $signup_history['remaining_days']; ?>" /><?php echo $signup_history['remaining_days']; ?></td>
					<td class="left"><?php echo $signup_history['date_start']; ?></td>
					<td class="left"><input type="hidden" name="date_end<?php echo $signup_history['signup_id']; ?>" value="<?php echo $signup_history['date_end']; ?>" /><?php echo $signup_history['date_end']; ?></td>					
					<td class="left"><select name="paid_status<?php echo $signup_history['signup_id']; ?>">
									<?php if ($signup_history['paid_status']) { ?>
									<option value="1" selected="selected"><?php echo $text_completed; ?></option>
									<option value="0"><?php echo $text_pending; ?></option>
									<?php } else { ?>
									<option value="1"><?php echo $text_completed; ?></option>
									<option value="0" selected="selected"><?php echo $text_pending; ?></option>
									<?php } ?>
									</select></td>
				  </tr>
				</tbody>
              <?php } ?>
              <?php } else { ?>
              <tr>
                <td class="center" colspan="11"><?php echo $text_no_results; ?></td>
              </tr>
             <?php } ?>
          </tbody>
        </table>
      </form>
      <div class="pagination"><?php echo $pagination; ?></div>
    </div>
  </div>
</div>
<?php echo $footer; ?>