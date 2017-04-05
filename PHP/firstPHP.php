<!DOCTYPE html>
<html>
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script>
    $(document).ready(function() {
        $("button").click(function(){
            // add some PHP function shit. Nope, can't do that homie            
            $("p").hide();            
        });
    });        
    </script> 
</head>
<body>
<button>click me</button>    
<p> this is not php code</p>
<?php

$txt = "A variable declaration!";
echo "and neither is this <br>";
echo "huh? " . $txt; 

function myTest() {
    static $x =5;
    echo "<p> another test for a funtion call. answer is: $x </p>";
    $x++; 
}    

myTest();    
myTest();  
myTest();   

$cow = "cow";    
return $cow; 
    
?>
<p>but actually, that was</p>
</body>
</html>