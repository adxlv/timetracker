__FOUNDATION_LOADED = false;
__MENU_HOVER_TIMEOUT = false
 
$('.app-menu').hover( 
	function(e){
		if (__MENU_HOVER_TIMEOUT) clearTimeout(__MENU_HOVER_TIMEOUT)
		__MENU_HOVER_TIMEOUT = setTimeout(function() {
			$('.app-container').addClass('show-menu')
			clearTimeout(__MENU_HOVER_TIMEOUT)
			__MENU_HOVER_TIMEOUT = false;
		}, 2000);
	},
	function () {
		if (__MENU_HOVER_TIMEOUT) clearTimeout(__MENU_HOVER_TIMEOUT)
		__MENU_HOVER_TIMEOUT = setTimeout(function() {
			$('.app-container').removeClass('show-menu')
			clearTimeout(__MENU_HOVER_TIMEOUT)
			__MENU_HOVER_TIMEOUT = false;
		}, 300)
		
		// if (__MENU_HOVER_TIMEOUT) clearTimeout(__MENU_HOVER_TIMEOUT)	
	}
)
$('.app-menu').click( function(e){
	$('.app-container').addClass('show-menu')
})
$('.app-menu a').off().click( function(e){
	if($(this).parents('li').hasClass('disabled')) {
		e.preventDefault();
		e.stopPropagation();
	} else {
		$(this).parents('li').addClass('active').siblings('li').removeClass('active')
		$('.app-container').removeClass('show-menu')
		e.stopPropagation();
	}
})

// Remove the class that restricts CSS animations
$(window).load(function() {
	console.log('Loaded!!!')
  $("body").removeClass("preload-animations-disabled");
});

function CheckIfNeedRelogin($scope) {
	// console.log($scope);
	// for (o_name in $scope) {
	// 	if ( typeof $scope[o_name] === 'object' && o_name.search(/\$/)==-1 && o_name!='this' ) {
	// 		var obj = _($scope[o_name]).toArray()
	// 		console.log( obj ) 
	// 	}
	// }
}
function LoadFoundation() {
	// RemoveTooltips();
	$(document).foundation();
}
function LoadSortable() {
	console.log('LoadSortable');
	$('.sortable').sortable({
		items: '.full-row',
		handle: '.handle',
		forcePlaceholderSize: true,
	}).bind('sortupdate', function(e, ui) {
		console.log('e',e);
		console.log('ui',ui);
	});
}

function RemoveTooltips() {
	$('.tooltip').remove()
}
function AngularTabClickOverride() {
	$('.tabs a').click(function(e){
		var direction = ($('.tabs .active').index() < $(this).parent().index())?'leave-left':'leave-right';
		$('.tabs-content').removeClass('leave-left leave-right').addClass(direction)
	})
	
}

function FoundationTabCallbackOverride() {
	$('.tabs').on('toggled', function (event, tab) {
		var currentSelect  = $('.tabs-content .active.foundation')
		var targetSelect   = $('#' + $(event.currentTarget).children('dd.active').data('tab-panel'))

		var curID = currentSelect.attr('id').replace('panel-','')
		var tarID = targetSelect.attr('id').replace('panel-','')
		
		console.log(curID,tarID);
		if (curID>tarID) {
			targetSelect.addClass('to-left no-anim')
			currentSelect.addClass('to-right no-anim')

			delay_action()
		} else if (curID<tarID) {
			targetSelect.addClass('to-right no-anim')
			currentSelect.addClass('to-left no-anim')

			delay_action()
		}
		console.log(currentSelect,targetSelect);

		function delay_action() {
			this.timer = this.timer || null
			setTimeout(function() {
				targetSelect.removeClass('no-anim')
				currentSelect.removeClass('no-anim')

				targetSelect.addClass('active')
				currentSelect.removeClass('active')

				if (this.timer) {clearTimeout(this.timer)};
				this.timer = setTimeout(function() {
					currentSelect.removeClass('to-right to-left')
					targetSelect.removeClass('to-right to-left')
				}, 500);
			}, 10);
		}
	});
}

