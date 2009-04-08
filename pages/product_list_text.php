<?php if ($products): ?>
  <?php foreach ($products as $product): ?>
    <div class="parale-product-text">
      <a href="#parale-product-preview_<?php echo $product->id ?>" rel="#parale-product-preview_<?php echo $product->id ?>" title="<?php echo $product->category ?>">
        <?php echo $product->title ?>
      </a>
      <div id="parale-product-preview_<?php echo $product->id ?>" style="display: none">
        <?php echo $product->help ?>
        <p>
          <a href="<?php echo $product->aff_link ?>" title="<?php echo $product->title ?>" class="add_text_to_post">Add to post</a>&nbsp;&nbsp;&nbsp;
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
  jQuery(".parale-product-text a").each(function (i) {
    $(this).cluetip({local: true, cursor: 'pointer', sticky: true, closePosition: 'title', mouseOutClose: true});
	});
//]]>
</script>
