// input field
function showResults(str) {
	if (str.length==0) { 
		$("#posts").empty();
		return;
	}
	$.ajax({
		dataType: "jsonp",
		url: "http://search.twitter.com/search.json?q="+encodeURIComponent(str),
		success: function(result) {
			//$("#posts").empty();
			for (var i = 0; i < result.results.length; i++){
				var thisTweet = replaceWithData($("#tweet-template").html(), result.results[i]);
				$("#posts .cleft").before(thisTweet);
				//console.log(thisTweet);
			}
		},
		fail: function() {
			return false;
		}
	});
}

// Replace the {{XXX}} with the corresponding property
function replaceWithData(template, data) {
	var html_template = template, 
		prop;
	for (prop in data) {
		if (data.hasOwnProperty(prop)) {
			html_template = html_template.replace('{{' + prop + '}}', data[prop]);
		}
	}
	return html_template;
}
$(document).ready(function() {
	$("#go").click(function(){
		showResults($("#search").val());
	})
});
console.log ($(document).scrollTop() != $(document).height());
/*if ($(document).scrollTop() != $(document).height()){
	$('html, body').animate({
		scrollTop: $(document).height()
	}, 10000);
	if (){
		$('html, body').stop(true);
	}
}*/