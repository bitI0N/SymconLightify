<?

# Commands
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

define('OSR_GETPAIREDEVICES', 0x13);
define('OSR_GETGROUPLIST', 0x1E);
define('OSR_GETGROUPINFO', 0x26);
define('OSR_SETBULBBRIGHT', 0x31);
define('OSR_SETDEVICESTATE', 0x32);
define('OSR_SETCOLORTEMP', 0x33);
define('OSR_SETBULBCOLOR', 0x36);
define('OSR_SETDEVICESCENE', 0x52);
define('OSR_GETDEVICEINFO', 0x68);
define('OSR_GETGATEWAYFIRMWARE', 0x6F);
define('OSR_BULBCOLORCYCLE', 0xD5);

define('OSR_TRANSITION', 0x00); //0.0 sec
define('OSR_TRANSITMAX', 0x50); //8.0 sec

		
class lightifySocket {
	
	private $socket = null;
	

  public function __construct ($host, $port) {
		if (!$this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP))
    	die('Unable to create AF_INET socket!');

		//socket connect
		if (socket_connect($this->socket, $host, $port) === false)
			die('Unable to connect to AF_INET socket!');

		//socket options
		time_nanosleep(0, 500000000);
		socket_set_block($this->socket);
		//socket_set_option($this->socket, SOL_SOCKET, SO_RCVTIMEO, array('sec' => 1, 'usec' => 0));
		//socket_set_option($this->socket, SOL_SOCKET, SO_SNDTIMEO, array('sec' => 1, 'usec' => 0));
  }
	

	protected function SendData($flag, $command, $args = null) {
		$data = $flag.chr($command).chr(0x00).chr(0x00).chr(0x00).chr(0x00);
		if ($args != null) $data .= $args;
		
		$data = chr(strlen($data)).chr(0x00).$data;
		$result = socket_write($this->socket, $data, strlen($data));

		if ($result > 0) {
			if (false === ($buffer = socket_read($this->socket, 4096))) //Read 4096 bytes block
				die('Unable to read from AF_INET socket!');
			$length = strlen($buffer);

			if ($length > 9) {
				$errno = ord($buffer{8});
				if ($errno == false) return $buffer;
			}
		}

		return $result;
	}
	

	public function AllLights($Value) {
		$args = str_repeat(chr(0xFF), 8).chr($Value);
		$buffer = $this->SendData(chr(0x00), OSR_SETDEVICESTATE, $args);

		return ((strlen($buffer) == 20) ? true : false);
	}


	public function State($MAC, $flag, $Value) {
		$args = $MAC.chr($Value);
		$buffer = $this->SendData($flag, OSR_SETDEVICESTATE, $args);
		
		return ((strlen($buffer) == 20) ? true : false);
	}
	

	public function Color($MAC, $flag, $Value) {
		$args = $MAC.chr($Value['r']).chr($Value['g']).chr($Value['b']).chr(0xFF).chr(OSR_TRANSITION).chr(0x00);
		$buffer = $this->SendData($flag, OSR_SETBULBCOLOR, $args);

		return ((strlen($buffer) == 20) ? true : false);
	}


	public function ColorTemperature($MAC, $flag, $Value) {
		$hex = dechex($Value);
		if (strlen($hex) < 4) $hex = str_repeat("0", 4-strlen($hex)).$hex;
							
		$args = $MAC.chr(hexdec(substr($hex, 2, 2))).chr(hexdec(substr($hex, 0, 2))).chr(OSR_TRANSITION).chr(0x00);
		$buffer = $this->SendData($flag, OSR_SETCOLORTEMP, $args);
	
		return ((strlen($buffer) == 20) ? true : false);
	}


	public function Brightness($MAC, $flag, $Value) {
		$args = $MAC.chr($Value).chr(OSR_TRANSITION).chr(0x00);
		$buffer = $this->SendData($flag, OSR_SETBULBBRIGHT, $args);

		return ((strlen($buffer) == 20) ? true : false);
	}


	public function Saturation($MAC, $flag, $Value) {
		$args = $MAC.chr($Value['r']).chr($Value['g']).chr($Value['b']).chr(0xFF).chr(OSR_TRANSITION).chr(0x00);
		$buffer = $this->SendData($flag, OSR_SETBULBCOLOR, $args);

		return ((strlen($buffer) == 20) ? true : false);
	}


	public function ColorCycle($MAC, $Cycle, $Value) {
		$Value = dechex($Value); 
		$Value = str_repeat("0", 4-strlen($Value)).$Value;

		$args = $MAC.(($Cycle) ? chr(0x01) : chr(0x00)).chr(hexdec(substr($Value, 2, 2))).chr(hexdec(substr($Value, 0, 2)));
		$buffer = $this->SendData(chr(0x00), OSR_BULBCOLORCYCLE, $args);

		return ((strlen($buffer) == 20) ? true : false);
	}
			
								
	public function PairedDevices() {
		$args = chr(0x01).chr(0x00).chr(0x00).chr(0x00).chr(0x00);
		return $this->SendData(chr(0x00), OSR_GETPAIREDEVICES, $args);
	}
	
	
	public function GroupList() {
		return $this->SendData(chr(0x00), OSR_GETGROUPLIST);
	}


	public function DeviceInfo($MAC) {
		return $this->SendData(chr(0x00), OSR_GETDEVICEINFO, $MAC);
	}
	

	public function GroupInfo($MAC) {
		return $this->SendData(chr(0x00), OSR_GETGROUPINFO, $MAC);
	}
	
							
	public function GatewayFirmware() {
		return $this->SendData(chr(0x00), OSR_GETGATEWAYFIRMWARE);
	}


	function __desctruct() {
		if ($this->socket) socket_close($this-socket);
	}

}





