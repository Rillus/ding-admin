var taskBoard = {
    
    jQuery : $,
    
    settings : {
        columns : '.column',
        widgetSelector: '.widget',
        handleSelector: '.widget-head',
        contentSelector: '.widget-content',
        widgetDefault : {
            movable: true,
            removable: false,
            collapsible: true,
            collapsed: true,
            editable: true,
            colorClasses : ['color-grey', 'color-lime', 'color-green', 'color-blue', 'color-purple', 'color-pink', 'color-red', 'color-orange']
        },
        widgetIndividual : {
            intro : {
                movable: false,
                removable: false,
                collapsible: false,
                editable: false
            }
        }
    },

    init : function () {
        // this.attachStylesheet('taskBoard.js.css');
        this.addWidgetControls();
        this.makeSortable();
    },
    
    getWidgetSettings : function (id) {
        var $ = this.jQuery,
            settings = this.settings;
        return (id&&settings.widgetIndividual[id]) ? $.extend({},settings.widgetDefault,settings.widgetIndividual[id]) : settings.widgetDefault;
    },
	
    sendData : function (me) {
		me = me.parents(this.settings.widgetSelector);
		var taskBoard = this,
			myId = me.attr('id'),
			colorStylePattern = /\bcolor-[\w]{1,}\b/,
			processing,
			timeout,
			href = location.pathname;
		myId = myId.substring(6);
		processing = false;
		timeout = setTimeout(function(){
			if (!processing) {
				processing=true;
				request =
					$.ajax({  
						type: 'POST',  
						url: window.baseUrl+'post/view_settings/'+href.substr(href.lastIndexOf('/') + 1), 
						data: {
							id: myId,
							colour: me.attr('class').match(colorStylePattern)[0],
							column: me.parents(taskBoard.settings.columns).attr('id').substring(6),
							order: me.parents(taskBoard.settings.columns).find("li.widget").index(me)
						},
						success: function(response) {
							processing=false;
							console.log(response+' ok');
						}
					});
			}
		}, 1000);
	},
	
    addWidgetControls : function () {
        var taskBoard = this,
            $ = this.jQuery,
            settings = this.settings;
            
        $(settings.widgetSelector, $(settings.columns)).each(function () {
            var thisWidgetSettings = taskBoard.getWidgetSettings(this.id);
            if (thisWidgetSettings.removable) {
                $('<a href="#" class="remove">CLOSE</a>').mousedown(function (e) {
                    e.stopPropagation();    
                }).click(function () {
                    if(confirm('This widget will be removed, ok?')) {
                        $(this).parents(settings.widgetSelector).animate({
                            opacity: 0    
                        },function () {
                            $(this).wrap('<div/>').parent().slideUp(function () {
                                $(this).remove();
                            });
                        });
                    }
                    return false;
                }).appendTo($(settings.handleSelector, this));
            }
            
            if (thisWidgetSettings.editable) {
                $('<a href="#" class="edit">EDIT</a>').click(function (e) {
                    e.preventDefault();    
					if ($(this).parents(settings.widgetSelector).find('.edit-box').filter(":visible").length == 0){
						$(this).css({backgroundPosition: '-66px 0', width: '55px'})
							.parents(settings.widgetSelector)
							.find('.edit-box').show().find('input').focus();
						$(this).parents(settings.widgetSelector).find('h3').hide();
					} else {
						$(this).css({backgroundPosition: '', width: ''})
							.parents(settings.widgetSelector)
							.find('.edit-box').hide();
						$(this).parents(settings.widgetSelector).find('h3').show();
					}
				}).appendTo($(settings.handleSelector,this));
                $('<div class="edit-box" style="display:none;"/>')
                    //.append('<ul><li class="item"><label>Change the title?</label><input value="' + $('h3',this).text() + '"/></li>')
                    .append((function(){
                        var colorList = '<li class="item"><label>Available colors:</label><ul class="colors">';
                        $(thisWidgetSettings.colorClasses).each(function () {
                            colorList += '<li class="' + this + '"/>';
                        });
                        return colorList + '</ul>';
                    })())
                    .append('</ul>')
                    .insertAfter($(settings.handleSelector,this));
            }
            
            if (thisWidgetSettings.collapsible) {
                $('<a href="#" class="collapse">COLLAPSE</a>').click(function (e) {
                    e.preventDefault();  
					if ($(this).parents(settings.widgetSelector).find(settings.contentSelector).filter(":visible").length > 0){
						$(this).css({backgroundPosition: '-38px 0'})
							.parents(settings.widgetSelector)
							.find(settings.contentSelector).hide();
					} else {
						$(this).css({backgroundPosition: ''})
							.parents(settings.widgetSelector)
							.find(settings.contentSelector).show();
					}					
				}).prependTo($(settings.handleSelector,this));
				
				if (thisWidgetSettings.collapsed){
					$(this).find(settings.contentSelector).hide();
					$(this).find(".collapse").css({backgroundPosition: '-38px 0'})
				}
            }
        });
        
        $('.edit-box').each(function () {
            $('input',this).keyup(function () {
                $(this).parents(settings.widgetSelector).find('h3').text( $(this).val().length>20 ? $(this).val().substr(0,20)+'...' : $(this).val() );
            });
            $('ul.colors li',this).click(function () {
                
                var colorStylePattern = /\bcolor-[\w]{1,}\b/,
                    thisWidgetColorClass = $(this).parents(settings.widgetSelector).attr('class').match(colorStylePattern)
                if (thisWidgetColorClass) {
                    $(this).parents(settings.widgetSelector)
                        .removeClass(thisWidgetColorClass[0])
                        .addClass($(this).attr('class').match(colorStylePattern)[0]);
					console.log ($(this).parents(settings.widgetSelector).attr('id'));
					
					taskBoard.sendData($(this));
					
                }
                return false;
                
            });
        });
        
    },
    
    attachStylesheet : function (href) {
        var $ = this.jQuery;
        return $('<link href="' + href + '" rel="stylesheet" type="text/css" />').appendTo('head');
    },
    
    makeSortable : function () {
        var taskBoard = this,
            $ = this.jQuery,
            settings = this.settings,
            $sortableItems = (function () {
                var notSortable = '';
                $(settings.widgetSelector,$(settings.columns)).each(function (i) {
                    if (!taskBoard.getWidgetSettings(this.id).movable) {
                        if(!this.id) {
                            this.id = 'widget-no-id-' + i;
                        }
                        notSortable += '#' + this.id + ',';
                    }
                });
                return $('> li:not(' + notSortable + ')', settings.columns);
            })();
        
        $sortableItems.find(settings.handleSelector).css({
            cursor: 'move'
        }).mousedown(function (e) {
            $sortableItems.css({width:''});
            $(this).parent().css({
                width: $(this).parent().width() + 'px'
            });
        }).mouseup(function () {
            if(!$(this).parent().hasClass('dragging')) {
                $(this).parent().css({width:''});
            } else {
                $(settings.columns).sortable('disable');
				taskBoard.sendData($(this));
            }
        });

        $(settings.columns).sortable({
            items: $sortableItems,
            connectWith: $(settings.columns),
            handle: settings.handleSelector,
            placeholder: 'widget-placeholder',
            forcePlaceholderSize: true,
            revert: 300,
            delay: 100,
            opacity: 0.8,
            containment: 'document',
            start: function (e,ui) {
                $(ui.helper).addClass('dragging');
            },
            stop: function (e,ui) {
                $(ui.item).css({width:''}).removeClass('dragging');
                $(settings.columns).sortable('enable');
            }
        });
    }
  
};

taskBoard.init();