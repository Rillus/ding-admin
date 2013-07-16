var baseUrl = window.baseUrl,
	requestPending = false,
	fields = new Array,
	typeVal,
	viewVal,
	thisTemplate;
$(document).ready(function(){
	
	/* ------- Generic validation stuff ------ */
	var jVal = {
		'toggleElement' : function() {
			$(this).parents().next('.toggle').toggle();
		},
		'addRow' : function(me) {
			if($("#add-row").length > 0){
				console.log('add from template');
				me.prev().append($("#add-row").html());
				me.prev().children(":last").hide().slideDown("slow");
				me.prev().find(".help-inline").slideUp();
			} else if ($("#add.view").length > 0){
				me.prev().children(":first").children(":first").clone().appendTo(me.prev().children());
				me.prev().children(":last").hide().slideDown("slow");
			}
			var num;
			if ($('[name="fieldType[]"]').length > 0){
				num = $('[name="fieldType[]"]').length;
			} else {
				num = $('input[name="fieldCount"]').val();
				num++;
			}
			console.log(num);
			$('input[name="fieldCount"]').val(num);
		},
		'deleteRow' : function(me) {			
			me.parents(".controls-row").slideUp("normal", function() { 
				$(this).remove();
				var num;
				if ($('[name="fieldType[]"]').length > 0){
					num = $('[name="fieldType[]"]').length;
				} else {
					num = $('input[name="fieldCount"]').val();
					num--;
				}
				$('input[name="fieldCount"]').val(num);
			});
		}
	}
	
	$(document).on("click", "#add-button", function(e){
		e.preventDefault();
		jVal.addRow($(this));
	});
	
	/* ------- Add view page ------ */
	var viewType = {
		'addView' : function(result) {
			$("#results").empty();
			if (viewVal == "2"){ // taskboard
				//$("#resultHolder").prev().append("<h3>Add categories to drag between</h3>");
				thisTemplate = $("#dragbox-template").html();
				$("#results").append(thisTemplate);

				if ($("#add-button").length == 0){
					$("#resultHolder").after($("#addButton-template").html());
				}
			} else {
				thisTemplate = $("#table-template").html();
				$("#results").prepend("<h3>Select fields to show</h3>");

				for (var i = 0; i < fields.length; i++){
					var thisField = replaceWithData(thisTemplate, fields[i]);
					$("#results").append(thisField);
					if ($("#add-button").length > 0){
						$("#add-button").remove();
					}
				}
			}
		}
	};
	function jsonRequest(type, val){
		if (val != ''){
			var requestUrl = baseUrl + "json/" + type + "/" + val;
			if (requestPending == false){
				requestPending = true;
				$.ajax({
					dataType: "json",
					url: requestUrl,
					success: function(result) {
						requestPending = false;
						fields = [];
						for (var i = 0; i < result["type"].length; i++){
							fields[i] = new Array;
							fields[i]["name"] = result["name"][i];
							fields[i]["safe_name"] = result["safe_name"][i];
						}
						viewType.addView();
					},
					fail: function() {
						requestPending = false;
						//return false;
					},
				});
			}
		}
	}
	
	if($('#content-type').length > 0){
		$('#view-type').change(function(e){
			typeVal = $('#content-type').val();
			viewVal = $('#view-type').val();
			viewType.addView();
		});
		
		$('#content-type').change(function(e){
			typeVal = $('#content-type').val();
			viewVal = $('#view-type').val();
			jsonRequest("content_type", typeVal);
		});
		
		typeVal = $('#content-type').val();
		viewVal = $('#view-type').val();
		jsonRequest("content_type", typeVal);
	}
	/* ------- Generic template filler ------ */
	// Replace the {{XXX}} with the corresponding property
	function replaceWithData(template, data) {
		var html_template = template, 
			prop;
		for (prop in data) {
			html_template = html_template.replace('{{' + prop + '}}', data[prop]);
		}
		return html_template;
	}
	
	/* ------- Permissions page ------ */
	
	var permissions = {
		'hideTypes' : function() {
			$(".types").toggle();
			$("table tr.header .lastCol").append(" <span class='hider icon-chevron-down'></span>");
			$(document).on("click", ".hider", function(e){
				$(this).toggleClass("icon-chevron-down icon-chevron-up")
				var permClass = "."+$(this).parents().parents().attr("id");
				$(permClass).toggle();
			});
		},
		'checkBoxes' : function() {
			$("table tr.header .lastCol").append("<label class='checkbox'><input type='checkbox'> Select all</label>");
			$(document).on("change", "tr.header .lastCol input", function(e){
				$(this).toggleClass("icon-chevron-down icon-chevron-up");
				var permClass = "."+$(this).parents().parents().parents().attr("id");
				$(permClass+" input").prop("checked", !$(permClass+" input").prop("checked"));
				console.log($(permClass+" input"));
			});
		}
	};
	
	if ($("#permissions").length > 0){
		permissions.checkBoxes();
		permissions.hideTypes();
	}
	
	/* ------- Add node date/time picker ------ */
	if ($(".datepicker").length > 0){
		$(".datepicker").datepicker({ dateFormat: "dd/mm/yy" });
	}
	if ($(".datetimepicker").length > 0){
		$(".datetimepicker").datetimepicker({
			hourGrid: 3,
			minuteGrid: 10, 
			dateFormat: "dd/mm/yy" 
		});
	}
	
	/* ------- Add Content Type page ------ */
	var contentType = {
		'replaceNodeField' : function(me) {
			var descField = $("[name='fieldDescription[]']"),
				selectedDescField = me.find("option:selected[value=8]").parents(".controls-row").find(descField),
				unSelectedDescField = me.find("option:selected[value!=8]").parents(".controls-row").find(descField);

			selectedDescField.replaceWith($("#dropdown-select").html());
			unSelectedDescField.replaceWith($("#description-field").html());
		},
		'nodeReference' : function() {
			var contentType = this;
			$(document).on("change", "[name='fieldType[]']", function(e){
				contentType.replaceNodeField($(this));
			});
		}
	};
	
	if ($(".deleter").length > 0){
		$(document).on("click", ".deleter", function(e){
			console.log('deleter');
			jVal.deleteRow($(this));
		});
	}
	if ($("#dropdown-select").length > 0){
		contentType.nodeReference();
	}
	
	/* ------- Ajax page loading ------ */
	function requestView(url, el, changePage){
		if (document.URL == url){
			return;
		}
		var requestUrl = url;
		if (requestPending == false){
			requestPending = true;
			el.empty().append("<span class='icon-refresh'></span>");
			$.ajax({
				dataType: "html",
				url: requestUrl,
				success: function(result) {
					requestPending = false;
					el.hide().empty().append(result).fadeIn();
					//update body id and class
					if (changePage != false){
						var segments = requestUrl.split('/');
						$("body").attr('id',segments[5]);
						if (segments[6] == ""){
							$("body").removeClass();
						} else {
							$("body").attr('class',segments[6]);
						}
						//document.title = response.pageTitle;
						window.history.pushState({
							"html":result //, "pageTitle":response.pageTitle
						},"", requestUrl);
					}
				},
				fail: function() {
					//return false;
				}
			});
		}
	}
	$(document).on("click", "a.load", function(e){
		e.preventDefault();
		var href = $(this).attr("href");
		if (href != "#"){
			requestView(href, $("#contain"));
		}
	})
	/* ------- Ajax for dashboard ------ */
	if ($("#home").length > 0){
		requestView(baseUrl+"view", $("#home #views"), false);
	}
	// this triggers page load on back button
	window.onpopstate = function(e){
		if(e.state){
			console.log(e.state);
			el = $("#contain");
			el.hide().empty().append(e.state.html).fadeIn();
			//document.title = e.state.pageTitle;
		} /*else {
			if(window.location.href.substr(-2) !== "?r") {
			  window.location = window.location.href + "?r";
			}
		}*/
	};
	
	/* ------- Recipes ------ */
	var recipe = {
		'template' : function(type) {
			var recipe = this,
				name,
				description,
				fieldType = new Array(),
				fieldName = new Array(),
				fieldDesc = new Array(),
				fieldReq = new Array();
				stage0 = $("#stage0").length;
			
			if (type == "project"){
				if (stage0 == 0){
					name = "Project";
					description = "An ongoing project";
					
					fieldType[0] = "2";
					fieldName[0] = "Project description";
					fieldDesc[0] = "A bit about the project";
					fieldReq[0] = "No";
					
					fieldType[1] = "3";
					fieldName[1] = "Start date";
					fieldDesc[1] = "When did the project start?";
					fieldReq[1] = "No";
					
					fieldType[2] = "3";
					fieldName[2] = "Deadline";
					fieldDesc[2] = "Do we have an end date?";
					fieldReq[2] = "No";
					
					fieldType[3] = "5";
					fieldName[3] = "Brand";
					fieldDesc[3] = "Grosvenor, Mecca";
					fieldReq[3] = "No";
					
					fieldType[4] = "9";
					fieldName[4] = "Jira link";
					fieldDesc[4] = "Enter the URL for the Jira task";
					fieldReq[4] = "No";
				} else {
					$("[name='templateType']").val("project");
				}
			} else if (type == "userstory"){
				if (stage0 == 0){
					name = "userstory";
					description = "An ongoing project";
					
					fieldType[0] = "2";
					fieldName[0] = "Project description";
					fieldDesc[0] = "A bit about the project";
					fieldReq[0] = "No";
					
					fieldType[1] = "3";
					fieldName[1] = "Start date";
					fieldDesc[1] = "When did the project start?";
					fieldReq[1] = "No";
					
					fieldType[2] = "3";
					fieldName[2] = "Deadline";
					fieldDesc[2] = "Do we have an end date?";
					fieldReq[2] = "No";
					
					fieldType[3] = "5";
					fieldName[3] = "Brand";
					fieldDesc[3] = "Grosvenor, Mecca";
					fieldReq[3] = "No";
					
					fieldType[4] = "9";
					fieldName[4] = "Jira link";
					fieldDesc[4] = "Enter the URL for the Jira task";
					fieldReq[4] = "No";
				} else {
					$("[name='templateType']").val("userstory");
				}
			} else if (type == "subtask"){
				if (stage0 == 0){
					name = "subtask";
					description = "An ongoing project";
					
					fieldType[0] = "2";
					fieldName[0] = "Project description";
					fieldDesc[0] = "A bit about the project";
					fieldReq[0] = "No";
					
					fieldType[1] = "3";
					fieldName[1] = "Start date";
					fieldDesc[1] = "When did the project start?";
					fieldReq[1] = "No";
					
					fieldType[2] = "3";
					fieldName[2] = "Deadline";
					fieldDesc[2] = "Do we have an end date?";
					fieldReq[2] = "No";
					
					fieldType[3] = "5";
					fieldName[3] = "Brand";
					fieldDesc[3] = "Grosvenor, Mecca";
					fieldReq[3] = "No";
					
					fieldType[4] = "9";
					fieldName[4] = "Jira link";
					fieldDesc[4] = "Enter the URL for the Jira task";
					fieldReq[4] = "No";
				} else {
					$("[name='templateType']").val("subtask");
				}
			} else if (type == "new"){
				if (stage0 != 0){
					$("[name='templateType']").val("new");
				}
			} else {
				console.log('not');
			}
			if (stage0 > 0){
				$('#templatesForm').submit();
				console.log('submit');
			} else {
				// create enough fields for the arrays to fill
				if (fieldType.length > 0){
					for (var i = 0; i < fieldType.length; i++){
						if ($("[name='fieldType[]']").length < fieldType.length){
							while ($("[name='fieldType[]']").length < fieldType.length){
								jVal.addRow($("#add-button"));
							}
						} else {
							while ($("[name='fieldType[]']").length > fieldType.length){
								jVal.deleteRow($(".deleter"));
							}
						}
					}
				}
				// fill the fields
				$("[name='name']").val(name);
				$("[name='description']").val(description);
				recipe.fillTemplates("[name='fieldType[]']", fieldType)
				recipe.fillTemplates("[name='fieldName[]']", fieldName)
				recipe.fillTemplates("[name='fieldDescription[]']", fieldDesc)
				recipe.fillTemplates("[name='fieldRequired[]']", fieldReq)
				
				recipe.hideTemplates();
			}
		},
		'fillTemplates' : function(field, array){
			$(field).each(function( index ) {
				$(this).val(array[index]);
			});
		},
		'hideTemplates' : function(){
			$("#content-templates").slideUp();
			$("#control-title").text("Check the fields below and edit as necessary");
		}
	};
	$(document).on("click", ".large-button.content-template", function(e){
		var el = this,
			type;
		if ($(el).hasClass("project")){
			type = "project";
		} else if ($(el).hasClass("userstory")){
			type = "userstory";
		} else if ($(el).hasClass("subtask")){
			type = "subtask";
		} else if ($(el).hasClass("new")){
			type = "new";
		}
		recipe.template(type);
	});
	if ($("#stage1").length > 0){
		var type = $("[name='templateType']").val();
		if (type != ""){
			recipe.template(type);
		}
	}
});