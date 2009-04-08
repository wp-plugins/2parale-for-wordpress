<?php if ($products): ?>
  <?php foreach ($products as $product): ?>
    <div class="parale-product">
      <a href="#parale-product-preview_<?php echo $product->id ?>" rel="#parale-product-preview_<?php echo $product->id ?>" title="<?php echo $product->category ?>">
        <img src="<?php echo $product->{'thumb-path'} ?>" class="parale-product-image" style="width: 63px; "/>
      </a>
      <div id="parale-product-preview_<?php echo $product->id ?>" style="display: none; text-align: center;">
        <div style="margin: 0 0 5px 0;">
          <img src="<?php echo $product->{'thumb-path'} ?>" class="parale-product-image" />
        </div>
        
        <?php echo $product->help ?>
        
        <p>
          <a href="<?php echo $product->aff_link ?>" title="<?php echo $product->help ?>" image="<?php echo $product->{'thumb-path'} ?>" class="add_banner_to_post">Add to post</a>&nbsp;&nbsp;&nbsp;
          <a href="<?php echo $product->aff_link ?>" class="add_url_to_post">Add url</a>&nbsp;&nbsp;&nbsp;
          <a href="<?php echo $product->url ?>" target="_blank">View original</a>
        </p>
      </div>
    </div>
  <?php endforeach ?>
  <?php echo getPaginationString($page, $total, DOUAPARALE_PER_PAGE, 1, '', '') ?>
<?php else: ?>
  No products found.
<?php endif ?>

<script type="text/javascript">  
//<![CDATA[
  <?php require_once('cluetip.js.php') ?>
  jQuery(".parale-product a").each(function (i) {
    $(this).cluetip({local: true, cursor: 'pointer', sticky: true, closePosition: 'title', mouseOutClose: true});
	});
//]]>
</script>