function All_FilterMenuOpen_Setup() {
	$('.filter-button').each(function(i,el){
		var $filterBtn = $(el)
		var $filterMnu = $filterBtn.parents('thead').find('.filter-row .button-group')


		$filterBtn.off().click(function(){
			if ($(this).hasClass('active')) {
				$(this).removeClass('active')
				$filterMnu.css({'margin-top':-100})
			} else {
				$(this).addClass('active')
				$filterMnu.css({'margin-top':0})
			}
		})
	})
}

function Estimate_SetActive(id){
	console.log('Estimate_SetActive');
	$('.estimate-list .estimate.active').removeClass('active')
	if(typeof id !== 'undefined') $('.estimate-list .estimate[data-estimate-id='+id+']').addClass('active')

}

function EstimateEdit_JobRoles_Events_Setup($scope) {

		$('.change-hours-callback').off('change').change(function(){
			console.log('change-hours-callback')
			var obj = {
				id 			:$(this).data("taskid"),
				taskroleid 	:$(this).data("taskroleid"),
				hours 		:$(this).val()||null,
			}
			$scope.changeHours(obj);
		})

		$('.change-taskname-callback').off('change').change(function(){
			console.log('change-taskname-callback')
			var obj = {
				id 			:$(this).data("entryid"),
				title 		:$(this).val(),
			}
			$scope.changeTaskname(obj);
		})

		$('#estimate-new-name-input').keypress(function(event){
			if (event.which == 13) {
				$(this).next('.button').click()
			}
		})

		$('.jobroles-salary-button').off().click(function(e){
			if ($(this).hasClass('active')) {
				$(this).removeClass('active')
				$('.jobs-roles-salary-edit-row').removeClass('show')
			} else {
				$(this).addClass('active')
				$('.jobs-roles-salary-edit-row').addClass('show')
			}
		})
}
function EstimateEdit_Expences_Events_Setup($scope) {

		$('.change-exp-callback').off('change').change(function(){
			console.log('change-exp-callback')
			var $par = $(this).parents('tr')
			var obj = {
				id 			:$(this).data("expid"),
				title 		:$par.find('.title-input').val(),
				units 		:$par.find('.units-input').val()||null,
				qty 		:$par.find('.qty-input').val()||null,
				price 		:$par.find('.price-input').val()||null,
			}
			// console.log(obj);
			$scope.changeExp(obj);

		})
		$('#estimate-new-exp-name-input').keypress(function(event){
			if (event.which == 13) {
				$(this).next('.button').click()
			}
		})
}
function EstimateEdit_BindEstimates_Events_Setup($scope) {
	var _LOCAL_SOMETHING_CHANGED = false
	$('.app-bound-other-estimates-content .bind-estimate').off("click").click(function(e){
		e.preventDefault();
		_LOCAL_SOMETHING_CHANGED = true;

		if ($(this).hasClass('binded')) {
			$(this).removeClass('binded')
			$(this).find('span').html('bind')
			// LoadFoundation()
			$scope.unbind_estimate($(this).data("estimateid"))
		} else {
			$(this).addClass('binded')
			$(this).find('span').html('unbind')
			// $(this).attr('title','Unind')
			// LoadFoundation()
			$scope.bind_estimate($(this).data("estimateid"))
		}
	})
	$(document).on('closed.fndtn.reveal', '[data-reveal]', function () {
		if(_LOCAL_SOMETHING_CHANGED) {
			$scope.update_estimates()
			_LOCAL_SOMETHING_CHANGED = false
		}
	});
}
function EstimateEdit_JobRoles_Click_Setup($scope) {
	$('.app-groups-content .grid .grid-item').off("click").click(function(){
		var id = $(this).data("id")
		if ($(this).hasClass('selected')) {
			$(this).removeClass('selected')
			$scope.estimate.involved_roles.id.splice($scope.estimate.involved_roles.id.indexOf(id),1)
			$scope.$digest()
		} else {
			$(this).addClass('selected')
			$scope.estimate.involved_roles.id.push(id)
			$scope.$digest()
		}
	})
}
function EstimateEdit_JobRoles_Filter($scope) {
	var $r = $scope.estimate.involved_roles.id
	$('.app-groups-content .grid .grid-item').each(function(){
		for (key in $r) {
			if ($(this).data("id")==$r[key]) $(this).addClass('selected')
		}
	})
}
function Projects_BindOnhoverEffects(element) {
	var element = element || '.get-menu' 
	if (typeof _HOVERTIMER === 'undefined') _HOVERTIMER = false;

	function closeItem(item) {
		// console.log(item);
		$(item).off('mouseleave').off('mouseenter')
		$(item).find('.edit-button-group').removeClass('show');
		clearTimeout(_HOVERTIMER)
	}
	/*

	$(element).mouseenter(function(){
		var _that = this;
		if (_HOVERTIMER) { clearTimeout(_HOVERTIMER) }
		_HOVERTIMER = setTimeout(function() {
			$(_that).find('.edit-button-group').addClass('show')
			clearTimeout(_HOVERTIMER)
		}, 1000);
	})
	
	$(element).mouseleave(function(){
		if (_HOVERTIMER) clearTimeout(_HOVERTIMER)
		$(this).find('.edit-button-group').removeClass('show')
	})

	// */
	$(element).off().click(function(event){
		
		if ( $(this).parent().find('.edit-button-group').hasClass('show') ) {
			console.log('Returning');
			$('.edit-button-group').removeClass('show')
			return
		}
		$('.edit-button-group').removeClass('show')

		$(this).parent().find('.edit-button-group').addClass('show')
		$(this).parent().off('mouseleave').off('mouseenter').mouseleave(function(){
			// console.log('Leave');
			var me = this
			_HOVERTIMER = setTimeout(function(){
				closeItem(me)
			},1500)
		}).mouseenter(function() {
			// console.log('Enter');
			if (_HOVERTIMER) clearTimeout(_HOVERTIMER)
		})
	}).parent().off('contextmenu').on('contextmenu',function(event){
		// console.log('SecondClick',$(this).find(element));
		$(this).find(element).click()
		return false;
	})
}
function ProjectsOpen_Display_Setup($scope) {
	$('.picture-container').each(function(i,el){
		// console.log($(el).find('div').size());
		$(el).width( ($(el).find('img').size() * 40) + 45);
	})
}
function ProjectsOpen_Click_Setup($scope) {
	$('.done-button').click(function(){
		if ( $(this).hasClass('success') ) {
			$(this).removeClass('success')
			$(this).parents('tr').removeClass('is-done')
			$scope.done_task($(this).data('taskid'),false)
		} else {
			$(this).addClass('success')
			$(this).parents('tr').addClass('is-done')
			$scope.done_task($(this).data('taskid'),true)
		}
	})
}




