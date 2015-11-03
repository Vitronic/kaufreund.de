<?php echo $header; ?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if ($this->config->get('sign_up')) { ?>
  <h1><?php echo $heading_title; ?></h1>
  <p><?php echo $text_account_already; ?></p>
  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
    <h2><?php echo $text_your_details; ?></h2>
    <div class="content">
      <table class="form">
		<tr>
          <td><span class="required">*</span> <?php echo $entry_username; ?></td>
          <td><input type="text" name="username" value="<?php echo $username; ?>" />
            <?php if ($error_username) { ?>
            <span class="error"><?php echo $error_username; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_firstname; ?></td>
          <td><input type="text" name="firstname" value="<?php echo $firstname; ?>" />
            <?php if ($error_firstname) { ?>
            <span class="error"><?php echo $error_firstname; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_lastname; ?></td>
          <td><input type="text" name="lastname" value="<?php echo $lastname; ?>" />
            <?php if ($error_lastname) { ?>
            <span class="error"><?php echo $error_lastname; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_email; ?></td>
          <td><input type="text" name="email" value="<?php echo $email; ?>" />
            <?php if ($error_email) { ?>
            <span class="error"><?php echo $error_email; ?></span>
            <?php } ?></td>
        </tr>
		<tr>
          <td><?php echo $entry_bank_name; ?></td>
          <td><input type="text" name="bank_name" value="<?php echo $bank_name; ?>" /></td>
        </tr>
		<tr>
          <td><?php echo $entry_iban; ?></td>
          <td><input type="text" name="iban" value="<?php echo $iban; ?>" /></td>
        </tr>
		<tr>
          <td><?php echo $entry_swift_bic; ?></td>
          <td><input type="text" name="swift_bic" value="<?php echo $swift_bic; ?>" /></td>
        </tr>
		<tr>
          <td><?php echo $entry_tax_id; ?></td>
          <td><input type="text" name="tax_id" value="<?php echo $tax_id; ?>" /></td>
        </tr>
		<tr>
          <td><?php echo $entry_bank_address; ?></td>
          <td><input type="text" name="bank_address" value="<?php echo $bank_address; ?>" /></td>
        </tr>
		<tr>
          <td><span class="required">*</span> <?php echo $entry_paypal; ?></td>
          <td><input type="text" name="paypal" value="<?php echo $paypal; ?>" />
            <?php if ($error_paypal) { ?>
            <span class="error"><?php echo $error_paypal; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_telephone; ?></td>
          <td><input type="text" name="telephone" value="<?php echo $telephone; ?>" />
            <?php if ($error_telephone) { ?>
            <span class="error"><?php echo $error_telephone; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_fax; ?></td>
          <td><input type="text" name="fax" value="<?php echo $fax; ?>" /></td>
        </tr>
      </table>
    </div>
    <h2><?php echo $text_your_address; ?> </h2>
    <div class="content">
      <table class="form">
        <tr>
          <td><span class="required">*</span> <?php echo $entry_company; ?></td>
          <td><input type="text" name="company" value="<?php echo $company; ?>" /></td>
        </tr>
		<tr>
          <td><?php echo $entry_company_id; ?></td>
          <td><input type="text" name="company_id" value="<?php echo $company_id; ?>" /></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_address_1; ?></td>
          <td><input type="text" name="address_1" value="<?php echo $address_1; ?>" />
            <?php if ($error_address_1) { ?>
            <span class="error"><?php echo $error_address_1; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_address_2; ?></td>
          <td><input type="text" name="address_2" value="<?php echo $address_2; ?>" /></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_city; ?></td>
          <td><input type="text" name="city" value="<?php echo $city; ?>" />
            <?php if ($error_city) { ?>
            <span class="error"><?php echo $error_city; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_postcode; ?></td>
          <td><input type="text" name="postcode" value="<?php echo $postcode; ?>" />
            <?php if ($error_postcode) { ?>
            <span class="error"><?php echo $error_postcode; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_country; ?></td>
          <td><select name="country_id" onchange="$('select[name=\'zone_id\']').load('index.php?route=account/signup/zone&country_id=' + this.value + '&zone_id=<?php echo $zone_id; ?>');">
              <option value=""><?php echo $text_select; ?></option>
              <?php foreach ($countries as $country) { ?>
              <?php if ($country['country_id'] == $country_id) { ?>
              <option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select>
            <?php if ($error_country) { ?>
            <span class="error"><?php echo $error_country; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_zone; ?></td>
          <td><select name="zone_id">
            </select>
            <?php if ($error_zone) { ?>
            <span class="error"><?php echo $error_zone; ?></span>
            <?php } ?></td>
        </tr>
		<?php if ($this->config->get('signup_show_plan')) { ?>
		<tr>
          <td><span class="required">*</span> <?php echo $entry_plan; ?></td>
          <td><select name="singup_plan" id="singup_plan">
		  <?php foreach ($singup_plans as $singup_plan) { ?>
			<?php if ($singup_plan['commission_id'] != '1') { ?>
			<option value="<?php echo $this->encryption->encrypt($singup_plan['commission_id']); ?>:<?php echo $this->encryption->encrypt($singup_plan['commission_type']); ?>:<?php echo $this->encryption->encrypt($singup_plan['product_limit_id']); ?>:<?php echo $this->encryption->encrypt($singup_plan['duration']); ?>:<?php echo $this->encryption->encrypt($singup_plan['commission']); ?>"><?php if ($singup_plan['commission_type'] == '0') { ?><?php echo $singup_plan['commission_name'] . ' (' . $singup_plan['commission'] . '%) - ' . $singup_plan['product_limit'] . $text_products; ?></option>				
			<?php } elseif ($singup_plan['commission_type'] == '1') { ?><?php echo $singup_plan['commission_name'] . ' (' . $this->currency->format($singup_plan['commission'], $this->config->get('config_currency')) . ') - ' . $singup_plan['product_limit'] . $text_products; ?></option>
			<?php } elseif ($singup_plan['commission_type'] == '2') { ?><?php $data = explode(':',$singup_plan['commission']); ?><?php echo $singup_plan['commission_name'] . ' (' . $data[0] . '% + ' . $data[1] . ') - ' . $singup_plan['product_limit'] . $text_products; ?></option>
			<?php } elseif ($singup_plan['commission_type'] == '3') { ?><?php $data = explode(':',$singup_plan['commission']); ?><?php echo $singup_plan['commission_name'] . ' (' . $data[0] . ' + ' . $data[1] . '%) - ' . $singup_plan['product_limit'] . $text_products; ?></option>
			<?php } elseif ($singup_plan['commission_type'] == '4') { ?><?php echo $singup_plan['commission_name'] . ' (' . $this->currency->format($singup_plan['commission'], $this->config->get('config_currency')) . ') - ' . $singup_plan['product_limit'] . $text_products; ?></option>
			<?php } elseif ($singup_plan['commission_type'] == '5') { ?><?php echo $singup_plan['commission_name'] . ' (' . $this->currency->format($singup_plan['commission'], $this->config->get('config_currency')) . ') - ' . $singup_plan['product_limit'] . $text_products; ?></option>
			<?php } ?>			
			<?php } ?>
		  <?php } ?><input type="hidden" name="hsignup_plan" id="hsignup_plan" value="" />
		  </select>		  
		  </td>		
        </tr>
		<?php } else { ?>
			
		    <input type="hidden" name="singup_plan" value="<?php echo $default_commission; ?>" />
			<select hidden name="singup_plan" id="singup_plan">
		    <?php foreach ($singup_plans as $singup_plan) { ?>
			<?php if ($singup_plan['commission_id'] != '1') { ?>			
			<option value="<?php echo $this->encryption->encrypt($singup_plan['commission_id']); ?>:<?php echo $this->encryption->encrypt($singup_plan['commission_type']); ?>:<?php echo $this->encryption->encrypt($singup_plan['product_limit_id']); ?>:<?php echo $this->encryption->encrypt($singup_plan['duration']); ?>:<?php echo $this->encryption->encrypt($singup_plan['commission']); ?>"><?php if ($singup_plan['commission_type'] == '0') { ?><?php echo $singup_plan['commission_name'] . ' (' . $singup_plan['commission'] . '%) - ' . $singup_plan['product_limit'] . $text_products; ?></option>				
			<?php } elseif ($singup_plan['commission_type'] == '1') { ?><?php echo $singup_plan['commission_name'] . ' (' . $this->currency->format($singup_plan['commission'], $this->config->get('config_currency')) . ') - ' . $singup_plan['product_limit'] . $text_products; ?></option>
			<?php } elseif ($singup_plan['commission_type'] == '2') { ?><?php $data = explode(':',$singup_plan['commission']); ?><?php echo $singup_plan['commission_name'] . ' (' . $data[0] . '% + ' . $data[1] . ') - ' . $singup_plan['product_limit'] . $text_products; ?></option>
			<?php } elseif ($singup_plan['commission_type'] == '3') { ?><?php $data = explode(':',$singup_plan['commission']); ?><?php echo $singup_plan['commission_name'] . ' (' . $data[0] . ' + ' . $data[1] . '%) - ' . $singup_plan['product_limit'] . $text_products; ?></option>
			<?php } elseif ($singup_plan['commission_type'] == '4') { ?><?php echo $singup_plan['commission_name'] . ' (' . $this->currency->format($singup_plan['commission'], $this->config->get('config_currency')) . ') - ' . $singup_plan['product_limit'] . $text_products; ?></option>
			<?php } elseif ($singup_plan['commission_type'] == '5') { ?><?php echo $singup_plan['commission_name'] . ' (' . $this->currency->format($singup_plan['commission'], $this->config->get('config_currency')) . ') - ' . $singup_plan['product_limit'] . $text_products; ?></option>
			<?php } ?>			
			<?php } ?>
		  <?php } ?><input type="hidden" name="hsignup_plan" id="hsignup_plan" value="" />
		<?php } ?>		
		<tr>
          <td><?php echo $entry_store_url; ?></td>
          <td><input type="text" name="store_url" value="<?php echo $address_2; ?>" size="68" /></td>
        </tr>
		<tr>
          <td><?php echo $entry_store_description; ?></td>
          <td><textarea name="store_description" cols="68" rows="8" ></textarea></td>
        </tr>
      </table>
    </div>
    <h2><?php echo $text_your_password; ?></h2>
    <div class="content">
      <table class="form">
        <tr>
          <td><span class="required">*</span> <?php echo $entry_password; ?></td>
          <td><input type="password" name="password" value="<?php echo $password; ?>" />
            <?php if ($error_password) { ?>
            <span class="error"><?php echo $error_password; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_confirm; ?></td>
          <td><input type="password" name="confirm" value="<?php echo $confirm; ?>" />
            <?php if ($error_confirm) { ?>
            <span class="error"><?php echo $error_confirm; ?></span>
            <?php } ?></td>
        </tr>
      </table>
    </div>    
    <?php if ($text_agree) { ?>
    <div class="buttons">
      <div class="right"><?php echo $text_agree; ?>
        <?php if ($agree) { ?>
        <input type="checkbox" name="agree" value="1" checked="checked" />
        <?php } else { ?>
        <input type="checkbox" name="agree" value="1" />
        <?php } ?>
        <input type="submit" value="<?php echo $button_sign_up; ?>" class="button" />
      </div>
    </div>
    <?php } else { ?>
    <div class="buttons">
      <div class="right">
        <input type="submit" value="<?php echo $button_sign_up; ?>" class="button" />
      </div>
    </div>
    <?php } ?>
	<?php } else { ?>
		<h1><?php echo $heading_title; ?></h1>
		<h2><?php echo $text_close_sign_up; ?></h2>
	<?php } ?>
  </form>
  <?php echo $content_bottom; ?></div>

<script type="text/javascript"><!--
$("#hsignup_plan").val($("#singup_plan option:selected").text());
$('select[name=\'singup_plan\']').change(function () { 
$("#hsignup_plan").val($("#singup_plan option:selected").text()); 
});
//--></script>

<script type="text/javascript"><!--
$('select[name=\'zone_id\']').load('index.php?route=account/signup/zone&country_id=<?php echo $country_id; ?>&zone_id=<?php echo $zone_id; ?>');
//--></script>
 
<script type="text/javascript"><!--
$(document).ready(function() {
	$('.colorbox').colorbox({
		width: 640,
		height: 480
	});
});
//--></script>
<?php echo $footer; ?>