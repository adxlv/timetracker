<div class="container">
	
	<h2 class="">Time Tracker</h2> <!-- <i class="fa fa-angle-double-down"></i> -->

	<ul class='side-nav'>
		<li class="list" ui-sref-active="active">
			<a ui-sref="home.tasks"
			   class="icon icon-some">Home
			   <i class="fa fa-home"></i> 
			</a> 
		</li>
		<li class="list" ng-class="{'active':('project'|includedByState), 'active c':('clients'|includedByState) }">
			<a ui-sref="clients" 
			   class="icon icon-some">All Clients
			   <i class="fa fa-suitcase"></i>
			</a> 
		</li>
		<li class="list" ng-class="{'active':('reports'|includedByState) }">
			<a ui-sref="reports.dashboard" 
			   class="icon icon-some">Reports
			   <i class="fa fa-area-chart"></i>
			</a> 
		</li>
				<!-- <li class="list empty">	<a class="icon icon-some">&nbsp;</a> </li> -->


		<li class="list" ui-sref-active="active">
			<a ui-sref="tasks"
				class="icon icon-some">My Tasks
				<i class="fa fa-tasks "></i>
			</a>
		</li>		
		<!-- <li class="list empty">	<a class="icon icon-some">&nbsp;</a><i class="fa"></i> </li> -->


		<li class="list disabled">
			<a href="#/manuals" 
			   class="icon icon-some">Manuals
			   <i class="fa fa-file-text-o"></i>
			</a> 
		</li>

		<li class="list" ui-sref-active="active">
			<a ui-sref="settings" 
			   class="icon icon-some">Settings
			   <i class="fa fa-cogs"></i>
			</a> 
		</li>

		<li class="list">
			<a href="#/apps" 
			   class="icon icon-some">Apps
			   <i class="fa fa-flask"></i>
			</a> 
		</li>

		<li class="list" ui-sref-active="active">
			<a href="/logout" 
			   class="icon icon-some">Exit
			   <i class="fa fa-power-off"></i> 
			</a> 
		</li>

	</ul>

	<h2 class="menu-icon fa fa-bars"></h2>

</div>
