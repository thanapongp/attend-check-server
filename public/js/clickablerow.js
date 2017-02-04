$(".clickable-row").click(function() {
	window.location = $(this).data("href");
});

// store the currently selected tab in the hash value
$("ul.nav-tabs > li > a").on("shown.bs.tab", function(e) {
  	var id = $(e.target).attr("href").substr(1);
  	window.location.hash = id;
});

$(window).on('hashchange',function(){ 
    $('#coursetab a[href="' + window.location.hash + '"]').tab('show');
});

// on load of the page: switch to the currently selected tab
var hash = window.location.hash;
if (hash) {
	window.scrollTo(0, 0);
	$('#coursetab a[href="' + hash + '"]').tab('show');
}