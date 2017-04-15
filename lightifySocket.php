<?

//Commands
class lightifyCommands extends stdClass {

	# 13 List paired devices (broadcast)
	# 1E List configured groups/zones (broadcast)
	# 26 Get group/zone information (group/zone)
	# 31 Set brigthness (device, group/zone)
	# 32 Set power switch on/off (device, group/zone)
	# 33 Set light color temperature (device, group/zone)
	# 36 Set light color (RGBW) (device, group/zone)
	# 52 Activate scene (device, group/zone)
	# 68 Get device information (device)
	# 6F Gateway Firmware version (broadcast)
	# D5 Cycle group/zone color
	
	const GETPAIREDEVICES = 0x13;
	const GETGROUPLIST = 0x1E;
	const GETGROUPINFO = 0x26;
	const SETBULBBRIGHT = 0x31;
	const SETDEVICESTATE = 0x32;
	const SETCOLORTEMP = 0x33;
	const SETBULBCOLOR = 0x36;
	const SETDEVICESCENE = 0x52;
	const GETDEVICEINFO = 0x68;
	const GETGATEWAYFIRMWARE = 0x6F;
	const BULBCOLORCYCLE = 0xD5;
	
}


//Socket functions		
class lightifySocket extends stdClass {
	
	private $socket = null;
	

  public function __construct ($host, $port) {
		if (!$this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP))
    	die('Unable to create AF_INET socket!');

		//socket connect
		if (socket_connect($this->socket, $host, $port) === false)
			die('Unable to connect to AF_INET socket!');

		//socket options
		socket_set_block($this->socket);
		//socket_set_option($this->socket, SOL_SOCKET, SO_RCVTIMEO, array('sec' => 1, 'usec' => 0));
		//socket_set_option($this->socket, SOL_SOCKET, SO_SNDTIMEO, array('sec' => 1, 'usec' => 0));
  }
	
	
	protected function getSessionToken() {
		$random = substr(str_shuffle('ABCDEF0123456789'), 0, 8);
		$id = "";

		for ($i = 0; $i < 8; $i += 2) {
			$id .= chr(ord(substr($random, $i, 2)));
		}
		return $id;
	}


	protected function sendData($flag, $command, $args = null) {
		//$data = $flag.chr($command).chr(0x00).chr(0x00).chr(0x00).chr(0x00);
		$data = $flag.chr($command).$this->getSessionToken();
		if ($args != null) $data .= $args;
		
		$data = chr(strlen($data)).chr(0x00).$data;
		$result = socket_write($this->socket, $data, strlen($data));

		if ($result > 0) {
			if (false === ($buffer = socket_read($this->socket, 4096))) //Read 4096 bytes block
				die('Unable to read from AF_INET socket!');
			$length = strlen($buffer);

			if ($length > 9) {
				//$errno = ord($buffer{8});
				if (0 == ($errno = ord($buffer{8}))) return $buffer;
			}
		}

		return false;
	}
	

	public function setAllLightsState($Value) {
		$args = str_repeat(chr(0xFF), 8).chr($Value);
		$buffer = $this->sendData(chr(0x00), lightifyCommands::SETDEVICESTATE, $args);

		return ((strlen($buffer) == 20) ? $buffer : false);
	}


	public function setState($MAC, $flag, $Value) {
		$args = $MAC.chr($Value);
		$buffer = $this->sendData($flag, lightifyCommands::SETDEVICESTATE, $args);
		
		return ((strlen($buffer) == 20) ? $buffer : false);
	}
	

	public function setColor($MAC, $flag, $Value, $Transition) {
		$args = $MAC.chr($Value['r']).chr($Value['g']).chr($Value['b']).chr(0xFF).chr(dechex($Transition)).chr(0x00);
		$buffer = $this->sendData($flag, lightifyCommands::SETBULBCOLOR, $args);

		return ((strlen($buffer) == 20) ? $buffer : false);
	}


	public function setColorTemperature($MAC, $flag, $Value, $Transition) {
		$hex = dechex($Value);
		if (strlen($hex) < 4) $hex = str_repeat("0", 4-strlen($hex)).$hex;
							
		$args = $MAC.chr(hexdec(substr($hex, 2, 2))).chr(hexdec(substr($hex, 0, 2))).chr(dechex($Transition)).chr(0x00);
		$buffer = $this->sendData($flag, lightifyCommands::SETCOLORTEMP, $args);
	
		return ((strlen($buffer) == 20) ? $buffer : false);
	}


	public function setBrightness($MAC, $flag, $Value, $Transition) {
		$args = $MAC.chr($Value).chr(dechex($Transition)).chr(0x00);
		$buffer = $this->sendData($flag, lightifyCommands::SETBULBBRIGHT, $args);

		return ((strlen($buffer) == 20) ? $buffer : false);
	}


	public function setSaturation($MAC, $flag, $Value, $Transition) {
		$args = $MAC.chr($Value['r']).chr($Value['g']).chr($Value['b']).chr(0xFF).chr(dechex($Transition)).chr(0x00);
		$buffer = $this->sendData($flag, lightifyCommands::SETBULBCOLOR, $args);

		return ((strlen($buffer) == 20) ? $buffer : false);
	}


	public function setColorCycle($MAC, $Cycle, $Value) {
		$Value = dechex($Value);
		$Value = str_repeat("0", 4-strlen($Value)).$Value;

		$args = $MAC.(($Cycle) ? chr(0x01) : chr(0x00)).chr(hexdec(substr($Value, 2, 2))).chr(hexdec(substr($Value, 0, 2)));
		$buffer = $this->sendData(chr(0x00), lightifyCommands::BULBCOLORCYCLE, $args);

		return ((strlen($buffer) == 20) ? $buffer : false);
	}
			
								
	public function getPairedDevices() {
		$args = chr(0x01).chr(0x00).chr(0x00).chr(0x00).chr(0x00);
		return $this->sendData(chr(0x00), lightifyCommands::GETPAIREDEVICES, $args);
	}
	
	
	public function getGroupList() {
		return $this->sendData(chr(0x00), lightifyCommands::GETGROUPLIST);
	}


	public function getDeviceInfo($MAC) {
		return $this->sendData(chr(0x00), lightifyCommands::GETDEVICEINFO, $MAC);
	}
	

	public function getGroupInfo($MAC) {
		return $this->sendData(chr(0x00), lightifyCommands::GETGROUPINFO, $MAC);
	}
	
							
	public function getGatewayFirmware() {
		return $this->sendData(chr(0x00), lightifyCommands::GETGATEWAYFIRMWARE);
	}


	public function getUnknownInfo($command, $MAC) {
		return $this->sendData(chr(0x00), $command, $MAC);
	}


	function __desctruct() {
		if ($this->socket) socket_close($this-socket);
	}

}




