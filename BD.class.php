<?php
	class BD{
		private $connexionBDD = null;
		
		public function __construct(){
			$this -> connectDB();
		}
		
		private function connectDB(){
			try{
				$host = "localhost";$db="elielmathe_iot";$user="elielmathe_iot";$pwd="iot2018";
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$this -> connexionBDD = new PDO("mysql:host=".$host.";dbname=".$db,$user,$pwd,$pdo_options);
			}catch(Exception $err){die("Database access error ".$err->getMessage());}
		}
		
		public function saveDevice($appId,$deviceId,$hardwareSerial,$port,$counter,$payLoadRaw,$downlink){
			if(!empty($appId) && !empty($deviceId) && !empty($hardwareSerial) && !empty($port) && !empty($counter) && !empty($payLoadRaw)&& !empty($downlink)){
				try{
					$req = $this -> connexionBDD -> prepare("
									INSERT INTO Device(
													idDevice,
													app_id,
													dev_id,
													hardware_serial,
													port,counter,
													payload_raw,
													downlink_url
												) VALUES('',?,?,?,?,?,?,?)");
					$i  = $req -> execute(array($appId,$deviceId,$hardwareSerial,$port,$counter,$payLoadRaw,$downlink));
					$req -> closeCursor();
					if($i > 0) return true;
				}catch(Exception $err){$this -> catchException($err);}
			}
		}
		
		public function checkIfDeviceExists($appId,$deviceId){
			if(!empty($appId) && !empty($deviceId)){
				try{
					$req = $this -> connexionBDD -> prepare("
									SELECT idDevice FROM Device WHERE app_id=? AND dev_id=?
							");
					$req -> execute(array($appId,$deviceId));
					if($res = $req -> fetch()){
						$req -> closeCursor();
						return $res['idDevice'];
					}
				}catch(Exception $err){$this -> catchException($err);}
			}
		}
		
		public function saveData($idDevice,$idGateway,$co2,$humidity,$temperature,$timest,$frequency,$modulation,$data_rate,$coding_rate){
			
			if(!empty($idDevice) && !empty($idGateway) && !empty($timest) && !empty($frequency) && !empty($modulation) && !empty($data_rate) && !empty($coding_rate)){
				try{
					$req = $this -> connexionBDD -> prepare("
								INSERT INTO Data(idData,co2,humidity,temperature,timest,frequency,modulation,data_rate,coding_rate,Device_idDevice,Gateway_idGateway) 
								VALUES('',?,?,?,?,?,?,?,?,?,?)
							");
					$i = $req -> execute(array($co2,$humidity,$temperature,$timest,$frequency,$modulation,$data_rate,$coding_rate,$idDevice,$idGateway));
					$req -> closeCursor();
					return $i;
				}catch(Exception $err){$this -> catchException($err);}
			}
		}
		
		public function checkIfGatewayExists($gatewayId){
			if(!empty($gatewayId)){
				try{
					$req = $this -> connexionBDD -> prepare("
								SELECT idGateway FROM Gateway WHERE gtw_id=?
					");
					$req -> execute(array($gatewayId));
					if($res = $req -> fetch()){
						$req -> closeCursor();
						return $res['idGateway'];
					}
				}catch(Exception $err){$this -> catchException($err);}
			}
		}
		
		public function saveGateway($gatewayId,$gatewayTrusted,$timest,$timeDate,$channel,$rrsl,$snr,$rf_chain,$latitude,$longitude,$location_source){
			if(
				!empty($gatewayId) && !empty($gatewayTrusted) && 
				!empty($timest) && !empty($timeDate) && !empty($channel) && 
				!empty($rrsl) && !empty($snr) && !empty($rf_chain) && !empty($latitude) && 
				!empty($longitude) && !empty($location_source)
			){
				try{
					$req = $this -> connexionBDD -> prepare("
								INSERT INTO Gateway(idGateway,gtw_id,gtw_trusted,timest,time,channel,rssi,snr,rf_chain,latitude,longitude,location_source) 
								VALUES('',?,?,?,?,?,?,?,?,?,?,?)
							");
					$req -> execute(array($gatewayId,$gatewayTrusted,$timest,$timeDate,$channel,$rrsl,$snr,$rf_chain,$latitude,$longitude,$location_source));
				}catch(Exception $err){$this -> catchException($err);}
			}
		}
		
		public function getData($lastId,$groupBy){
			try{
				$lastId = $lastId ? $lastId : 1;
				$groupBy;
				//SELECT * FROM `Data` GROUP BY DATE_FORMAT(timest,"%d:%m:%y:%h:%i")
				
				$request = "";
				$dateFormat = "";
				
				if($groupBy == "minute"){
					$dateFormat = "%h:%i";
					$request = ' GROUP BY DATE_FORMAT(timest,"%d:%m:%y:%h:%i")';
				}else if($groupBy == "hour"){
					$dateFormat = "%h:00";
					$request = ' GROUP BY DATE_FORMAT(timest,"%d:%m:%y:%h")';
				}else if($groupBy == "day"){
					$dateFormat = "%d/%m/%y";
					$request = ' GROUP BY DATE_FORMAT(timest,"%d:%m:%y")';
				}else if($groupBy == "month"){
					$dateFormat = "%m/%y";
					$request = ' GROUP BY DATE_FORMAT(timest,"%m:%y")';
				}else if($groupBy == "year"){
					$dateFormat = "%y";
					$request = ' GROUP BY DATE_FORMAT(timest,"%y")';
				}
				
				$request = "
							SELECT 
								Device_idDevice,Gateway_idGateway,AVG(co2) AS co2,
								coding_rate,data_rate,frequency,AVG(humidity) AS humidity,
								idData,latitude,longitude,modulation,AVG(temperature) AS temperature,
								DATE_FORMAT(timest,'".$dateFormat."') AS timest
							FROM `Data` WHERE idData>=".$lastId."
				".$request." ORDER BY idData DESC LIMIT 0,200";
				
				//echo ">>".$request."<<";
				
				$req = $this -> connexionBDD -> prepare($request);
				$req -> execute(array());
				$toReturn = array();
				while($res = $req -> fetch()){
					$toReturn[] = $res;
				}
				return $toReturn;
			}catch(Exception $err){$this -> catchException($err);}
		}
		
		public function getDevices(){
			try{
				$req = $this -> connexionBDD -> prepare("
								SELECT * FROM Device 
				");
				$req -> execute(array());
				$toReturn = array();
				while($res = $req -> fetch()){
					$toReturn[] = $res;
				}
				return $toReturn;
			}catch(Exception $err){$this -> catchException($err);}
		}
		
		public function getGateways(){
			try{
				$req = $this -> connexionBDD -> prepare("
								SELECT * FROM Gateway 
				");
				$req -> execute(array());
				$toReturn = array();
				while($res = $req -> fetch()){
					$toReturn[] = $res;
				}
				return $toReturn;
			}catch(Exception $err){$this -> catchException($err);}
		}
		
		private function catchException($exception){
			echo("Error: ". $exception -> getMessage());
		}
	}