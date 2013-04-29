jQuery(document).ready(function($) {

	//Add / Remove Classes for each array item
	$.each(custom.editing, function(index, value) {

	  	    postid = '#the-list > #post-' + index;
	  	    $(postid).addClass(value);
	});

});//END jQuery
