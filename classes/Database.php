<?php  
    #sqlite3 database wrapper
    #
    # this was supposed to handle all the database access, but I scrapped that concept out of lazyness halfway through
    class Database {
        static $instance;
        private $sqlite;
        
        private function __construct () {

            $newDB = !file_exists('db/wh.db');

	       if ($this->db = new SQLite3('db/wh.db')) {
            	if ($newDB) {
                    #holes
            	    $this->db->exec('CREATE TABLE holes (system TEXT, name TEXT, reporter INT, reported INT, signature TEXT);');
                    
                    #players
                    $this->db->exec('CREATE TABLE players (charName TEXT, charID TEXT, corpID INT, fc INT, director INT);');
                    
                    #comments
                    $this->db->exec('CREATE TABLE comments (hole INT, author INT, time INT, text TEXT);');
                    
                    #sites
                    $this->db->exec('CREATE TABLE sites (hole INT, type TEXT, name TEXT, sig TEXT, time INT);');
                }   
            } else {
                die($err);
            }
        }

        public static function getInstance() {
            $this->db = new SQLite3('db/wh.db');
            if(!self::$instance) { 
                self::$instance = new self(); 
            } 
            return self::$instance; 
        }

        public static function getObjects($type) {
             #self::getInstance()->db->exec('INSERT INTO holes (system,class,name,siteNumber,activity,reporter,reported) VALUES ("Amarr","C3","J123456",3,0,0,0);');
             #self::getInstance()->db->exec('DELETE FROM holes WHERE rowid=9;');
            $db = new SQLite3('db/wh.db');
            $db->busyTimeout(10000);
            $query = $db->query($type);
            if (!$query) file_put_contents("log.error", "Type=".$type."\n", FILE_APPEND);
            
            $result = array();
            while ($row = $query->fetchArray()) {
                array_push($result, $row);
            }
            $query->finalize();
            $db->close();
            return $result;
        }

        public static function filterBy($type, $filter) {
            $db = new SQLite3('db/wh.db');
            $db->busyTimeout(3000);
            $query = $db->query('SELECT rowid,* FROM '.$type.' WHERE '.$filter.';');
            $result = array();
            while ($row = $query->fetchArray(SQLITE3_ASSOC)) {
                array_push($result, $row);
            }
            $query->finalize();
            $db->close();
            return $result;
        }

        public static function putObject($type, $object) {
            $db = new SQLite3('db/wh.db');
            $db->busyTimeout(1000);
            $query = $db->exec('INSERT INTO '.SQLite3::escapeString($type).' '.$object.';');
            return $db->lastInsertRowID();
            $db->close();
        }

        public static function removeObjects($type, $where) {
            $db = new SQLite3('db/wh.db');
            $db->busyTimeout(1000);
            $query = $db->exec('DELETE FROM '.SQLite3::escapeString($type).' WHERE '.$where.';');
            return $db->getInstance()->lastInsertRowID();
            $db->close();
        }

        public static function exec($string) {
            $db = new SQLite3('db/wh.db');
            $db->busyTimeout(1000);
            $db->exec($string);
            $db->close();
        }
    }
?>
