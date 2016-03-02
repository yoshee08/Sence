<?php
/*

	*************************************************
	*	Author: Grohs Fabian						*
	*	Webite: grohsfabian.com						*
	*	Portofolio: codecanyon.net/user/neeesteea	*
	*	1.7 Query by xPaw							*
	*************************************************

*/
class McException extends Exception {}

class MinecraftQuery {
	private $ip;
	private $port;
	private $socket;
	private $timeout;
	
	public function __construct($ip, $port = 25565, $timeout = 2) {
		/* Define variables and connect to the server */
		$this->ip = $ip;
		$this->port = $port;
		$this->timeout = $port;
		
		$this->connect();
	}
	
	public function connect() {
		/* Try and connect to the server */
		$this->socket = @fsockopen("tcp://" . $this->ip, $this->port, $errno, $errstr, $this->timeout);
		//if(!$this->socket) throw new McException($errstr);
		return false;
		stream_set_timeout($this->socket, $this->timeout);
		socket_set_nonblock($this->socket);
print_r($this->socket);	
}
	
	public function close() {
		fclose($this->socket);
		$this->socket = null;
	}
	
	public function Query() {
		/* Try the 1.7 query protocol */
		$response = $this->QueryNew();

		/* Else use the older query protocol */
		if($response == false) {
			$this->close();
			$this->connect();
			$response = $this->QueryOld();
		}
		return $response;
	}
	
	public function QueryNew() {
	
		/* Handshake using xPaw's scraddresst */
		$data = "\x00";  										// packet ID = 0 (varint)
		$data .= "\x04"; 										// Protocol version (varint)
		$data .= pack('c', strlen($this->ip)) . $this->ip;  // Server (varint len + UTF-8 addr)
		$data .= pack('n', $this->port);						// Server port (unsigned short)
		$data .= "\x01"; 										// Next state: status (varint)
		$data = pack('c', strlen($data)) . $data; 			// prepend length of packet ID + data

		/* Sending the data to the server */
		@fwrite($this->socket, $data); 
	
		/* Request data */
		@fwrite($this->socket, "\x01\x00");
		
		/* Read the length with xpaws scraddresst */
		$length = $this->ReadVarInt();

		/* Initiate the $data variable ( where returned data from the server will be stored ) */
		$data = "";

		/* Read until there is no more data to read */
		while(strlen($data) < $length) {
			$r = $length - strlen($data);
			$data .= fread($this->socket, $r);
		}

		/* Decode JSON */
		$data = json_decode(substr($data, 3));

		if($data == false) {} else {
			if(is_object($data->description)) {
				$data->description->text = preg_replace("/(§.)/", "",$data->description->text);
				$data->description->text = preg_replace("/[^[:alnum:][:punct:] ]/", "", $data->description->text);
			} else {
				$data->description = preg_replace("/(§.)/", "",$data->description);
				$data->description = preg_replace("/[^[:alnum:][:punct:] ]/", "", $data->description);
			}
			return array(
				'status' 		=> 1,
				'HostName'		=> (is_object($data->description)) ? $data->description->text : $data->description,
				'Players'		=> $data->players->online,
				'MaxPlayers'	=> $data->players->max,
				'serverVersion' => $data->version->name,
				'favicon'		=> $data->favicon
			);
		}
		
	}
	
	public function QueryOld() {
		/* Send the request */
		fwrite($this->socket, "\xFE\x01");
		
		/* Receiving data */
		$data = fread($this->socket, 1024);
		if($data == false) return false;
		
		/* Ensure we're getting a kick message as expected */
		if ($data != false && substr($data, 0, 1) == "\xFF") 
		{
			/* Remove packet, length and starting characters */
			$data = substr($data, 9); 
			
			/* Separate string into array */
			$data = explode("\x00\x00", $data);
			
			/* Define the results into some variables */
			$protocolVersion = $data[0];
			@$serverVersion = mb_convert_encoding($data[1], 'UTF-8', 'UCS-2');
			@$motd = $data[2];
			$motd = mb_convert_encoding($motd, 'UTF-8', 'UCS-2');
			@$players = mb_convert_encoding($data[3], 'UTF-8', 'UCS-2');
			@$max_players = mb_convert_encoding($data[4], 'UTF-8', 'UCS-2');
			
			/* Return data or error */
			return array(
				'status' => 1,
				'HostName' => $motd,
				'Players' => $players,
				'MaxPlayers' => $max_players,
				'serverVersion' => $serverVersion,
				'favicon' => false
			);
		}
	}
	
	public function getStatus() {
		return (!$this->socket) ? false:true ;
	}
	
	public function formatMOTD($motd){
		$search  = array('§0', '§1', '§2', '§3', '§4', '§5', '§6', '§7', '§8', '§9', '§a', '§b', '§c', '§d', '§e', '§f', '§l', '§m', '§n', '§o', '§k', '§r');
		$replace = array('<font color="#000000">', '<font color="#0000AA">', '<font color="#00AA00">', '<font color="#00AAAA">', '<font color="#aa00aa">', '<font color="#ffaa00">', '<font color="#aaaaaa">', '<font color="#555555">', '<font color="#5555ff">', '<font color="#55ff55">', '<font color="#55ffff">', '<font color="#ff5555">', '<font color="#ff55ff">', '<font color="#ffff55">', '<font color="#ffffff">', '<font color="#000000">', '<b>', '<u>', '<i>', '<font color="#000000">', '<font color="#000000">');
		$motd  = str_replace($search, $replace, $motd);
		
		return $motd;
	}

	private function ReadVarInt() {

		$i = 0;
		$j = 0;

		while( true )
		{
			$k = @fgetc( $this->socket );

			if( $k === FALSE )
			{
				return 0;
			}

			$k = Ord( $k );

			$i |= ( $k & 0x7F ) << $j++ * 7;

			if( $j > 5 )
			{
				throw new Exception( 'VarInt too big' );
			}

			if( ( $k & 0x80 ) != 128 )
			{
				break;
			}
		}

		return $i;
	}
	
}

?>
