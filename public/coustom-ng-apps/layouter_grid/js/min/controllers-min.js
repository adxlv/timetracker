var app=angular.module("Timetracker.apps.LayouterGrid.controllers",[]).controller("LG_IndexController",function(a,e,o,l,n){function r(e,o){"undefined"!=typeof o&&(angular.equals(e,o)||n.save(a.app))}var t=o.$$absUrl;a.app=n.get(function(e){a.$watch("app",r,!0)}),a.add_new=function(){var e={active:!1,lang:"",texts:["","","","","","","",""]};a.app.data.languages.push(e)},a.sortableOptions={handle:".handle",delay:150,forceHelperSize:!0,forcePlaceholderSize:!1,stop:function(a,e){$(".sortable").toggleClass("fix-display"),$(".button-group").removeClass("show")}}});
//# sourceMappingURL=./controllers-min.js.map