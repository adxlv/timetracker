<?php
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
* 
*/
class NumberSpellout {
	function __construct() {}
	
	private static $ones     = array("viens","divi","trīs","četri","pieci","seši","septiņi","astoņi", "deviņi");
	private static $teens    = array("vienpadsmit","divpadsmit","trīspadsmit","četrpadsmit","piecpadsmit","sešpadsmit","septiņpadsmit","astoņpadsmit", "deviņpadsmit");
	private static $tens     = array("desmit","divdesmit","trīsdesmit","četrdesmit","piecdesmit","sešdesmit","septiņdesmit","astoņdesmit", "deviņdesmit");
	private static $hunderts = array("simts","simti");
	
	private static $part_plr = array("","tūkstoši","miljoni","miljardi");
	private static $part_sng = array("","tūkstotis","miljons","miljards");

	private static $c_big   = array("eiro","eiro");
	private static $c_small = array("cents","centi");

	public static function toString($num) {
		$num = floatval($num);

		$small = ($num*100)%100;
		$big = $num-($small/100);
		$main_arr = self::splitToThree($big);
		
		foreach ($main_arr as $key => $number) {
			$main_arr[$key] = self::threeToString($number,$key);
		}
		
		$main_arr = array_reverse($main_arr);
		
		$ret = implode($main_arr,' ');

		$ret .= self::curencyString($big,self::$c_big).', '.sprintf("%02d", $small).' '.self::curencyString($small,self::$c_small);
		return $ret;
	}
	private static function splitToThree($num) {
		$times_repeat = (strlen($num)%3==0)?0:3-strlen($num)%3;
		$digit_str = str_repeat(0,$times_repeat).$num;
		
		$digit_arr = str_split($digit_str,3);

		return array_reverse($digit_arr);
	}
	private static function threeToString($three,$key) {
		$ret_str = '';

		$digit_hundert = intval($three/100);
		$digit_tens    = intval($three%100 / 10);
		$digit_ones    = intval($three%10);

		
		
		# SIMTI
		if ($digit_hundert > 0 && $digit_hundert < 2) {
			// $ret_str.= self::$part_sng[0].' ';
			$ret_str.= self::$ones[$digit_hundert - 1].' '.self::$hunderts[0].' ';
		} else if ($digit_hundert > 0) {
			$ret_str.= self::$ones[$digit_hundert - 1].' '.self::$hunderts[1].' ';
		}


		# DESMITI VAI PADSMITI
		if ($digit_tens > 1) {
			$ret_str.= self::$tens[$digit_tens - 1].' ';
		} else if ($digit_tens == 1 && $digit_ones > 0) {
			$ret_str.= self::$teens[$digit_ones - 1].' ';
		} else if ($digit_tens == 1 ) {
			$ret_str.= self::$tens[0].' ';
		}


		# VIENI 
		if ($digit_tens > 1 && $digit_ones > 0) {
			$ret_str.= self::$ones[$digit_ones - 1];
		} else if ($digit_tens == 0 && $digit_ones > 0){
			$ret_str.= self::$ones[$digit_ones - 1];
		}


		# KEY
		if ($digit_ones==1 && $digit_tens*10+$digit_ones!=11) {
			$ret_str .= ' ' . self::$part_sng[$key];
		} else {
			$ret_str .= ' ' . self::$part_plr[$key];
		}

		return $ret_str;
	}
	private static function curencyString($num,$curencyArray) {
		$digit_tens    = intval($num%100);
		$digit_ones    = intval($num%10);

		if ($digit_ones==1 && $digit_tens!=11) {
			$ret_str = $curencyArray[0];
		} else {
			$ret_str = $curencyArray[1];
		}
		return $ret_str;
	}
}

class HomeController extends BaseController {

	public function index() {
		$usr = Auth::user();
		
		# Redirect all non PMs
		if ($usr->group == 0) {
			return Redirect::to('/desktopapp/dash/#/my-tasks/0');
		};


		return View::make('index', array (
			'apps' => $this->generateNgApps(),
			'localEnv'  => (App::environment('local'))?'enviroment-local':'',
		));
	}

	public function showLogin()
	{
		App::error(function(ModelNotFoundException $e)
		{
		    return View::make('admin/login', array(
		    	'error' => true,
		    	'type'  => 'alert',
		    	'msg'   => 'you probably mistyped your username or password. Remember password is case-sensitive.',
		    	'user'  => Input::get('username')
		    ));
		});


		if (Request::isMethod('post')) {
			$user = Input::get('username');
			$pass = Input::get('password');

			$usr = User::whereLogin($user)->firstOrFail();
			// print_r(); exit;

			if ( $usr->id && Hash::check($pass, $usr->pass) && !$usr->disabled ) {
				Auth::login($usr,true);

				if ($usr->group == 0) {
					return Redirect::to('/desktopapp/dash/#/my-tasks/0');
				}

			    return Redirect::to('/');

			} else {
				return View::make('admin/login', array(
			    	'error' => true,
			    	'type'  => 'alert',
			    	'msg'   => 'you probably mistyped your username or password. Remember password is case-sensitive.',
			    	'user'  => Input::get('username')
			    ));
			}	

		} else {
			if (Auth::check()) {
				return Redirect::intended('/');
			}
		}
		
		return View::make('admin/login', array(
	    	'user'  => null,
	    	'error' => false,
	    ));
	}

