<?php
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
             		$headers = apache_request_headers();

                        if (!isset($headers['EVE_TRUSTED'])) {
                                $this->used = false;
                                $this->trusted = false;
                        } else if ($headers['EVE_TRUSTED'] == "Yes") {
                                $this->trusted = true;
                                $this->used = true;
                                if (isset($headers['EVE_CHARNAME']))  $this->charName = $headers['EVE_CHARNAME'];
                                if (isset($headers['EVE_CHARID'])) $this->charID = $headers['EVE_CHARID'];
                                if (isset($headers['EVE_CORPNAME'])) $this->corpName = $headers['EVE_CORPNAME'];
                                if (isset($headers['EVE_CORPID'])) $this->corpID = $headers['EVE_CORPID'];
                                if (isset($headers['EVE_ALLIANCENAME'])) $this->allianceName = $headers['EVE_ALLIANCENAME'];
                                if (isset($headers['EVE_ALLIANCEID'])) $this->allianceID = $headers['EVE_ALLIANCEID'];
                                if (isset($headers['EVE_REGIONNAME'])) $this->regionName = $headers['EVE_REGIONNAME'];
                                if (isset($headers['EVE_CONSTELLATIONNAME'])) $this->constellationName = $headers['EVE_CONSTELLATIONNAME'];
                                if (isset($headers['EVE_SOLARSYSTEMNAME'])) $this->solarSystemName = $headers['EVE_SOLARSYSTEMNAME'];
                                if (isset($headers['EVE_STATIONNAME'])) $this->stationName = $headers['EVE_STATIONNAME'];
                                if (isset($headers['EVE_STATIONID'])) $this->stationID = $headers['EVE_STATIONID'];
                                if (isset($headers['EVE_SOLARSYSTEMID'])) $this->solarSystemID = $headers['EVE_SOLARSYSTEMID'];
                                if (isset($headers['EVE_SHIPID'])) $this->shipID = $headers['EVE_SHIPID'];
                                if (isset($headers['EVE_SHIPNAME'])) $this->shipName = $headers['EVE_SHIPNAME'];
                                if (isset($headers['EVE_SHIPTYPEID'])) $this->shipTypeID = $headers['EVE_SHIPTYPEID'];
                                if (isset($headers['EVE_SHIPTYPENAME'])) $this->shipTypeName = $headers['EVE_SHIPTYPENAME'];
                        } else {
                                $this->trusted = false;
                                $this->used = true;
                        }

		}

	}
