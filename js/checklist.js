var checklist = {
    
    jQuery : $,

    init : function () {
        var checklist = this;
		$(document).on("click", "[type='checkbox']", function(e){
			checklist.sendData($(this));
		});
    },
	
    sendData : function (me) {
		var checklist = this,
			myId = me.attr('value'),
			colorStylePattern = /\bcolor-[\w]{1,}\b/,
			processing,
			timeout,
			checked = "0",
			href = location.pathname;
		if (me.is(':checked')){
			checked = "1";
		}
		processing = false;
		timeout = setTimeout(function(){
			if (!processing) {
				processing=true;
				request =
					$.ajax({  
						type: 'POST',  
						url: window.baseUrl+'post/checklist_settings/'+href.substr(href.lastIndexOf('/') + 1), 
						data: {
							id: myId,
							check: checked
						},
						success: function(response) {
							processing=false;
							console.log(response+' ok');
							if (checked == "1"){
								me.parents("tr").addClass('error');
								me.parents("td").append('<span class="hide">1</span>');
							} else {
								me.parents("tr").removeClass('error');
								me.parents("td").children(".hide").remove();
							}
						}
					});
			}
		}, 5);
	}
};

checklist.init();