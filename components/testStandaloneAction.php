<?php
/* 
The return value of an action method or the run() method of a standalone action is significant. It stands for the result of the corresponding action.
The return value can be a response object which will be sent to as the response to end users.

For Web applications, the return value can also be some arbitrary data which will be assigned to yii\web\Response::$data and 
be further converted into a string representing the response body.
For console applications, the return value can also be an integer representing the exit status of the command execution.
In the examples shown above, the action results are all strings which will be treated as the response body to be sent to end users. 
The following example shows how an action can redirect the user browser to a new URL by returning a response object (because the redirect() method returns a response object):
 */
namespace app\components;

use yii\base\Action;

class testStandaloneAction extends Action
{
    public function run(){
        print_r($this);
        return "Ini dari testStandaloneAction (cocok buat kaya prosess migrate db)";
    }
	
	
}
?>