function DashboardMy_Dragger_Setup($scope) {
	var $scope = $scope
	$(document).on('opened.fndtn.reveal', '[data-reveal]', function () {
		switch ( $(this).attr('id') ) {
			case 'time-select-modal': 
				new Tasks_Dragger_Modal($scope,this).open()
				break;

			case 'user-add-new-task-modal': 
				new Tasks_AddNewTask_Modal($scope,this).open()
				break;
		}
	});
	
	$(document).on('closed.fndtn.reveal', '[data-reveal]', function () {
		switch ( $(this).attr('id') ) {
			case 'time-select-modal': 
				new Tasks_Dragger_Modal($scope,this).close()
				break;

			case 'user-add-new-task-modal': 
				new Tasks_AddNewTask_Modal($scope,this).close()
				break;
		}
	});
}
function Dashboard_D3($scope) {
	console.log($scope.chartdata.done_pie);
	
	c3.generate({
		bindto: '#chart-spent',
	    data: {
	        columns : $scope.chartdata.done_pie,
	        type    : 'donut'
	    },
	    donut: {
	        // title       : "Spent time on client ",
	        // onclick     : function (d, i) { console.log(d, i); },
	        // onmouseover : function (d, i) { console.log(d, i); },
	        // onmouseout  : function (d, i) { console.log(d, i); }
	    }
	});

	c3.generate({
		bindto: '#chart-estimates',
	    data: {
	        columns : $scope.chartdata.estimate_pie,
	        type    : 'donut'
	    },
	    donut: {
	        // title       : "Spent time on client ",
	        // onclick     : function (d, i) { console.log(d, i); },
	        // onmouseover : function (d, i) { console.log(d, i); },
	        // onmouseout  : function (d, i) { console.log(d, i); }
	    }
	});
}
function Dashboard_D3_SingleProject($scope) {
	//Done vs Sold Total
	c3.generate({
		bindto: '#chart-project-overview-min',
	    data: {
	        columns : $scope.chartdata.project_overview_pie,
	        type    : 'bar',
	        groups: [
	            // ['Done', 'Sold']
	        ]
	    },
	    grid: {
	        y: {
	            show: true
	        }
	    },
	    axis : {
		    y : {
	            tick: { format: function (y) {
	            	var f = d3.format(".2r")
	             	return f(y/60)+'h'
	            }}
		    },
	        x : {
	            tick: { format: function (x) {return ''} }
		    },
	    }
	});

	//Overview by Roles
	c3.generate({
		bindto: '#chart-project-overview-roles',
	    data: {
	        json: $scope.chartdata.project_overview_time_graph,
	        keys: {
	        	x: 'name',
	        	value: ['Done','Sold'],
	        },
	        type    : 'bar',
	        groups: [
	            // ['done', 'sold']
	        ],
	        colors: {
	            'Done': '#CCC',
	            'Sold': '#555',

	            '3': '#72A73D',
	            '4': '#C93B75',
	            '5': '#4D4EC9',
	            '6': '#FBD25C',
        	}
	    },
	    bar: {
			width: {
				ratio: 0.8 // this makes bar width 50% of length between ticks
			}
		},
	    grid: {
	        y: {
	            show: true
	        }
	    },
	    axis : {
		    y : {
	            tick: { format: function (y) {
	            	var f = d3.format(".2r")
	             	return f(y/60)+'h'
	            }}
		    },
	        x : {
	            type: 'category',
	            // tick: { format: function (x) {return ''} },
		    },
	    },
	});
	// chart.resize({
	// 	height: 340
	// });

	//Overview
	c3.generate({
		bindto: '#chart-project-overview',
        data: {
			x: 'x',
            columns: $scope.chartdata.project_overview,
            type:'area',
            // type:'step',
            // type:'bar',
            names: {
					'1':"PV",
		            '2':"Radošais direktors",
		            '3':"Tekstu autors",
		            '4':"Dizaineris",
		            '5':"Maketētājs",
		            '6':"Programmētājs",
				},
		        colors: {
		            '1': '#C9674A',
		            '2': '#4AC9A5',
		            '3': '#72A73D',
		            '4': '#C93B75',
		            '5': '#4D4EC9',
		            '6': '#FBD25C',
		        },
		        hide: ['1a','1b','1_','1']

        },
        tooltip: {
		format: {
			value: function(v,ratio,id) {
					var f = d3.format(".2r")
			    	if (id[1]=='b') {
			    		var v = v + json[id[0]+'a']
			    	}
			     	return f(v/60)+'h'
				}
			}
		},
		legend: {},
        axis: {
            x: {
            	type: 'timeseries',
            	// type: 'category',
            	tick: {
            		// rotate: 15,
	           		format: '%Y-%m-%d'
	        	},
            },
            y : {
		            tick: { 
		            	format: function (v) {
		            		var f = d3.format(".2r")
		            		// var f = d3.format("f")
		             		return f(v/60)+'h'
		            	}
		        	},
		            // padding: {top: 0, bottom: 0}
			    },
        },
		subchart: {
			// show: true
		}
    });
}

