<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use yii\console\Controller;
use yii\helpers\Console;
use yii\helpers\VarDumper;
use yii\helpers\ArrayHelper; 
use yii\helpers\Json; 

/**
 * This command is provided as an example console commands.
 * Note :
 * - description on sub command : hanya baca pada line pertama.
 * - When using \* in console, don't forget to quote it as "*" in order to avoid executing it as a shell glob that will be replaced by all file names of the current directory.
 */
class CmdTestController extends Controller
{
    public $msg;
    /**
     * This command echoes what you entered as the msg
     * @param string $message the message to be echoed.
     */
    public function actionMsg($message = 'hello world')
    {  
        echo "print with echo : $message ?" . "\n";
        $this->stdout("\n print with STDOUT : $message?\n", Console::BOLD);  // Prints a string to STDOUT
        //If you need to build string dynamically combining multiple styles it's better to use ansiFormat:
        echo $this->ansiFormat("\n print with ANSI : $message?\n", Console::FG_YELLOW, Console::UNDERLINE); // Formats a string with ANSI codes
        $this->stderr("\n print with STDERR : $message?\n", Console::FG_YELLOW, Console::UNDERLINE); // Prints a string to STDERR
    }
    
    /**
    * for test exitt code
    * Using exit codes is a best practice for console application development. Conventionally, a command returns 0 to indicate that everything is OK. 
    * If the command returns a number greater than zero, that's considered to be indicative of an error. 
    * The number returned will be the error code, potentially usable to find out details about the error
    */
    public function actionExitCode($error)
    {
        if ($error) {
            echo "A problem occured!\n";
            return 1;  # self::EXIT_CODE_ERROR;
        }
        // do something
        return 0; # # self::EXIT_CODE_NORMAL;
    }
    
    /** 
    Show class all/specified key properties value
    **/
    public function actionIndex($key=null, $depth=3){
        if($key){
            $controller = ArrayHelper::toArray($this, [], false);   
            VarDumper::dump( ArrayHelper::getValue($controller, $key), $depth);  //print_r($value);  // 'module', 'module.defaultRoute'
        }
        else VarDumper::dump($this, $depth); 
    }
    
    /** 
    Prompt testing, with validator, required input
    **/
    public function actionPrompt($msg=null){
        //$this->prompt('xx');   // biaa enter tanpa masukan variable
        // $this->prompt('xx',['required'=>true]);   // harus masukan variable input
        $this->prompt('masukan message : ',['validator'=>function($input, $error){   //echo $input . gettype($input);
            if(strlen($input) >=2 ){
                $this->msg = $input;
                return true;
            }
            else echo 'lenght value must be great than 2 char - ';
        }, 'required'=>true]);   // masukan variable input optional, set required for valid.
        
        echo "message : $this->msg" ."(". gettype($this->msg) .")";
    }
    
    /**
     * Asks user to confirm by typing y or n.
     */
    public function actionConfirm(){
        if($this->confirm('Yakin loe..??'))
            echo "Ok deh kaka..";
        else
            echo "Ga yakin diee..:p";
    }
    
    /**
     * Gives the user an option to choose from. Giving '?' as an input will show
     * a list of options to choose from and their explanations.
     */
    public function actionSelect(){
        $select = $this->select('Pilih donk akhh..', ['kopi'=>'kopi', 'susu'=>'susu']);
        echo "Pilihan nya adalah : $select";
    }
    
    /**
     * Returns help information for this controller.
     * @detail default : true, if false then get summary doc
     */
    public function actionDoc($detail=true){
        if($detail)
            echo $this->getHelp();
        else
            echo $this->getHelpSummary();
        // getActionHelp($action)
        // getActionArgsHelp($action)
        // getActionOptionsHelp($action)
        
        // getActionMethodReflection($action)  -- protected
    }
    
    /**
     * Starts display of a progress bar on screen.
     * This bar will be updated by [[updateProgress()]] and my be ended by [[endProgress()]].
     * @detail default : true, if false then get summary doc
     */
    public function actionProgress($total=100){
        $select = $this->select('pilih sample : ', [1=>'sample1', 2=>'sample2']);
        if($select==1){
            Console::startProgress(0, $total);
            for ($n = 1; $n <= $total; $n++) {
                usleep($total);
                Console::updateProgress($n, $total);
            }
            Console::endProgress();
        }
        else{
            # Git clone like progress (showing only status information):
            Console::startProgress(0, $total, 'Counting objects: ', false);
            for ($n = 1; $n <= $total; $n++) {
                usleep($total);
                Console::updateProgress($n, $total);
            }
            Console::endProgress("done." . PHP_EOL);
        }
    }
    
    public function beforeAction($event){
        //VarDumper::dump($event, 2);
        //echo $this->getActionHelpSummary($event);
        echo "Descriptions : ". $this->getActionHelp($event). "\nResult : \n";
        
        //$action_args = $this->getActionArgsHelp($event);  # args action param doc
        //echo "\n Params" . preg_replace('/[{}]/', '', Json::encode($action_args));
        //$action_opt = $this->getActionOptionsHelp($event); print_r($action_opt); # options params doc
        return true;
    }
    
    // public function afterAction($action, $result){
        // $result = parent::afterAction($action, $result);
        // // your custom code here
        // VarDumper::dump($action, 2);
        // return $result;
    // }
    
}
