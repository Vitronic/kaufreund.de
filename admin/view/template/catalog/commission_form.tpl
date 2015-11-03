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
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/product.png" alt="" /> <?php echo $heading_title; ?></h1>
    <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
  </div>
  <div class="content">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <table class="form">
		<tr>
          <td><span class="required">*</span> <?php echo $entry_name; ?></td>
          <td><input name="commission_name" value="<?php echo $commission_name; ?>" size="25" />
            <?php if ($error_commission_name) { ?>
            <span class="error"><?php echo $error_commission_name; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_type; ?></td>
          <td><select name="commission_type">
				<?php if (!$commission_type) { ?>
					<option value="0" selected="selected"><?php echo $text_percentage; ?></option>
					<option value="1"><?php echo $text_fixed_rate; ?></option>
					<option value="2"><?php echo $text_pf; ?></option>
					<option value="3"><?php echo $text_fp; ?></option>
					<option value="4"><?php echo $text_month; ?></option>
					<option value="5"><?php echo $text_year; ?></option>
                <?php } elseif ($commission_type == '1') { ?>
					<option value="0"><?php echo $text_percentage; ?></option>
					<option value="1" selected="selected"><?php echo $text_fixed_rate; ?></option>
					<option value="2"><?php echo $text_pf; ?></option>
					<option value="3"><?php echo $text_fp; ?></option>
					<option value="4"><?php echo $text_month; ?></option>
					<option value="5"><?php echo $text_year; ?></option>
				<?php } elseif ($commission_type == '2') { ?>
					<option value="0"><?php echo $text_percentage; ?></option>
					<option value="1"><?php echo $text_fixed_rate; ?></option>
					<option value="2"  selected="selected"><?php echo $text_pf; ?></option>
					<option value="3"><?php echo $text_fp; ?></option>
					<option value="4"><?php echo $text_month; ?></option>
					<option value="5"><?php echo $text_year; ?></option>
				<?php } elseif ($commission_type == '3') { ?>
					<option value="0"><?php echo $text_percentage; ?></option>
					<option value="1"><?php echo $text_fixed_rate; ?></option>
					<option value="2"><?php echo $text_pf; ?></option>
					<option value="3" selected="selected"><?php echo $text_fp; ?></option>
					<option value="4"><?php echo $text_month; ?></option>
					<option value="5"><?php echo $text_year; ?></option>
				<?php } elseif ($commission_type == '4') { ?>
					<option value="0"><?php echo $text_percentage; ?></option>
					<option value="1"><?php echo $text_fixed_rate; ?></option>
					<option value="2"><?php echo $text_pf; ?></option>
					<option value="3"><?php echo $text_fp; ?></option>
					<option value="4" selected="selected"><?php echo $text_month; ?></option>
					<option value="5"><?php echo $text_year; ?></option>
				<?php } elseif ($commission_type == '5') { ?>
					<option value="0"><?php echo $text_percentage; ?></option>
					<option value="1"><?php echo $text_fixed_rate; ?></option>
					<option value="2"><?php echo $text_pf; ?></option>
					<option value="3"><?php echo $text_fp; ?></option>
					<option value="4"><?php echo $text_month; ?></option>
					<option value="5" selected="selected"><?php echo $text_year; ?></option>
                <?php } ?>
              </select></td>
        </tr>		
		<tr>
          <td id="commission"><span class="required">*</span> <?php echo $entry_commission; ?></td>
          <td><input name="commission" value="<?php echo $commission; ?>" size="25" />
            <?php if ($error_commission) { ?>
            <span class="error"><?php echo $error_commission; ?></span>
            <?php } ?></td>
        </tr>
		<tr>
		  <td><span class="required">*</span> <?php echo $entry_duration; ?></td>
		  <td><input name="duration" value="<?php echo $duration; ?>"/></td>
		</tr>
		<tr> 
		  <td><span class="required">*</span> <?php echo $entry_limit; ?></td>
		  <td><select name="product_limit_id">
			<?php foreach($product_limits as $product_limit) { ?>
              <?php if ($product_limit['product_limit_id'] == $product_limit_id) { ?>
				<option value="<?php echo $product_limit['product_limit_id']; ?>" selected="selected"><?php echo $product_limit['package_name'] . ' (' . $product_limit['product_limit'] . ')'; ?></option>
			  <?php } else { ?>
                <option value="<?php echo $product_limit['product_limit_id']; ?>"><?php echo $product_limit['package_name']  . ' (' . $product_limit['product_limit'] . ')'; ?></option>
              <?php } ?>
            <?php } ?>
		    </select></td>
		</tr>
		<tr>
          <td><?php echo $entry_sort_order; ?></td>
          <td><input name="sort_order" value="<?php echo $sort_order; ?>" size="1" /></td>
        </tr>
      </table>
    </form>
  </div>
</div>
<script type="text/javascript"><!--
$('select[name=\'commission_type\']').bind('change', function() {
	if (this.value == '4' || this.value == '5') {
		$('#commission').html('<span class="required">*</span><?php echo $entry_subscription; ?>');
	} else {
		$('#commission').html('<span class="required">*</span><?php echo $entry_commission; ?>');
	}
});
$('select[name=\'commission_type\']').trigger('change');
//--></script>
<?php echo $footer; ?>