<?php

class BaseController extends Controller {

	public function generateNgApps() {
		# construct ng app list
			$apps = [];
			$apps[] = $this::constructAppPaths('Timetracker.Layouter.Grid','/coustom-ng-apps/layouter_grid/');
			$apps[] = $this::constructAppPaths('Timetracker.FluidAlerts','/coustom-ng-apps/fluid_alerts/');
		
		## last is the main app
			$apps[] = $this::constructAppPaths('Timetracker','/');

		return $apps;
	}

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	public function sold_done_calculate($in) {

		if (count($in)>1) {
			foreach ($in as $key => $project) {
				$in[$key] = $this::__sold_done_calculate_($project);
			}
			return $in;
		} else {
			return $this::__sold_done_calculate_($in);
		}
	}

	# Lets Calculate Money!
	public function __sold_done_calculate_($in) {
		$in->sold_m = 0;
		$in->done_m = 0;

		if (isSet($in->estimates)) foreach ($in->estimates as $estimate) {
			$estimate->sold_m = 0;

			if (isSet($estimate->entries)) foreach ($estimate->entries as $entry) {
				$roles = [];
				$entry->sold_m = 0;

				if (isSet($entry->hours)) foreach ($entry->hours as $key => $hour) {
					$roles[] = [
						'id' => $estimate->involved_roles->id[$key],
						'hours' => $hour*60,
						'salary' => $estimate->involved_roles->salary[$key]
					];
					$entry->sold_m += $hour*60;
					$entry->cost_hours += $hour*$estimate->involved_roles->salary[$key];
				}
				
				$entry->roles = $roles;
				$estimate->sold_m += $entry->sold_m;
				$estimate->cost_hours += $entry->cost_hours;
			}

			if (isSet($estimate->expences)) foreach ($estimate->expences as $expence) {
					$expence->cost_additional = $expence->qty * $expence->price;

					$estimate->cost_additional += $expence->cost_additional;
				}
			
			$in->sold_m += $estimate->sold_m;
		}

		if (isSet($in->tasks)) foreach ($in->tasks as $task) {
			$task->done_m = 0;

			if (isSet($task->taskrolebinds)) foreach ($task->taskrolebinds as $taskrole) {
				$taskrole->done_m = 0;

				if (isSet($taskrole->users)) foreach ($taskrole->users as $user) {
					$user->done_m = 0;
					
					if (isSet($in->estimates)) foreach ($user->timelogs as $timelog) {
						if ($timelog->minutes && $timelog->task_role_bind_id == $taskrole->id) $user->done_m += $timelog->minutes;
					}

					$taskrole->done_m += $user->done_m;
				}
				$taskrole->users_empty = count($taskrole->users) == 0;
				$task->done_m += $taskrole->done_m;
			}
			$task->taskrolebinds_empty = count($task->taskrolebinds) == 0;
			
			$in->done_m += $task->done_m;
		}

		return $in;
	}
	public function constructAppPaths($name,$path) {
		return [
			'name' => $name,
			'path' => $path
		];
	}

	public function getMonth_vid($date) {
		$firstday = $date->modify('first day of this month')->format('YYYY_MM_DD');
		$response = file_get_contents("http://www.vid.lv/kalendars_viz.php?lng2=LV&Date=2014_11_01");
		// print_r(gettype($response)); exit;
		// print_r($response); exit;

		$raw_items = [];
		# Filter out center calendar table
		preg_match("/<center>.*<\/center>/ms",$response,$raw_items);
		# Get all days
		preg_match_all("/<td.*?<\/td>/ms",implode($raw_items),$raw_items);

		$items = [];
		foreach ($raw_items[0] as $raw_item) {
			preg_match( '/class="(.*?)"/' , $raw_item , $class);
			preg_match( '/<td.*>(.*?)<\/td>/' , $raw_item , $date);
			preg_match( '/alt="(.*?)"/' , $raw_item , $name);
			$items[] = [
				'class' => $class[1]=='kl1' ? 'regular' : $class[1]=='kl2'? 'free' : 'other',
				'date' => $date[1],
				'name' => (isset($name[0]))?$name[1]:'',
			];
		}
		print_r($items);
		echo "\r\n";
		print_r($raw_items); exit;


		return $items;
	}

	public function getYear_vid($date) {

	}
	
	
}
