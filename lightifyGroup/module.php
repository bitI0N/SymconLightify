<?

require_once(__DIR__.DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."lightifyDevice.php"); 


class lightifyGroup extends lightifyDevice {

  public function Create() {
    parent::Create();

		$this->RegisterPropertyString("UniqueID", "");
		$this->RegisterPropertyString("Devices", "");
		$this->RegisterPropertyString("Instances", "");
	}


	public function GetConfigurationForm() {
		$data = json_decode(file_get_contents(__DIR__.DIRECTORY_SEPARATOR."form.json"));
		$Instances = json_decode($this->ReadPropertyString("Instances"), true);
		
		foreach ($Instances as $key => $value) {
			if (IPS_InstanceExists($value['DeviceID'])) {
				$ModuleID = IPS_GetInstance($value['DeviceID'])['ModuleInfo']['ModuleID'];
				
				$HueID = @IPS_GetObjectIDByIdent('HUE', $value['DeviceID']);
				$ColorID = @IPS_GetObjectIDByIdent('COLOR', $value['DeviceID']);
				$ColorTempID = @IPS_GetObjectIDByIdent('COLOR_TEMPERATURE', $value['DeviceID']);
				$BrightID = @IPS_GetObjectIDByIdent('BRIGHTNESS', $value['DeviceID']);
				$SaturationID = @IPS_GetObjectIDByIdent('SATURATION', $value['DeviceID']);

				$Hue = ($HueID) ? GetValueInteger($HueID)."°" : "";
				$Color = ($ColorID) ? strtoupper(str_pad(dechex(GetValueInteger($ColorID)), 6, 0, STR_PAD_LEFT)) : "";			
				$ColorTemp = ($ColorTempID) ? GetValueInteger($ColorTempID) : "";
				$Bright = ($BrightID) ? GetValueInteger($BrightID)."%" : "";
				$Saturation = ($SaturationID) ? GetValueInteger($SaturationID)."%" : "";
				
				$Online = GetValueBoolean(@IPS_GetObjectIDByIdent('ONLINE', $value['DeviceID']));
				$State = GetValueBoolean(@IPS_GetObjectIDByIdent('STATE', $value['DeviceID']));

				if ($State) {
					if ($ModuleID == osrIPSModule::omLight)
						//$rowColor = "#".$Color; //State on
						$rowColor = ($ColorTemp) ? "#FFDA48" : "#FFFCE0"; //State on
					if ($ModuleID == osrIPSModule::omPlug)
						$rowColor = "#98FF72"; //State on
				} else {
					if ($ModuleID == osrIPSModule::omLight)
						//$rowColor = "#D6D6D6"; //State off
						$rowColor = ($Online) ? "#FFFFFF" : "#D6D6D6"; //State off
					if ($ModuleID == osrIPSModule::omPlug)
						$rowColor = ($Online) ? "#FFA0A0	" : "#D6D6D6"; //State off
				}

				$data->elements[1]->values[] = array(
					"InstanceID" => $key,
					"LightID" => IPS_GetProperty($value['DeviceID'], "DeviceID"),
					//"State" => $State,
					"Name" => IPS_GetName($value['DeviceID']),
					//"UniqueID" => IPS_GetProperty($value['DeviceID'], "UniqueID"),
					"Hue" => $Hue,
					"Color" => ($Color != "") ? "#".$Color : "",
					"CT" => ($ColorTemp != "") ? $ColorTemp."K" : "",
					"Brightness" => $Bright,
					"Saturation" => $Saturation,
					"rowColor" => $rowColor
				);
			}
		}
		
		return json_encode($data);
	}
	
	
  protected function getUniqueID() {
    $UniqueID = $this->ReadPropertyString("UniqueID");
    return $UniqueID;
  }
 
}