function Dashboard_OnClick_Setup($scope) {
	$('.estimate-row-title').off().click(function(){
		var $that = $(this).next('.estimate-details')
		if( $that.hasClass('closed') ) {
			$that.removeClass('closed')
		} else {
			$that.addClass('closed')
		}
	})
}
function DashboardCascade_OnClick_Setup() {
	$('.row-title').off().click(function(){
		if( $(this).hasClass('open') ) {
			$(this).removeClass('open')
			$(this).next('.row-child').hide(100);

			$(this).next('.row-child').find('.row-title').removeClass('open')
			$(this).next('.row-child').find('.row-child').hide(100);
		} else {
			$(this).addClass('open')
			$(this).next('.row-child').show(100);
		}
	})
}


function Tasks_ShowHideDone_Click_Setup() {
	$('.done-task-filter').click(function(){
		if ( $(this).hasClass('filter-on') ) {
			$(this).removeClass('filter-on')
			$('table.done-task-filter-on').addClass('done-task-filter-off').removeClass('done-task-filter-on')
		} else {
			$(this).addClass('filter-on')
			$('table.done-task-filter-off').addClass('done-task-filter-on').removeClass('done-task-filter-off')
		}

	})
}
function Tasks_AddNewTask_Modal($scope,_this) {
	this.open = function() {
		var options = {
			keys: ['client.title','title'],
		}
		var arr = _($scope.projects).first($scope.projects.length)
		$scope.Fuse = new Fuse(arr, options);
		console.log($scope.projects,arr);
	}
	this.close = function() {
		$(_this).find('.modal-error-area').fadeOut(0)
		$(_this).find('.fuzzy-searchresults table').fadeOut(0)
		$(_this).find('.postfix').removeClass('success').find('i').fadeOut(0)
		try {
			$scope.newTask.jobroles_o = null
			$scope.newTask.title = ''
			$scope.fuzzy.selected = ''
			$scope.fuzzy.searchstring = ''
		} catch(err) {

		}

	}
}
function Tasks_Info_Show_Edit(){
	$('a.edit-page-task-info').click(function(){
		var $that = this
		$('.info-edit-row').addClass('show')
	})

	$('.info-edit-row').find('a.cancel-edit').click(function(){
		$('.info-edit-row').removeClass('show')
	})
}
function Tasks_BindUsersModal_Setup() {
	console.log('Started Doing things');

	$('.search-col input').off('onfocus','onblur').focus(function() {
		console.log('Interaction Focus');
	})
}

