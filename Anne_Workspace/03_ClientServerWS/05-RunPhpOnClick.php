<html>
  <?php
    function runMyFunction() {
      echo "I just ran a php function";
    }
    
    if (isset($_GET['hello'])) {
      runMyFunction();
    }
  ?>
  
  Hello there!
  <a href="05-RunPhpOnClick.php?hello=true">Run PHP Function</a>
</html>
