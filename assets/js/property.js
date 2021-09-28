/*
* Property plugin js
*/
jQuery(document).ready(function(){
	console.log('Plugin js loaded');
	// properties load more
	jQuery('#properties-loadmore').on('click',function(){
		console.log('loadmore clicked.');
		let currentPage = jQuery('#property-listing-page').val();
		let perPage = jQuery('#property-listing-perpage').val();
		let loadmoreBtn = jQuery(this);
		let ajaxData = {
      'action'	: 'properties_loadmore',
      'paged'		: currentPage,
      'posts_per_page'		: perPage
    };
    console.log(ajaxData);
    jQuery.ajax({
      url: properties_ajax_params.ajaxurl,
      data: ajaxData,
      dataType : 'json',
      type : 'POST',
      beforeSend: function(xhr){
        console.clear();
        console.log('ajax process');
        loadmoreBtn.text('Loading...');
      },
      success: function(data){
      	let response = data.data;
        if(response.posts_found == false){
           loadmoreBtn.text('No More Posts');
           setTimeout(function(){
             loadmoreBtn.remove();
           },3000);
         }else{
           loadmoreBtn.closest('div').before(response.properties);
           loadmoreBtn.text('Load More');
           currentPage++;
           jQuery('#property-listing-page').val(currentPage);
         }

      },
      failure: function(error){
        console.log('error');
        loadmoreBtn.closest('div').before('There was some error');
      }
    });
	});
});