	public function showApiLogin()
	{
		App::error(function(ModelNotFoundException $e)
		{
		    return View::make('admin/login', array(
		    	'error' => true,
		    	'type'  => 'alert',
		    	'msg'   => 'you probably mistyped your username or password. Remember password is case-sensitive.',
		    	'user'  => Input::get('username')
		    ));
		});


		if (Auth::check()) {
			// return Redirect::intended('/');
		} else {
			return Response::json(array(
				'loggedout' => true,
		    ));
		}

	}

	public function showLogout()
	{
		Auth::logout();
		return Redirect::to('/');
	}

	public function showLogout_fromDesktopApp()
	{
		Auth::logout();
		return Redirect::to('/desktopapp/dash/#/my-tasks');
	}

	public function loggedIn()
	{
		// $usr = User::find(1);
		// Auth::login($usr);

		$user = Auth::user();
		if ($user) {
			$user->loggedin = true;
			return $user;
		} else {
			return Response::json(array(
				"loggedin" => false,
			));
		}
	}
	
	protected function dashboard()
	{
		return View::make('desktopindex', array (
			'apps' => $this->generateNgApps()
		));
	}

	public function test() {
		return $this::getMonth_vid(new DateTime());

	}

	public function pdf_invoice($estimate_id) {		

		$estimate = Estimate::with([
			'project.client'=> function($q) {},
			'bill_entries' => function($q) {},
		])->findOrFail($estimate_id);
		$branch = Branches::findOrFail($estimate->group);

		$totals = array();
		if ($branch->type == 'standart') {
			$totals['summ'] = $estimate->total_summ;
			$totals['summ_add'] = $totals['summ']*0.21;
			$totals['summ_total'] = $totals['summ']+$totals['summ_add'];
			$totals['hidden_perc'] = 0;
		} else {
			// $total = number_format($summ*0.09+$summ,2,',',' ');
			$totals['summ'] = $estimate->total_summ;
			$totals['summ_add'] = $totals['summ']*0.09;
			$totals['summ_total'] = $totals['summ']+$totals['summ_add'];
			$totals['hidden_perc'] = 0.09;
		}

		// foreach ($totals as $key => $value) {
		// 	$totals[$key] = number_format($value,2,',',' ');
		// }


		// dd($total);

		$data = array(
			'estimate'=>$estimate,
			'branch'=>$branch,
			'date' => $estimate->created_at->format('d-m-Y'),
			'totals' => $totals,
			'total_spellout' =>  NumberSpellout::toString($totals['summ_total']),
		);

		

		// return View::make('pdf.invoice', $data);


		$pdf = PDF::loadView('pdf.invoice', $data);
		return $pdf->stream();
	}

	public function pdf_estimate($estimate_id) {
		
		$estimate = Estimate::with([
			'entries' => function($q) {
    			$q->orderBy('sortorder','desc');
    		},
			'project.client'=> function($q) {},
		])->findOrFail($estimate_id);
		$branch = Branches::findOrFail($estimate->group);
		$jobroles = JobRole::get();

		$roleArr = array();
		foreach ($estimate->involved_roles->id as $key => $roleId) {
			$roleArr[$roleId] = $jobroles->find($roleId)->title;
		}
		$estimate->roles = $roleArr;

		foreach ($estimate->entries as $key => $entry) {
			$entrysum = 0;
			foreach ($entry->hours as $key => $value) {
				$entrysum += $value*$estimate->involved_roles->salary[$key];
			}
			$entry->total = $entrysum;
		}

		$totals = array();
		if ($branch->type == 'standart') {
			$totals['summ'] = $estimate->total_summ;
			$totals['summ_add'] = $totals['summ']*0.21;
			$totals['summ_total'] = $totals['summ']+$totals['summ_add'];
			$totals['hidden_perc'] = 0;
		} else {
			// $total = number_format($summ*0.09+$summ,2,',',' ');
			$totals['summ'] = $estimate->total_summ;
			$totals['summ_add'] = $totals['summ']*0.09;
			$totals['summ_total'] = $totals['summ']+$totals['summ_add'];
			$totals['hidden_perc'] = 0.09;
		}

		$data = array(
			'estimate'=>$estimate,
			'date' => $estimate->created_at->format('d.m.Y'),
			'branch'=>$branch,
			'jobroles'=>$jobroles,
			'totals' => $totals,
			'special_classes' => 'estimate',
		);

		// return View::make('pdf.estimate', $data);
		$pdf = PDF::loadView('pdf.estimate', $data);
		// dd($pdf);
		return $pdf->stream();
	}
}
