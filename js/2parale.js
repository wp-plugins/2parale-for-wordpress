jQuery(document).ready(function() 
{	
	jQuery("#2parale-search-submit").click(onInputEntered);
	jQuery("#parale_pagination a").live("click", onInputEnteredWithPage);	
	jQuery(".add_text_to_post").live("click", addTextToEditor);
	jQuery(".add_banner_to_post").live("click", addBannerToEditor);
	jQuery(".add_url_to_post").live("click", addUrlToEditor);
});

function addBannerToEditor(event) 
{
  title = $(this).attr('title');
  href = $(this).attr('href');
  image = $(this).attr('image');
  text = '<div><dl class="wp-caption alignright" style="width: 140px;"><dt class="wp-caption-dt"><a href="'+ href +'"><img title="'+ title +'" src="'+ image +'" alt="'+ title +'" /></a></dt><dd class="wp-caption-dd" style="font-size: 0.8em;">'+ title +'</dd></dl></div></div>Write text here...';
  send_to_editor(text);
  event.preventDefault();
}

function addTextToEditor(event) 
{
  title = $(this).attr('title');
  href = $(this).attr('href');
	send_to_editor('<a name="' + title + '" href="' + href + '">' + title + '</a>');
	event.preventDefault();
}

function addUrlToEditor(event) 
{
  href = $(this).attr('href');
	send_to_editor(href);
	event.preventDefault();
}

function onInputEntered(event) 
{
	event.preventDefault();
	jQuery("#2parale-result").html('<img src="../wp-includes/js/thickbox/loadingAnimation.gif" id="2parale-loading-image" />');
	jQuery.post(
		jQuery("#2parale-siteurl").attr("value") + "/wp-admin/admin-ajax.php", 
		{ action: "2Parale_for_wordpress", 'cookie': encodeURIComponent(document.cookie), 'campaign': jQuery("#parale_campaign").attr("value"), 'search': jQuery("#parale_search").attr("value"), 'searchtype': jQuery("#parale_search_type").attr("value") }, 
		function(data, textStatus) {
			jQuery("#2parale-result").html(data);
		});
	return false;
}

function onInputEnteredWithPage(event)
{
  event.preventDefault();
	jQuery("#2parale-result").html('<img src="../wp-includes/js/thickbox/loadingAnimation.gif" id="2parale-loading-image" />');
	jQuery.post(
		jQuery("#2parale-siteurl").attr("value") + "/wp-admin/admin-ajax.php", 
		{ action: "2Parale_for_wordpress", 'cookie': encodeURIComponent(document.cookie), 'campaign': jQuery("#parale_campaign").attr("value"), 'search': jQuery("#parale_search").attr("value"), 'searchtype': jQuery("#parale_search_type").attr("value"), 'page': $(this).attr("href") }, 
		function(data, textStatus) {
			jQuery("#2parale-result").html(data);
		});
	return false;
}