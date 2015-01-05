<?php


	require_once('classes/Database.php');


	class Wormhole {
		public $jumps;
		public $system;
		public $class;
		public $name;
		public $siteNumber;
		public $activity;
		public $reporter;
		public $reported;
		public $sites;
		public $comments;
		public $age;
		public $id;


		public function __construct ($id, $system, $name, $reporter, $reported, $sites, $comments, $sig = "", $status = 0) {
			$this->id = $id;
			$this->system = $system;
			$this->name = $name;
			$this->reporter = $reporter;
			$this->reported = $reported;
			$this->sites = $sites;
			$this->comments = $comments;
			$this->sig = "not specified";
			if ($sig)	$this->sig = $sig;
			$this->status = $status;
			$this->siteNumber = count($sites['Combat Site']);
			$this->wspace = preg_match ('/J[0-9][0-9][0-9][0-9][0-9][0-9]/i', $name);

			if ($this->wspace) {
				$this->class = self::getClassByName($name);
				$this->effect = self::getEffectByName($name);

			} else {
				$this->class = self::getSecurityByName($name);
				$this->effect = array(0 => "", "effect" => "");
				$this->region = self::getRegionByName($name);
			}
			$this->jumps = self::getJumps($system);
			$this->age = time() - $reported;
		}

		public static function parseScan($scan) {
			$result = array(
				'Combat Site' => array(),
				'Relic Site' => array(),
				'Data Site' => array(),
				'Wormhole' => array(),
				'unscanned' => array(),
				'all' => array()

			);
			$data = explode("\n", $scan);

			foreach ($data as $line) {
				$arr = explode("\t", $line);
				if (sizeof($arr) < 3) continue;
				$site = array(
					"sig" => $arr[0],
					"type" => $arr[2],
					"name" => $arr[3]
				);

				if ($site['type'] != "") {
					if (isset($result[$site['type']])) {
						array_push($result[$site['type']], $site);
					}
				} else {
					array_push($result['unscanned'], $site);
				}
				array_push($result['all'], $site);	
			}
			return $result;
		}



		public function save() {
			$holeinfo = $charInfo = Database::filterBy("holes", ' name="'.SQLite3::escapeString($this->name).'"');

			if ($this->class == null) return -1;

			if (count($holeinfo) != 0) {
				foreach ($holeinfo as $hole) {
					if ((time() - $hole['reported']) < 86400) return -1;	
				}
			} 



			$charInfo = Database::filterBy("players", ' charID="'.SQLite3::escapeString($this->reporter[0]).'"');

			if (count($charInfo) == 0) {
				$id = Database::putObject("players", "(charName, charID, corpID, fc, director) VALUES ('".SQLite3::escapeString($this->reporter[1])."','".SQLite3::escapeString($this->reporter[0])."','".SQLite3::escapeString($this->reporter[2])."',0,0);");	
			} else if ($charInfo[0]['corpID'] != $this->reporter[2]) {
				$id = $charInfo[0]['rowid'];
				Database::exec('UPDATE players SET corpID="'.SQLite3::escapeString($this->reporter[2]).'" WHERE charID='.SQLite3::escapeString($this->reporter[0]).';');
			} else {
				$id = $charInfo[0]['rowid'];
			}
			$holeID = Database::putObject("holes", "(system, name, reporter, reported, sig) VALUES ('".SQLite3::escapeString($this->system)."','".SQLite3::escapeString($this->name)."','".SQLite3::escapeString($id)."','".SQLite3::escapeString($this->reported)."','".SQLite3::escapeString($this->sig)."')");

			self::saveSites($holeID, $this->sites);

			return $holeID;
		}

		public static function saveSites($holeID, $sites) {
			
			foreach ($sites['all'] as $site) {
				$siteinfo = Database::filterBy("sites", ' sig="'.SQLite3::escapeString($site['sig']).'" AND hole='.$holeID);
				if (count($siteinfo) == 0) { 
					$id = Database::putObject("sites", "(sig, type, name, hole, time) VALUES ('".SQLite3::escapeString($site['sig'])."','".SQLite3::escapeString($site['type'])."','".SQLite3::escapeString($site['name'])."',".SQLite3::escapeString($holeID).",".time().");");	
				} else {
					$db = new SQLite3('db/wh.db');
            		$db->busyTimeout(1000);
            		$query = $db->exec('UPDATE sites SET sig="'.SQLite3::escapeString($site['sig']).'", type="'.SQLite3::escapeString($site['type']).'", name="'.SQLite3::escapeString($site['name']).'", hole="'.SQLite3::escapeString($holeID).', time="'.time().'" WHERE rowid='.$siteinfo[0]['rowid'].';');
				}
			}
		}

		public static function getJumps($system, $first=0) {
			if (preg_match ('/J[0-9][0-9][0-9][0-9][0-9][0-9]/i', $system)) {
				if ($first === $system) return ">5";
				$db = new SQLite3("db/wh.db");
				$res = $db->query('SELECT system FROM holes WHERE name="'.SQLite3::escapeString($system).'" AND reported>'. (time()-86400) .';')->fetchArray();
				if ($res) {
					if ($first === 0) {
						$ju = self::getJumps($res['system'], $system);
					} else {
						$ju = self::getJumps($res['system'], $first);
					}
					if ($ju == ">5") return $ju;
					return 1+$ju;
				}
				return ">5";

			}

			$jumpArray = array(
				strtolower("GE-8JV")	=> "0",
				strtolower("AX-DOT")	=> "1",
				strtolower("MUXX-4")	=> "2",
				strtolower("E1-4YH")	=> "3",
				strtolower("B-XJX4")	=> "4",
				strtolower("TLHB-Z")	=> "4",
				strtolower("AOK-WQ")	=> "4",
				strtolower("E3-SDZ")	=> "5",
				strtolower("3-OKDA")	=> "1",
				strtolower("MY-W1V")	=> "2",
				strtolower("8B-2YA")	=> "3",
				strtolower("SNFV-I")	=> "4",
				strtolower("HP-64T")	=> "5",
				strtolower("V2-VC2")	=> "5",
				strtolower("CX65-5")	=> "5",
				strtolower("YHN-3K")	=> "1",
				strtolower("3GD6-8")	=> "2",
				strtolower("4M-HGL")	=> "2",
				strtolower("DSS-EZ")	=> "3",
				strtolower("MB4D-4")	=> "4",
				strtolower("LGK-VP")	=> "5",
				strtolower("V-3YG7")	=> "1",
				strtolower("HY-RWO")	=> "2",
				strtolower("6X7-JO")	=> "3",
				strtolower("A-803L")	=> "4",
				strtolower("GJ0-OJ")	=> "5",
				strtolower("I-8D0G")	=> "5",
				strtolower("EX6-AO")	=> "3",
				strtolower("Q-U96U")	=> "4",
				strtolower("QETZ-W")	=> "5",
				strtolower("WFC-MY")	=> "5",
				strtolower("X4-WL0")	=> "5",
				strtolower("B-3QPD")	=> "2",
				strtolower("U-QVWD")	=> "3",
				strtolower("36N-HZ")	=> "4",
				strtolower("QSM-LM")	=> "5",
				strtolower("9KOE-A")	=> "5",
				strtolower("0SHT-A")	=> "4",
				strtolower("V-IUEL")	=> "5",
				strtolower("D87E-A")	=> "5",
				strtolower("JWJ-P1")	=> "5"

			);

			/*if (!isset($_COOKIE['home'])) $_COOKIE['home'] = "GJ0-OJ";


			if ($_COOKIE['home'] == "Ratillose") {
				$jumpArray = array(
					strtolower("Ratillose")  => "0",
					strtolower("Ondree") 	 => "1",
					strtolower("Vevelonel")  => "1",
					strtolower("Pochelymp")  => "2",
					strtolower("Anckee") 	 => "2",
					strtolower("Straloin") 	 => "2",
					strtolower("Eggheron") 	 => "3",
					strtolower("Heluene") 	 => "3",
					strtolower("Elore") 	 => "3",
					strtolower("Toustain") 	 => "3",
					strtolower("Arittant") 	 => "4",
					strtolower("Oruse") 	 => "4",
					strtolower("Hare") 		 => "4",
					strtolower("Meunvon") 	 => "4",
					strtolower("Yveve") 	 => "4",
					strtolower("Babirmouli") => "5",
					strtolower("Ogaria") 	 => "5",
					strtolower("Conomette")  => "5",
					strtolower("Yvelet") 	 => "5"
				);
			} else if ($_COOKIE['home'] == "Chidah") {
				$jumpArray = array(
					strtolower("Sooma") => "1",
					strtolower("Chidah") => "0",
					strtolower("Shenela") => "1",
					strtolower("Dooz") => "2",
					strtolower("Sari") => "3",
					strtolower("Timeor") => "4",
					strtolower("Shach") => "5",
					strtolower("Uzistoon") => "3",
					strtolower("Bayuka") => "3",
					strtolower("Bairshir") => "4",
					strtolower("Moh") => "5",
					strtolower("Onsooh") => "1",
					strtolower("Assabona") => "2",
					strtolower("Nazhgete") => "2",
					strtolower("Irshah") => "3",
					strtolower("Bar") => "4",
					strtolower("Gamis") => "5",
					strtolower("Sucha") => "5",
					strtolower("Jarizza") => "5",
					strtolower("Gomati") => "4",
					strtolower("Assah") => "5",
					strtolower("Hasateem") => "5",
					strtolower("Sendaya") => "2",
					strtolower("Kazna") => "3",
					strtolower("Kuharah") => "4",
					strtolower("Futzchag") => "5",
					strtolower("Lilmad") => "6",
					strtolower("Jayneleb") => "6",
					strtolower("Podion") => "7",
					strtolower("Mifrata") => "3",
					strtolower("Majamar") => "4",
					strtolower("Fera") => "5",
					strtolower("Faspera") => "4",
					strtolower("Jaymass") => "5",
					strtolower("Camal") => "6",
					strtolower("Ihal") => "4",
					strtolower("Shedoo") => "5",
					strtolower("Doril") => "3",
					strtolower("Jorund") => "4",
					strtolower("Farit") => "5",
					strtolower("Utopia") => "5"
				);
			} else if ($_COOKIE['home'] == "Sendaya") {
				$jumpArray = array(
					strtolower("Sendaya") => "0",
					strtolower("Mifrata") => "1",
					strtolower("Faspera") => "2",
					strtolower("Jaymass") => "2",
					strtolower("Majamar") => "2",
					strtolower("Ihal") => "2",
					strtolower("Camal") => "3",
					strtolower("Fera") => "3",
					strtolower("Shedoo") => "3",
					strtolower("Gamis") => "4",
					strtolower("Bekirdod") => "5",
					strtolower("Nieril") => "5",
					strtolower("Hothoumou") => "5",
					strtolower("Berta") => "4",
					strtolower("Juddi") => "5",
					strtolower("Kazna") => "1",
					strtolower("Kuharah") => "2",
					strtolower("Futzchag") => "2",
					strtolower("Lilmad") => "3",
					strtolower("Jayneleb") => "3",
					strtolower("Podion") => "4",
					strtolower("Onsooh") => "1",
					strtolower("Nazhgete") => "2",
					strtolower("Assabona") => "2",
					strtolower("Irshah") => "3",
					strtolower("Gomati") => "4",
					strtolower("Assah") => "5",
					strtolower("Hasateem") => "5",
					strtolower("Bar") => "4",
					strtolower("Sucha") => "5",
					strtolower("Jarizza") => "5",
					strtolower("Chidah") => "2",
					strtolower("Sooma") => "3",
					strtolower("Shenela") => "3",
					strtolower("Dooz") => "4",
					strtolower("Sari") => "5",
					strtolower("Uzistoon") => "5",
					strtolower("Bayuka") => "5",
					strtolower("Shamahi") => "2",
					strtolower("Ilahed") => "3",
					strtolower("Sosa") => "4",
					strtolower("Ishkad") => "4",
					strtolower("Eshiwil") => "5",
					strtolower("Aranir") => "5",
					strtolower("Kehara") => "3",
					strtolower("Arena") => "4",
					strtolower("Serad") => "5",
					strtolower("Uhtafal") => "5",
					strtolower("Dysa") => "4",
					strtolower("Mahti") => "5",
					strtolower("Asilem") => "5",
					strtolower("Shach") => "4",
					strtolower("Mahnagh") => "5",
					strtolower("Timeor") => "5",
					strtolower("Doril") => "1",		
					strtolower("Jorund") => "2",		
					strtolower("Farit") => "3",		
					strtolower("Hemin") => "3",		
					strtolower("Utopia") => "2",		
					strtolower("Litom") => "3",		
					strtolower("Jamunda") => "4",		
					strtolower("Hemin") => "3",		
					strtolower("RMOC-W") => "4",		
					strtolower("YKE4-3") => "5",
					strtolower("K-QWHE") => "5",
					strtolower("1-PWGB") => "5"

				);
			} else  {
				$jumpArray = array(
					strtolower("GJ0-OJ") => "0",
					strtolower("JWZ2-V") => "1",
					strtolower("J-ODE7") => "2",
					strtolower("OGL8-Q") => "3",
					strtolower("R-K4Qy") => "4",
					strtolower("A-803L") => "1",
					strtolower("I-8D0G") => "2",
					strtolower("WQG-4K") => "3",
					strtolower("ZXIC-7") => "4",
					strtolower("KDF-GY") => "5",
					strtolower("F4R2-Q") => "5",
					strtolower("6X7-JO") => "2",
					strtolower("HY-RWO") => "3",
					strtolower("EX6-AO") => "4",
					strtolower("Q-U96U") => "5",
					strtolower("V-3Yg7") => "4",
					strtolower("B-3QPD") => "5",
					strtolower("GE-8JV") => "5",
					strtolower("Q-S7ZD") => "1",
					strtolower("3L3N-X") => "2",
					strtolower("4-P4FE") => "3",
					strtolower("RH0-EG") => "4",
					strtolower("D-9UEV") => "5",
					strtolower("89R-PI") => "5",
					strtolower("UALX-3") => "3",
					strtolower("Y-ORBJ") => "4",
					strtolower("6-IAFR") => "5",
					strtolower("DT-PXH") => "4",
					strtolower("S4-9DN") => "5"
				);

				/*$jumpArray = array(
					strtolower("Barleguet") => "0",
					strtolower('Ausmaert') => "1",
					strtolower("Vestouve") => "1",
					strtolower("Aulbres") => "1",
					strtolower("Kenninck") => "2",
					strtolower("Kennick") => "2",
					strtolower("Espigoure") => "2",
					strtolower("TXW-EI") => "2",
					strtolower("Gare") => "2",
					strtolower("3MOG-V") => "3",
					strtolower("NG-C6Y") => "3",
					strtolower("5-FGQI") => "3",
					strtolower("Aunsou") => "3",
					strtolower("Y-W6GF") => "4",
					strtolower("8V-SJJ") => "4",
					strtolower("XYY-IA") => "4",
					strtolower("Reynire") => "4",
					strtolower("Cumemare") => "4",
					strtolower("Alperaute") => "4",
					strtolower("KLYN-8") => "5",
					strtolower("5KS-AB") => "5",
					strtolower("3KNK-A") => "5",
					strtolower("KF-JRD") => "5",
					strtolower("Pain") => "5",
					strtolower("Covryn") => "5",
					strtolower("Ostingele") => "5"
				);///dont need this anymore
			} */

			if (isset($jumpArray[strtolower($system)])) {
				return $jumpArray[strtolower($system)];

			}
			return ">5";
		}

		public static function getEffectByName($wh) {
			$db = new SQLite3("db/staticdata.db");
			$res = $db->query('SELECT effect FROM wh WHERE name="'.SQLite3::escapeString($wh).'";');
			return $res->fetchArray();
		}

		public static function getClassByName($wh) {
			$db = new SQLite3("db/staticdata.db");
			$res = $db->query('SELECT class FROM wh WHERE name="'.SQLite3::escapeString($wh).'";');
			$result = $res->fetchArray();
			return $result[0];			
		}

		public static function getRegionByName($wh) {
			$db = new SQLite3("db/staticdata.db");
			$res = $db->query('SELECT region FROM kspace WHERE name="'.SQLite3::escapeString($wh).'";');
			$result = $res->fetchArray();
			return $result[0];					
		}
		public static function getSecurityByName($wh) {
			$db = new SQLite3("db/staticdata.db");
			$res = $db->query('SELECT security FROM kspace WHERE name="'.SQLite3::escapeString($wh).'";');
			$result = $res->fetchArray();
			return $result[0];					
		}

		public static function getSites($wh, $after=0) {
			$result = array(
				'Combat Site' => array(),
				'Relic Site' => array(),
				'Data Site' => array(),
				'Wormhole' => array(),
				'unscanned' => array(),
				'all' => array()

			);

			foreach (Database::getObjects('SELECT rowid,* FROM sites WHERE hole='.SQLite3::escapeString($wh).';') as $site) {
				if ($site['type'] != "") {
					if (isset($result[$site['type']])) array_push($result[$site['type']], $site);
				} else {
					array_push($result['unscanned'], $site);
				}
				array_push($result['all'], array($site['sig'], $site['type'], $site['name']));	
			}

			return $result;
		}
		
		public static function getComments($wh, $after=0) {
			$result = array();
			foreach (Database::getObjects('SELECT rowid,text,* FROM comments WHERE hole='.SQLite3::escapeString($wh).' AND time>'.SQLite3::escapeString($after).';') as $site) {
				array_push($result, array($site['time'], self::getPlayerByID($site['author']), $site['text'], $site['hole'], $site['rowid']));	
			}
			return $result;
		}

		public static function getPlayerByID($id) {
			$db = new SQLite3("db/wh.db");
			$db->busyTimeout(1000);
			$res = $db->query('SELECT rowid,* FROM players WHERE rowid="'.SQLite3::escapeString($id).'";');
			if ($res) return $res->fetchArray(SQLITE3_ASSOC);
			return array();
		}

		public static function addComment($playerID, $wh, $comment) {
			$charInfo = Database::filterBy("players", ' charID="'.SQLite3::escapeString($playerID).'"');
			if (count($charInfo) == 0) {
				$id = Database::putObject("players", "(charName, charID, corpID, fc, director) VALUES ('".SQLite3::escapeString($this->reporter[1])."','".SQLite3::escapeString($this->reporter[0])."','".SQLite3::escapeString($this->reporter[2])."',0,0);");	
			} else {
				$id = $charInfo[0]['rowid'];
			}

			$rid = Database::putObject("comments", "(hole, author, time, text) VALUES ('".SQLite3::escapeString($wh)."','".SQLite3::escapeString($id)."','".SQLite3::escapeString(time())."','".SQLite3::escapeString($comment)."');");	
			
			return $rid;


		}

		public static function removeHoleById($id) {
			$db = new SQLite3("db/wh.db");
			$db->busyTimeout(1000);
			$res = $db->exec('UPDATE holes SET reported=-1 WHERE rowid='.SQLite3::escapeString($id).';');
		}

		public static function getObjects ($filter = "1=1") {
			$result = array();
			$oldest = time()-86400;
			foreach (Database::getObjects('SELECT holes.rowid,*,players._ROWID_ FROM holes JOIN players ON holes.reporter=players._ROWID_ WHERE reported > '.$oldest.' AND '.$filter.';') as $hole) {
				array_push($result, new Wormhole(
					$hole[0],
					$hole['system'],
					$hole['name'],
					array($hole['charName'], $hole['charID'], $hole['corpID']),
					$hole['reported'],
					self::getSites($hole[0]),
					self::getComments($hole[0]),
					$hole['sig'],
					$hole['status']
				));
			}

			return $result;
		}
	}
