<?php
if ( ! defined('DOCUMENTROOT')) exit('No direct script access allowed');
class LoadClass
{
	/**
	 * load class list
	 *
	 * @var array
	 */	
	private static $_items		=	array();
	/**
	 * STATIC CLASS
	 *
	 * @var int
	 */	
	const STATICCLASS	=	0;
	/**
	 * DYNAMIC CLASS
	 *
	 * @var int
	 */
	const DYNAMICCLASS	=	1;

	/**
	 * other CLASS
	 *
	 * @var int
	 */
	const OTHERCLASS	=	2;

	/**
	 * auto load class file
	 *
	 * @access public 
	 * @param  string $class_name
	 * @return void
	 */	
	public static function auto_load($class_name)
	{
		$class_name				=	trim($class_name);
		if ($class_object = self::get_item($class_name))
		{
			return $class_object;
		}
		self::load_static_class('CoreCfg' , 'config');
		if ($class_name == 'CoreCfg') return TRUE;
		$file_type_name		=	CoreCfg::$file_type_name;
		$file_type_prefix	=	implode("|" , array_keys($file_type_name));
		if (preg_match('/^\w+('.$file_type_prefix.')$/i', $class_name , $match_prefix))
		{
			if (count($match_prefix) <= 1)
			{
				halt(SERVER_ERROR, "CLASS error: not find class_name'", $class_name);
				return FALSE;
			}
			$prefix_subname		=	strtolower($match_prefix[1]);
			$file_path			=	$file_type_name[$prefix_subname];
			$run_path_config    =	rtrim(DOCUMENTROOT.$file_path , '/').'/'.'config.php';
			if (file_exists($run_path_config))
			{
				$path_config	=	include_once($run_path_config);
				if (isset($path_config[$class_name]))
				{
					include_once($path_config[$class_name]);
					return TRUE;
				}			
			}
			$var_name			=	$prefix_subname.'_folder';
			$file_folder		=	array();
			if (property_exists('CoreCfg' , $var_name))
			{
				$file_folder	=	CoreCfg::${$var_name};
			}
			foreach ($file_folder as $folder)
			{
				$file_path	=	$file_path.$folder;
				self::load_static_class($class_name , $file_path);
			}
		}
		else
		{
			halt(SERVER_ERROR, "CLASS error: not find class_name'", $class_name);
			return FALSE;
		}
	}
	/**
	 * load class by dynamic
	 *
	 * @access public 
	 * @param  string $class_name
	 * @param  string $folder
	 * @return object
	 */		
	public static function load_dynamic_class($class_name , $folder)
	{
		if (($class_object = self::get_item($class_name , self::DYNAMICCLASS)))
		{
			return $class_object;
		}
		$folder		=	rtrim(DOCUMENTROOT.$folder , '/').'/';
		if (!is_dir($folder))
		{
			return FALSE;
		}
		$is_loaded	=	self::load_file($class_name , $folder);
		if ($is_loaded === FALSE)
		{
			return FALSE;
		}
		$arg_list = func_get_args();
		if (count($arg_list) > 2)
		{
			list($n1 , $n2 , $n3 , $n4 ,$_)		=	array_slice($arg_list , 2);
			$call_function						=	(new $class_name($n1 , $n2 , $n3 , $n4 ,$_));
		}
		else
		{
			$call_function						=	 new $class_name;	
		}
		self::set_item($class_name , $call_function , self::DYNAMICCLASS);
		return  $call_function;
	}
	/**
	 * load class by static
	 *
	 * @access public 
	 * @param  string $class_name
	 * @param  string  $folder
	 * @return string
	 */		
	public static function load_static_class($class_name , $folder = 'core')
	{
		$folder		=	rtrim(DOCUMENTROOT.$folder , '/').'/';
		if (!is_dir($folder))
		{
			return FALSE;
		}
		return self::load_file($class_name , $folder) ? $class_name : NULL;
	}
	/**
	 * load class file
	 *
	 * @access public 
	 * @param  string $class_name
	 * @param  string  $file_path
	 * @return bool
	 */	
	private static function load_file($class_name ,  $file_path)
	{
		if (($class_object = self::get_item($class_name)))
		{
			return $class_name;
		}
		$file_path	=	$file_path.$class_name.'.class.php';
		if (!file_exists($file_path))
		{
			return FALSE;
		}
		require($file_path);
		self::set_item($class_name , $file_path);
		return TRUE;
	}
	/**
	 * set load class in items
	 *
	 * @access public 
	 * @param  mixed $key
	 * @param  mixed $value
	 * @param  int   $type
	 * @return bool
	 */	
	public static function set_item($key , $value , $type = self::STATICCLASS)
	{
		$items					=	&self::$_items;
		$items[$type][$key]		=	$value;
		return  TRUE;
	}

	/**
	 * get load class by key
	 *
	 * @access public 
	 * @param  mixed $key
	 * @param  int   $type
	 * @return bool
	 */	
	public static function get_item($key , $type = self::STATICCLASS)
	{
		$items	=	&self::$_items;	
		if (isset($items[$type][$key]))
		{
			return $items[$type][$key];
		}
		return FALSE;
	}	
	/**
	 * set load class item
	 *
	 * @access public 
	 * @return array
	 */	
	public static function get_item_all()
	{
		$items	=	&self::$_items;
		return $items;
	}
}