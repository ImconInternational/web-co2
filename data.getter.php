<?php
	header("Access-Control-Allow-Origin:*");
	if(!empty($_POST)){
		if(!empty($_POST['ac'])){
			include_once "BD.class.php";
			$db = new BD();
			
			if($_POST['ac'] == "1"){
				// get data
				$lastId = $_POST['lastid'];
				$groupBy = $_POST['groupby'];
				
				$data = $db -> getData($lastId,$groupBy);
				echo "[";
				for($i=0;$i<count($data);$i++){
					if($i > 0) echo ",";
					echo json_encode($data[$i]);
				}
				echo "]";
			}else if($_POST['ac'] == "2"){
				// get the sensors
				$sensors = $db -> getDevices();
				echo "[";
				for($i=0;$i<count($sensors);$i++){
					if($i > 0) echo ",";
					echo json_encode($sensors[$i]);
				}
				echo "]";
			}else if($_POST['ac'] == "3"){
				//get the gateways
				$gateways = $db -> getGateways();
				echo "[";
				for($i=0;$i<count($gateways);$i++){
					if($i > 0) echo ",";
					echo json_encode($gateways[$i]);
				}
				echo "]";
			}
		}
	}