<?php
class CoreCfg
{
	public static $ctl_folder		=	array(
			'/'
	);

	public static $storage_folder	=	array(
			'/',
			'abc/',
	);

	public static $comm_folder		=	array(
			'/',
	);
	
	public static $lib_folder		=	array(
			'/',
	);
	
	public static $hlp_folder		=	array(
			'/',
	);
	
	public static $cfg_folder		=	array(
			'/',
	);

	public static $model_folder		=	array(
			'/',
	);

	public static $file_type_name		=	array(
			'ctl'		=>	'controller',
			'model'		=>	'model',
			'lib'		=>	'lib',
			'hlp'		=>	'helper',
			'cfg'		=>	'config',
			'comm'		=>	'comm',
			'storage'	=>	'storage',
	);
	
	public static $ctl_url_params	= array
	(
			'c' , 'a'
	);

	public static $ctl_default_method	=	array
	(
			'index' , 'default'
	);

}