var app=angular.module("Timetracker.apps.FluidAlerts.controllers",[]).controller("FA_IndexController",function(i,o,n,e,d){function t(o,n){"undefined"!=typeof n&&(angular.equals(o,n)||d.save(i.app))}var l=n.$$absUrl,r=10;i.beep=function(){window.fluid.beep()},i.badge_add=function(){r++,window.fluid.dockBadge=r},i.badge_sub=function(){r--,window.fluid.dockBadge=r},i.notify=function(){window.fluid.showGrowlNotification({title:"title",description:"description",priority:1,sticky:!1,identifier:"foo"})}});
//# sourceMappingURL=./controllers-min.js.map