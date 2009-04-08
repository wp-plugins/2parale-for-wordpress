<div id="parale-search">
<p>
  <label for="parale_campaign"><?php _e( 'Shop' , '2parale-for-wordpress'); ?></label> 
  <select id="parale_campaign" name="parale_campaign">
    <option value=""></option>
    <?php foreach ($this->get_active_campaigns() as $campaign): ?>
      <option value="<?php echo $campaign->id ?>"><?php echo $campaign->name ?></option>
    <?php endforeach ?>
  </select>
  <a title="<?php echo _e('To get more shops go to 2parale.ro and sign-up for more campaigns. ') ?>">?</a>
</p>
<p>
  <label for="parale_search"><?php _e( 'Terms' , '2parale-for-wordpress'); ?></label>
  <input type="text" id="parale_search" name="parale_search" /> in 
  <select id="parale_search_type" name="parale_search_type">
    <option value="banner">Banner</option>
    <option value="text">Text</option>
  </select>  
</p>
<input type="hidden" id="2parale-siteurl" name="2parale-siteurl" value="<?php echo bloginfo( 'wpurl' );	?>" />
<p class="submit">
  <input type="submit" value="<?php _e( 'Search' , '2parale-for-wordpress'); ?>" id="2parale-search-submit" name="2parale-search-submit" /></p>
</div>
<div id="2parale-result"></div>
<br class="clear" />