function Tasks_Dragger_Modal($scope) {
	var $scope = $scope
	this.open = function() {
		$('.override').click(function(){
			$('.slider').css({
				top:-150
			})
			$('.manual-override').css({
				top:-95
			})
			// For Testing - goback timeout
			// setTimeout(function() {	$('.slider').css({top:0}); $('.manual-override').css({top:150}) }, 2000);
		})

		var dragger = new Dragdealer('time-select-slider', {
			slide:false,
			css3:true,
			animationCallback: function(x, y) {
				// var minutes = Math.round(480*Math.pow(x,2))
				var minutes = Math.round(480*x)
					minutes = Math.round(minutes/15) * 15
				var time = moment().startOf('hour').hour(0).minute(minutes);
				// $('#time-select-slider .value').text(time.hours()+':'+time.format("mm"));
				

				$scope.hours = time.hours()
				$scope.minutes = time.minutes() || '00'


				$('.digital-clock .hour').text(time.hours());
				$('.digital-clock .minute').text( Math.round(time.minutes()) || '00' );
				
				// $scope.$digest()

				$('.digital-clock .display').css({
					left:x*_TIMESELECTSLIDER_WIDTH - x*_DIGITALCLOCKDISPLAY_WIDTH
				})
			},
			ready: function() {
				//set some globals because DOM queries are slooooow
				_TIMESELECTSLIDER_WIDTH = $('#time-select-slider').width()
				_DIGITALCLOCKDISPLAY_WIDTH = 20
					$('.digital-clock .display > *').each(function(){
						_DIGITALCLOCKDISPLAY_WIDTH += $(this).width()
					})
					$('.digital-clock .display').width(_DIGITALCLOCKDISPLAY_WIDTH)

				// if ($scope.passobject.timelog.exist) {  }
			}
		});
		
		// var draggerPosition = Math.sqrt($scope.passobject.timelog.minutes/480)
		var draggerPosition = $scope.passobject.timelog.minutes/480
		if (draggerPosition>1) {
			setTimeout(function() { 
				$('.override').click(); 
				var time = moment().startOf('hour').hour(0).minute($scope.passobject.timelog.minutes);
				console.log(time);
				$scope.hours = time.hours();
				$scope.minutes = Math.round(time.minutes()/15) * 15 || '00'
				$scope.$digest()
			}, 110);	
		} else {
			dragger.setValue( draggerPosition ,0 )
		}
	}
	this.close = function() {
		console.log($(this));
		$('.slider').css({
			top:0
		})
		$('.manual-override').css({
			top:150
		})
	}
}

