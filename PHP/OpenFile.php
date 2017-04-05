<html>
<title>Open File - PHP</title>
    <head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script>
    $(document).ready(function() {
        $("button").click(function(){
            // add some PHP function shit. Nope, can't do that homie
            $("#info").text("something");
            $.get("testfile.txt", function(data){
                 $("#data").text(data);
                 
            });
        });
    });
        </script>    
    </head>    
    <body>

    <?php
        function runMyFunk() {
            echo "Funk all day";
        }

        if (isset($_GET['hello'])) {
            runMyFunk();
        }
    ?>
        
    <a href='OpenFile.php?hello=true'>Run that php!</a> 
        
    <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
        Name: <input type="text" name="name"><br>
        Fav farm creature: <input type="text" name="creature"><br>
        <input type="submit">
    </form>
        
    <button type="button">get text</button>
    <div id="info"></div> 
    <div id="data"></div>    
        
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_REQUEST['name'];
    $creature = $_REQUEST['creature'];
    $string = "Hello " . $name . ", it looks like your fav farm creature is a " . $creature . "\n";
    echo $string;   
        
        
        
    $current = file_get_contents("testfile.txt");
    $current .= $string . "\n";     
    file_put_contents("testfile.txt", $string, FILE_APPEND);    
    $myfile = fopen("testfile.txt", "w") or die("Unable to open this file");
    fwrite($myfile, $string);
    fclose($myfile);    
    }
    ?>
        
    </body>
</html>
