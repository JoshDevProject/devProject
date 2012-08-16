<?php

$multiArray = array(
    "Hi" => array(
        "blah" => "blah2",
        "blah1" => "meh"),
    array(          //implied "0"=>array(
        1,              //implied "0"=>1
        2,              //implied "1"=>2
        3,              //implied "2"=>3
        array(          //implied "3"=>array(
            122,            //implied "0"=>122
            3,              //implied "1"=>3
            4,              //implied "2"=>4
            3))             //implied "3"=>3
);

$singleArray = array(
    "Hi/blah/blah2",
    "Hi/blah1/meh",
    "0/0/1",
    "0/1/2",
    "0/2/3",
    "0/3/0/122",
    "0/3/1/3",
    "0/3/2/4",
    "0/3/3/3"
);

function convertMultiToSingle($multiArray, &$singleArray, $path='')
{
    //loop through this dimension of the array
    foreach ($multiArray as $key => $value)
    {
        //if the value is not another array
        if (!is_array($value))
        {
            //add it to the single dimensional array
            $singleArray[] = $path . '/' . $key . '/' . $value . '<br/>';
        }
        else convertMultiToSingle($value, $singleArray, $path . '/' . $key);
    }
}

function convertSingleToMulti($singleArray, &$multiArray)
{
    //loop through each dimension of the array
    foreach ($singleArray as $key => $value)
    {
        $tokens = explode("/", $value);
        addTokensToArray($multiArray, $tokens);
        foreach ($tokens as $key2 => $value2)
        {
            echo $value2 . "/";
        }
        echo "<br/>";

    }
}

function addTokensToArray($multiArray, $tokens)
{
    //if there are no tokens, return
    if (count($tokens) == 0)
        return;
    //loop through the array
    foreach ($multiArray as $key => $value)
    {
        //if we match
        if ($value == $tokens[0])
        {
            echo "Found match";
            //proceed into the array
            array_shift($tokens);
            addTokensToArray($multiArray[$value], $tokens);
        }
        else //if 
        //we dont have a match
        {
            $multiArray[$key] = $value;
            echo $value . "<br/>";
        }
    }    
}

//convert multi to single
echo "<br/>==================================================================<br/>";
echo "Converting Multidimensional Array to Single Dimension...</br>";
$convertedArray = array();
convertMultiToSingle($multiArray, $convertedArray);
echo "Multidimensional Array: </br>";
print_r($multiArray);
echo "<br/>New Single Dimensional Array: </br>";
print_r($convertedArray);

echo "<br/>==================================================================<br/>";
//convert single to multi
echo "Converting Single Dimensional Array to Multiple Dimensions...</br>";
$convertedArray = array();
convertSingleToMulti($singleArray, $convertedArray);
echo "Single Dimensional Array: </br>";
print_r($singleArray);
echo "<br/>New Multidimensional Array: </br>";
print_r($convertedArray);
?>
