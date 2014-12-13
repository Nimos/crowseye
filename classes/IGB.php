<?php
	# stores the information provided by EVE SSO and the ingame browser headers
	class CharacterInformation {
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
            if (!function_exists('getallheaders')) 
            { 
                function getallheaders() 
                { 
                    $headers = ''; 
                    foreach ($_SERVER as $name => $value) 
                    { 
                        if (substr($name, 0, 5) == 'HTTP_') 
                        { 
                            $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value; 
                        } 
                    } 
                    return $headers; 
                } 
            } 
     		$headers = getallheaders();

            $this->authed = false;
            $this->officer = false;
            $this->director = false;
            $this->dbID = -1;

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

            if (isset($_SESSION['loggedIn'])) {
                # SSO information overrides IGB information
                $this->charID = $_SESSION['charID'];
                $this->charName = $_SESSION['charName'];
                $this->authed = $_SESSION['loggedIn'];

                # Try to fill unknown values from database
                $charInfo = Database::filterBy("players", ' charID="'.SQLite3::escapeString($this->charID).'"');

                if ( count($charInfo) != 0 ) {
                    $charInfo = $charInfo[0];

                    if (!isset($this->corpID)) $this->corpID = $charInfo['corpID'];
                    $this->dbID = $charInfo['rowid'];
                    $this->officer = $charInfo['fc'];
                    $this->director = $charInfo['director'];
                    if ($this->director) $this->officer = 1;

                } else {
                    $api_info = file_get_contents("https://api.eveonline.com/eve/CharacterInfo.xml.aspx?characterID=".$this->charID);
                    if (preg_match("@<corporationID>([0-9]*)</corporationID>@", $api_info, $matches)) {
                        $this->corpID = $matches[1];
                    } else {
                        $this->corpID = 0;
                    }

                    Database::putObject("players", "(charName, charID, corpID, fc, director) VALUES ('".SQLite3::escapeString($this->charName)."','".SQLite3::escapeString($this->charID)."','".SQLite3::escapeString($this->corpID)."',0,0);");
                }
            }

		}

	}
