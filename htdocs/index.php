<?php
define('DOCUMENTROOT' , '../');
require_once DOCUMENTROOT.'core/Core.php';
run();

/*dispatch('/', 'hello');
function hello()
{
        return 'Hello world!';
}

dispatch('/abc/:size', 'hellos' ,  array('validation_function' => 'a_silly_validation_function'));
function hellos($c)
{

	$c = new FunComm();
	var_dump(params('size'));
		var_dump(url_for('c'));
        return 'Hello worldwww!';
}

function a_silly_validation_function($params)
{

	var_dump($params);
  //perhaps this looks something up in the database...
  if (isset($params[0]) && $params[0] == "1234") return true;
  
  return false;
}
*/
?>