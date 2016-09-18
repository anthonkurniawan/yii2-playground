<?php
/**
Behaviors
Behaviors are instances of yii\base\Behavior, or of a child class. Behaviors, also known as mixins, 
allow you to enhance the functionality of an existing component class without needing to change the class's inheritance. 
Attaching a behavior to a component "injects" the behavior's methods and properties into the component, making those methods and properties 
accessible as if they were defined in the component class itself. Moreover, a behavior can respond to the events triggered by the component, 
which allows behaviors to also customize the normal code execution of the component.

Defining Behaviors
To define a behavior, create a class that extends yii\base\Behavior, or extends a child class. For example:
The above code defines the behavior class app\components\MyBehavior, with two properties-- prop1 and prop2--and one method foo(). 
Note that property prop2 is defined via the getter getProp2() and the setter setProp2(). 
This is the case because yii\base\Behavior extends yii\base\Object and therefore supports defining properties via getters and setters.

Because this class is a behavior, when it is attached to a component, that component will then also have the the prop1 and prop2 properties and the foo() method.

Tip: Within a behavior, you can access the component that the behavior is attached to through the yii\base\Behavior::$owner property.
***/

namespace app\components;

use yii\base\Behavior;
use yii\db\ActiveRecord;

class MyBehavior extends Behavior
{
    public $prop1;

    private $_prop2;

    public function getProp2()
    {
        return $this->_prop2;
    }

    public function setProp2($value)
    {
        $this->_prop2 = $value;
    }

    public function foo()
    {
        // ...
    }
    
    # If a behavior needs to respond to the events triggered by the component it is attached to, it should override the yii\base\Behavior::events() method
    # The events() method should return a list of events and their corresponding handlers. this declares that the EVENT_BEFORE_VALIDATE event exists 
    # and defines its handler, beforeValidate(). 
    public function events()
    {
        # an array of an object or class name, and a method name as a string (without parentheses), e.g., [$object, 'methodName'];
        return [
            ActiveRecord::EVENT_BEFORE_VALIDATE => 'beforeValidate',  // a string that refers to the name of a method of the behavior class, 
        ];
    }

    # The signature of an event handler should be as follows, where $event refers to the event parameter.
    public function beforeValidate($event)
    {
        // ...
    }
}
?>