function Reports_users_Click_Setup($scope) {
	$('.user-picture-row a.click-bind').off().click(function(){
		$scope.open_user($(this).data('userid'))

		$(this).parent('div').siblings('.each-user').removeClass('active')
		$(this).parent('div').addClass('active')

	})
}
function Reports_bonus_Dragger_Setup(increase) {
	if (increase) {
		if (typeof loading_dragger === 'undefined') Reports_bonus_Dragger_Setup()
		var cur = loading_dragger.getValue()
		// if (cur[0]==0) {
		// 	loading_dragger.reflow()
		// }
		cur[0] += increase
		loading_dragger.setValue( cur[0] , cur[1] )

		if (cur[0]>=1) {
			setTimeout(function() {
				$('.loading-row .columns').fadeOut(500, function() {
					delete loading_dragger;
				});
			}, 2000);
		}

		return cur[0];
	} else {
		loading_dragger = new Dragdealer('loading-slider', {
				slide:false,
				css3:false,
				animationCallback: function(x, y) {
					$('#loading-slider').find('.handle h6').text(Math.round(x * 100)+'%')
				},
				ready: function() {
					//set some globals because DOM queries are slooooow
				}
		});
		loading_dragger.disable()
	}
}
function Reports_bonus_D3() {

	var makeBarChart = function(mins,title,json,id,period) {
		// console.log('json::',json);
		var period = parseInt(period)
		console.log('period::',period);
		
		c3.generate({
			bindto: id,
		    data: {
		        json : json,
		        type : 'bar',
		        order: null,
				labels: {
					format: function (v,id) {
		            	var f = d3.format("f")
		            	// if (id>10) {
		            	// 	var v = v + json[id[1]]
		            	// }
		            	// console.log('ID::',id);
		            	if (typeof id !== 'undefined' && id[1]=='b') {
		            		var v = v + json[id[0]+'a']
		            	}
		             	return f(v/60)+'h'
		            }
				},
				bar: {
					width: {
					    width: '100' // this makes bar width 50% of length between ticks
					}
				},
				groups: [
					['1a', '1b'],
		            ['2a', '2b'],
		            ['3a', '3b'],
		            ['4a', '4b'],
		            ['5a', '5b'],
		            ['6a', '6b'],
		            ['9a', '9b'],
				],
				names: {
					'1a':"PV - Pārdotās",
		            '2a':"Radošais direktors - Pārdotās",
		            '3a':"Tekstu autors - Pārdotās",
		            '4a':"Dizaineris - Pārdotās",
		            '5a':"Maketētājs - Pārdotās",
		            '6a':"Programmētājs - Pārdotās",

		            "1b":"PV - Padarītās",
		            "2b":"Radošais direktors - Padarītās",
		            "3b":"Tekstu autors - Padarītās",
		            "4b":"Dizaineris - Padarītās",
		            "5b":"Maketētājs - Padarītās",
		            "6b":"Programmētājs - Padarītās",

		            '9a':"Kopā Pārdotās",
		            '9b':"Kopā Padarītās",
				},
		        colors: {
		            '1a': '#C9674A',
		            '2a': '#4AC9A5',
		            '3a': '#72A73D',
		            '4a': '#C93B75',
		            '5a': '#4D4EC9',
		            '6a': '#FBD25C',

		            '1b': '#bbb',
		            '2b': '#bbb',
		            '3b': '#bbb',
		            '4b': '#bbb',
		            '5b': '#bbb',
		            '6b': '#bbb',

		            '9a': '#444',
		            '9b': '#bbb',
		        },
		        hide: ['1a','1b','1_']
		    },
		    tooltip: {
		    	format: {
		    		value: function(v,ratio,id) {
		    			var f = d3.format("f")
		            	if (id[1]=='b') {
		            		var v = v + json[id[0]+'a']
		            	}
		             	return f(v/60)+'h'
		    		}
		    	}
		    },
		    grid: {
		        y: {
		            show: true,
		        }
		    },
		    axis : {
		    	rotated: true,
			    y : {
			    	min: period, //20*8*12*60,
			    	max: period, //20*8*12*60,
		            tick: { format: function (v) {
		            	var f = d3.format(".2r")
		            	// var f = d3.format("f")
		             	return f(v/60)+'h'
		            }},
		            padding: {top: 0, bottom: 0}
			    },
		        x : {
		            tick: { format: function (x) {return ''} },
		            padding: {top: 0, bottom: 0}
			    },
		    }
		});
	}
	var makeGaugeChart = function() {

	}

	var period = $('.dashboard-bonus .user-picture-row').attr('data-period')
	console.log('__PERIOD',period);

	$('.dashboard-bonus .chart').each(function(idx,el){

		var id = '#'+$(el).attr('id')
		var mins = $(el).data('mins')
		var title = $(el).data('title')
		var json_sold = $(el).data('sold-json')
		var json_done = $(el).data('done-json')
		var json = $(el).data('json')


		
		// var if_size = _.chain(json_sold)
		// 	.reject(function(num,idx){return idx==1})
		// 	.size()
		// 	.value()

		// var json = {}
		// for (key in json_sold) {
		// 	json[key+'a'] = json_sold[key]
		// 	json[key+'b'] = json_done[key] - json_sold[key]
		// }

		// if (if_size>1) {
		// 	json['9a'] = 0
		// 	json['9b'] = 0
		// 	for (key in json_sold) {
		// 		json['9a'] += json_sold[key]
		// 		json['9b'] += json_done[key]
		// 	}
		// 	json['9b'] -= json['9a']
		// } else {}

		makeBarChart(mins,title,json,id,period)		
	})
	
}


