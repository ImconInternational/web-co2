<?php
	header("Access-Control-Allow-Origin:*");
	$data = file_get_contents('php://input');
	$data = json_decode($data,true);
	ob_start();
	//print_r($data);
	//echo "<br/><br/><br/>";
	
	if(!empty($data)){
		if(!empty($data['app_id']) && !empty($data['hardware_serial']) && !empty($data['metadata']) && !empty($data['downlink_url'])){
			include_once "BD.class.php";
			$db = new BD();
			
			
			$idGateway = $db -> checkIfGatewayExists($data['metadata']['gateways'][0]['gtw_id']);
			
			if(!$idGateway)
				$db -> saveGateway(
									$data['metadata']['gateways'][0]['gtw_id'], $data['metadata']['gateways'][0]['gtw_trusted'], 
									$data['metadata']['gateways'][0]['timestamp'], $data['metadata']['gateways'][0]['time'], 
									$data['metadata']['gateways'][0]['channel'], $data['metadata']['gateways'][0]['rssi'], 
									$data['metadata']['gateways'][0]['snr'], $data['metadata']['gateways'][0]['rf_chain'], 
									$data['metadata']['gateways'][0]['latitude'], $data['metadata']['gateways'][0]['longitude'], 
									$data['metadata']['gateways'][0]['location_source']);
			
			$idGateway = $db -> checkIfGatewayExists($data['metadata']['gateways'][0]['gtw_id']);
			
			unset($data['metadata']['gateways']);
			
			$idDevice = $db -> checkIfDeviceExists($data['app_id'], $data['dev_id']);	
			if(!$idDevice)
				$db -> saveDevice(
						$data['app_id'], $data['dev_id'], $data['hardware_serial'], 
						$data['port'], $data['counter'], $data['payload_raw'], $data['downlink_url']
				);
			
			
			$idDevice = $db -> checkIfDeviceExists($data['app_id'], $data['dev_id']);	
			
				
			$db -> saveData(
					$idDevice, $idGateway, 
					$data['payload_fields']['co2'], $data['payload_fields']['humidity'], 
					$data['payload_fields']['temperature'], $data['metadata']['time'], 
					$data['metadata']['frequency'], $data['metadata']['modulation'], 
					$data['metadata']['data_rate'], $data['metadata']['coding_rate']
			);
			
		}
	}
	
	$txt = ob_get_clean();
	$file = fopen("myfile.txt","a+");
	
	fwrite($file,$txt,strlen($txt));
	fclose($file);
	die();