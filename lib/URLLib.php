<?php
class URLLib
{
	public static function url_parse($env = NULL){
		if ($env === NULL)
		{
			$env		=	$_SERVER;
		}
		$path	=	self::parse_uri_detect($env);
		$path	=	empty($path) ? self::parse_uri_param($env) : $path;
		return $path;
	}

	private function parse_cli_args($env  = NULL)
	{
		if ($env === NULL)
		{
			$server	= $_SERVER;
		}
		else
		{
			$server		=	$env['SERVER'];
		}
		$args		=	array_slice($server['argv'], 1);
		return $args ? '/' . implode('/', $args) : '';
	}

	public function parse_uri_param($env = NULL)
	{
		if ($env === NULL)
		{
			$server		=	$_SERVER;
		}
		else
		{
			$server		=	$env['SERVER'];
		}
		$query_string	=	$server['QUERY_STRING'];
		if (empty($query_string))
		{
			return '';
		}
		parse_str($query_string , $request_info);
		$ctl		=	CoreCfg::$ctl_url_params;
		$runctl		=	$request_info[$ctl[0]];
		if (!isset($request_info[$ctl[0]]))  return '';
		if (!isset($request_info[$ctl[1]]))  return  array($runctl);
		return array($runctl , $request_info[$ctl[1]]);
	}

	public function parse_uri_detect($env)
	{
		if ($env === NULL)
		{
			$server		=	$_SERVER;
		}
		else
		{
			$server		=	$env['SERVER'];
		}
		if ( ! isset($server['REQUEST_URI']) OR ! isset($server['SCRIPT_NAME']))
		{
			return '';
		}
		$uri = $server['REQUEST_URI'];
		
		if (strpos($uri, $server['SCRIPT_NAME']) === 0)
		{
			$uri = substr($uri, strlen($server['SCRIPT_NAME']));
		}
		elseif (strpos($uri, dirname($server['SCRIPT_NAME'])) === 0)
		{
			$uri = substr($uri, strlen(dirname($server['SCRIPT_NAME'])));
		}
		if (strncmp($uri, '?/', 2) === 0)
		{
			$uri = substr($uri, 2);
		}
		$parts = preg_split('#\?#i', $uri, 2);
		$uri = $parts[0];
		if (isset($parts[1]))
		{
			$server['QUERY_STRING'] = $parts[1];
			parse_str($server['QUERY_STRING'], $_GET);
		}
		else
		{
			$server['QUERY_STRING'] = '';
			$_GET = array();
		}
		if ($uri == '/' || empty($uri))
		{
			return '';
		}
		$uri = parse_url($uri, PHP_URL_PATH);
		$uri = str_replace(array('//', '../'), '/', trim($uri, '/'));
		$uri = explode('/' , $uri);
		return $uri;
	}
}