function ClientsLoaded() {
}

function e404Loaded() {
	$('.page-404').height( $(window).height()*0.8 );
}

function ManualsLoaded() {	
	var diameter = 960;

	var tree = d3.layout.tree()
	    .size([360, diameter / 2 - 120])
	    .separation(function(a, b) { return (a.parent == b.parent ? 1 : 2) / a.depth; });

	var diagonal = d3.svg.diagonal.radial()
	    .projection(function(d) { return [d.y, d.x / 180 * Math.PI]; });

	var svg = d3.select(".manual-body").append("svg")
	    .attr("width", diameter)
	    .attr("height", diameter + 100)
	  .append("g")
	    .attr("transform", "translate(" + diameter / 2 + "," + diameter / 2 + ")");

	d3.json("flare.json", function(error, root) {
	  var nodes = tree.nodes(root),
	      links = tree.links(nodes);

	  var link = svg.selectAll(".link")
	      .data(links)
	    .enter().append("path")
	      .attr("class", "link")
	      .attr("d", diagonal);

	  var node = svg.selectAll(".node")
	      .data(nodes)
	    .enter().append("g")
	      .attr("class", "node")
	      .attr("transform", function(d) { return "rotate(" + (d.x - 90) + ")translate(" + d.y + ")"; })

	  node.append("circle")
	      .attr("r", 4.5);

	  node.append("text")
	      .attr("dy", ".31em")
	      .attr("text-anchor", function(d) { return d.x < 180 ? "start" : "end"; })
	      .attr("transform", function(d) { return d.x < 180 ? "translate(8)" : "rotate(180)translate(-8)"; })
	      .text(function(d) { return d.name; });
	});

	// d3.select(self.frameElement).style("height", diameter + 100 + "px");
}


function exampleData() {
  return  [
      { 
        "label": "One",
        "value" : 29.765957771107
      } , 
      { 
        "label": "Two",
        "value" : 0
      } , 
      { 
        "label": "Three",
        "value" : 32.807804682612
      } , 
      { 
        "label": "Four",
        "value" : 196.45946739256
      } , 
      { 
        "label": "Five",
        "value" : 0.19434030906893
      } , 
      { 
        "label": "Six",
        "value" : 98.079782601442
      } , 
      { 
        "label": "Seven",
        "value" : 13.925743130903
      } , 
      { 
        "label": "Eight",
        "value" : 5.1387322875705
      }
    ];
}

function exampleData2() {
  return  [
      { 
        "label": "One",
        "value" : 29.765957771107
      } , 
      { 
        "label": "Two",
        "value" : 100
      } , 
      { 
        "label": "Three",
        "value" : 32.807804682612
      } , 
      { 
        "label": "Four",
        "value" : 196.45946739256
      } , 
      { 
        "label": "Five",
        "value" : 0.19434030906893
      } , 
      { 
        "label": "Six",
        "value" : 12.079782601442
      } , 
      { 
        "label": "Seven",
        "value" : 13.925743130903
      } , 
      { 
        "label": "Eight",
        "value" : 4.1387322875705
      }
    ];
}