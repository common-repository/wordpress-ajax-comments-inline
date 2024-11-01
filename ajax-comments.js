var js_len = document.getElementsByTagName('script').length;
for (var i = 0, got_url = -1; i <= js_len && got_url == -1; i++){
	var wapi_jsurl = document.getElementsByTagName('script')[i].src,
			got_url = wapi_jsurl.indexOf('/ajax-comments.js');
}
var wapi_php_url = wapi_jsurl.replace('/ajax-comments.js','/ajax-comments.php');
var wapi_img_url = wapi_jsurl.replace('/ajax-comments.js','/loading.gif');


function showAjaxComments(id) {
     var postid= String(id);
     jQuery(document).ready(function($) {
     if ($("div#ajax-comments-"+postid).html()==""){
     jQuery.ajax({
  	type: 'POST',
  	url: wapi_php_url,
	beforeSend: function(XMLHttpRequest) {$("div#ajax-comments-"+postid).html("<img src='"+wapi_img_url+"' alt='Comments loading,Please wait...' title='Comments loading,Please wait...'>");},
  	data: {id:postid},
  	success: function(data){$("#ajax-comments-"+postid).html(data);},
	error: function(XMLHttpRequest, textStatus, errorThrown){$("#ajax-comments-"+postid).replaceWith("Failed to get comments:" + textStatus);}
     });		
         }else{
		$("#ajax-comments-"+postid).show();
         }
     	$("#show-ajax-comments-"+postid).hide();  $("#hide-ajax-comments-"+postid).show();  
    });
}


function hideAjaxComments(id) {
     var postid= String(id);
     jQuery(document).ready(function($) {
     	$("#hide-ajax-comments-"+postid).hide();  $("#show-ajax-comments-"+postid).show();  $("#ajax-comments-"+postid).hide(); 
     });
}


function quickCommentform(id) {
     var postid= String(id);
     jQuery(document).ready(function($) {
	$("#waci-comment-form-"+postid).show(); $("#show-ajax-comments-form-"+postid).hide();  $("#hide-ajax-comments-form-"+postid).show();  
     });
}

function hideCommentform(id) {
     var postid= String(id);
     jQuery(document).ready(function($) {
	$("#waci-comment-form-"+postid).hide(); $("#hide-ajax-comments-form-"+postid).hide();  $("#show-ajax-comments-form-"+postid).show(); 
     });
}