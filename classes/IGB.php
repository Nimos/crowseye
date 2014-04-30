<?
	# all the information the EVE ingame browser provides in one nicely packed object!
	class IGB {
		private static $instance;

		public $used;
		public $trusted;

		public $charName;
		public $charID;
		public $corpName;
		public $corpID;
		public $allianceName;
		public $allianceID;
		public $regionName;
		public $constellationName;
		public $solarSystemName;
		public $stationName;
		public $stationID;
		public $solarSystemID;
		public $warFactionID;
		public $shipID;
		public $shipName;
		public $shipTypeID;
		public $shipTypeName;

		public static function getInstance() {
  		  	if(!self::$instance) { 
 		    	self::$instance = new self(); 
  		  	} 
			return self::$instance; 
		}

		private function __construct() {
			if (!isset($_SERVER['HTTP_EVE_TRUSTED'])) {
				$this->used = false;
				$this->trusted = false;				
			} else if ($_SERVER['HTTP_EVE_TRUSTED'] == "Yes") {
				$this->trusted = true;
				$this->used = true;
				if (isset($_SERVER['HTTP_EVE_CHARNAME']))  $this->charName = $_SERVER['HTTP_EVE_CHARNAME'];
				if (isset($_SERVER['HTTP_EVE_CHARID'])) $this->charID = $_SERVER['HTTP_EVE_CHARID'];
				if (isset($_SERVER['HTTP_EVE_CORPNAME'])) $this->corpName = $_SERVER['HTTP_EVE_CORPNAME'];
				if (isset($_SERVER['HTTP_EVE_CORPID'])) $this->corpID = $_SERVER['HTTP_EVE_CORPID'];
				if (isset($_SERVER['HTTP_EVE_ALLIANCENAME'])) $this->allianceName = $_SERVER['HTTP_EVE_ALLIANCENAME'];
				if (isset($_SERVER['HTTP_EVE_ALLIANCEID'])) $this->allianceID = $_SERVER['HTTP_EVE_ALLIANCEID'];
				if (isset($_SERVER['HTTP_EVE_REGIONNAME'])) $this->regionName = $_SERVER['HTTP_EVE_REGIONNAME'];
				if (isset($_SERVER['HTTP_EVE_CONSTELLATIONNAME'])) $this->constellationName = $_SERVER['HTTP_EVE_CONSTELLATIONNAME'];
				if (isset($_SERVER['HTTP_EVE_SOLARSYSTEMNAME'])) $this->solarSystemName = $_SERVER['HTTP_EVE_SOLARSYSTEMNAME'];
				if (isset($_SERVER['HTTP_EVE_STATIONNAME'])) $this->stationName = $_SERVER['HTTP_EVE_STATIONNAME'];
				if (isset($_SERVER['HTTP_EVE_STATIONID'])) $this->stationID = $_SERVER['HTTP_EVE_STATIONID'];
				if (isset($_SERVER['HTTP_EVE_SOLARSYSTEMID'])) $this->solarSystemID = $_SERVER['HTTP_EVE_SOLARSYSTEMID'];
				if (isset($_SERVER['HTTP_EVE_SHIPID'])) $this->shipID = $_SERVER['HTTP_EVE_SHIPID'];
				if (isset($_SERVER['HTTP_EVE_SHIPNAME'])) $this->shipName = $_SERVER['HTTP_EVE_SHIPNAME'];
				if (isset($_SERVER['HTTP_EVE_SHIPTYPEID'])) $this->shipTypeID = $_SERVER['HTTP_EVE_SHIPTYPEID'];
				if (isset($_SERVER['HTTP_EVE_SHIPTYPENAME'])) $this->shipTypeName = $_SERVER['HTTP_EVE_SHIPTYPENAME'];
			} else {
				$this->trusted = false;
				$this->used = true;
			}


		}

	}