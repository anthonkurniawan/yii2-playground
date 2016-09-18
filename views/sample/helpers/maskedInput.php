
Basic Mask: (999) 999-9999 format
echo MaskedInput::widget([
    'name' => 'input-1',
    'mask' => '(999) 999-9999'
]);

Mask with dynamic syntax: 9-a{1,3}9{1,3} format
echo MaskedInput::widget([
    'name' => 'input-2',
    'mask' => '9-a{1,3}9{1,3}'
]);

Non Greedy Repeat: ~ mask "9" or mask "99" or ... mask "9999999999"
echo MaskedInput::widget([
    'name' => 'input-3',
    'mask' => '9',
    'clientOptions' => ['repeat' => 10, 'greedy' => false]
]);

Optional Input: Using optional inputs like in IP addresses (refer a better IP validation below)
echo MaskedInput::widget([
    'name' => 'input-4',
    'mask' => '9[9][9].9[9][9].9[9][9].9[9][9]'
]);

Multiple Masks. Notice how the formats change automatically based on the number of characters typed
echo MaskedInput::widget([
    'name' => 'input-5',
    'mask' => ['99-999-9999', '999-999-9999']
]);

License plate format
echo MaskedInput::widget([
    'name' => 'input-6',
    'mask' => '[9-]AAA-999'
]);

Using Extensions: Date input (dd/mm/yyyy) format
echo MaskedInput::widget([
    'name' => 'input-31',
    'clientOptions' => ['alias' =>  'date']
]);

Using Extensions: Date input (mm/dd/yyyy) format
echo MaskedInput::widget([
    'name' => 'input-32',
    'clientOptions' => ['alias' =>  'mm/dd/yyyy']
]);

Using Extensions: Numeric input (decimal) format. Group separator: , and Radix point: ..
echo MaskedInput::widget([
    'name' => 'input-33',
    'clientOptions' => [
        'alias' =>  'decimal',
        'groupSeparator' => ',',
        'autoGroup' => true
    ],
]);

Using Extensions: IP Address format.
echo MaskedInput::widget([
    'name' => 'input-34',
    'clientOptions' => [
        'alias' =>  'ip'
    ],
]);

Using Extensions: URL format. Type "www.yoursite.com".
echo MaskedInput::widget([
    'name' => 'input-35',
    'clientOptions' => [
        'alias' =>  'url',
    ],
]);

Using Extensions: Email Address format.
echo MaskedInput::widget([
    'name' => 'input-36',
    'clientOptions' => [
        'alias' =>  'email'
    ],
]);

Custom Definitions: Define your own mask definitions (Basic Year).
echo MaskedInput::widget([
    'name' => 'input-37',
    'mask' => 'j', // basic year
    'definitions' => ['j' => [
        'validator' => '[0-9\(\)\.\+/ ]',
        'cardinality' => 4,
        'prevalidator' =>  [
            ['validator' => '[12]', 'cardinality' => 1],
            ['validator' => '(19|20)', 'cardinality' => 2],
            ['validator' => '(19|20)\\d', 'cardinality' => 3],
        ]
    ]]
]);

Custom Definitions: Define your own mask definitions (Advanced Year).
$script = <<< SCRIPT
function (chrs, buffer, pos, strict, opts) {
   var valExp2 = new RegExp("2[0-5]|[01][0-9]");
   return valExp2.test(buffer[pos - 1] + chrs);
}
SCRIPT;
echo MaskedInput::widget([
    'name' => 'input-38',
    'mask' => 'y',
    'definitions' => ['y' => [
        'validator' => new \yii\web\JsExpression($script),
        'cardinality' => 2,
        'definitionSymbol' => 'i' 
    ]]
]);