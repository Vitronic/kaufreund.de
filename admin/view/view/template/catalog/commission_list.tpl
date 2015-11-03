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
      <div class="buttons"><a onclick="location = '<?php echo $insert; ?>'" class="button"><span><?php echo $button_insert; ?></span></a><a onclick="$('form').submit();" class="button"><span><?php echo $button_delete; ?></span></a></div>
   </div>
  <div class="content">
    <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
      <table class="list">
        <thead>
          <tr>
            <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
			<td class="left"><?php if ($sort == 'commission_name') { ?>
              <a href="<?php echo $sort_commission_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_commission_name; ?>"><?php echo $column_name; ?></a>
              <?php } ?></td>
            <td class="left"><?php if ($sort == 'commission_type') { ?>
              <a href="<?php echo $sort_commission_type; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_type; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_commission_type; ?>"><?php echo $column_type; ?></a>
              <?php } ?></td>
			<td class="left"><?php if ($sort == 'commission') { ?>  
			  <a href="<?php echo $sort_commission; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_commission; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_commission; ?>"><?php echo $column_commission; ?></a>
              <?php } ?></td>
			<td class="right"><?php echo $column_total_vendors; ?></td>
            <td class="right"><?php if ($sort == 'sort_order') { ?>
              <a href="<?php echo $sort_sort_order; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_sort_order; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_sort_order; ?>"><?php echo $column_sort_order; ?></a>
            <?php } ?></td>
            <td class="right"><?php echo $column_action; ?></td>
          </tr>
        </thead>
        <tbody>
          <?php if ($commissions) { ?>
          <?php foreach ($commissions as $commission) { ?>
          <tr>
            <td style="text-align: center;"><?php if ($commission['selected']) { ?>
              <input type="checkbox" name="selected[]" value="<?php echo $commission['commission_id']; ?>" checked="checked" />
              <?php } else { ?>
              <input type="checkbox" name="selected[]" value="<?php echo $commission['commission_id']; ?>" />
              <?php } ?></td>
			<td class="left"><?php echo $commission['commission_name']; ?></td>
			<td class="left"><?php echo $commission['commission_type']; ?></td>
			<td class="left"><?php echo $commission['commission']; ?></td>
			<td class="right"><?php echo $commission['total_vendors']; ?></td>
			<td class="right"><?php echo $commission['sort_order']; ?></td>
            <td class="right"><?php foreach ($commission['action'] as $action) { ?>
              [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
              <?php } ?></td>
          </tr>
          <?php } ?>
          <?php } else { ?>
          <tr>
            <td class="center" colspan="7"><?php echo $text_no_results; ?></td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    </form>
    <div class="pagination"><?php echo $pagination; ?></div>
  </div>
</div>
<?php echo $footer; ?>