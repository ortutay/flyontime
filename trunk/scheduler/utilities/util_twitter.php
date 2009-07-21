<?php
		
	class util_twitter
	{
		public static function isValidResponse($response)
		{
			if($response !== false)
			{
				try
				{
					if($response["header"]["HTTP-Response"]["Code"] == "200")
						return true;
				} catch (Exception $e) {}
			}
			
			return false;
		}
		
		public static function search($params)
		{
			if(!isset($params["q"]))
				return false;
			
			//get q
			$q = $params["q"];
			if(strlen($q) > 140)
				return false;
			
			$q_encoded = rawurlencode($q);
			
			//get rpp
			$rpp = "";
			if(isset($params["rpp"]))
				$rpp = $params["rpp"];
			
			//get page
			$page = "";
			if(isset($params["page"]))
				$page = $params["page"];
				
			//ge since_id
			$since_id = "";
			if(isset($params["since_id"]))
				$since_id = $params["since_id"];
			
			$url = $GLOBALS["twitter_search_api_root"].".json?q={$q_encoded}";
			
			if($rpp != "")
				$url .= "&rpp=".$rpp;
				
			if($page != "")
				$url .= "&page=".$page;
			
			if($since_id != "")
				$url .= "&since_id=".$since_id;
			
			return self::nonAuthJSONQuery($url);
		}
		
		public static function nonAuthJSONQuery($url)
		{
			try
			{
				$body_json = file_get_contents($url);
				
				$header = self::parseHeader($http_response_header);
				
				$response = array(
					"header" => $header,
					"body" => json_decode($body_json, true)
				);
				
				return $response;
				
			} catch (Exception $e) {}
			
			return false;
		}
		
		private static function parseHeader($header_raw)
		{
			$header = array();
			
			foreach($header_raw as $item)
			{
				$i = strpos($item, ":");
				
				if($i !== false)
				{
					$name = substr($item, 0, $i);
					$value = substr($item, $i + 2);
					
					$header[$name] = $value;
				}
				else
				{
					if(substr($item, 0, 5) == "HTTP/")
					{
						$arr = explode(" ", $item);
						$header["HTTP-Response"] = array(
							"Version" => $arr[0],
							"Code" => $arr[1],
							"Msg" => $arr[2]
						);
					}
					else
					{
						$header[$item] = $item;
					}
				}
			}
			
			return $header;
		}

	}
?>