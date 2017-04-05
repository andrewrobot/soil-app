<html>
  <head>
    <title>Server Side Response</title>
  </head>
  
  <body>
    
    <details>
      <summary>Functionality</summary>
      <ul>
        <li>User inputs info</li>
        <li>The form has been declared with a method of POST and an action of calling the php file on the server</li>
        <li>The input is manipulated by the php code on the server and is sent back to the user in a new format</li>
      </ul>
    </details>
    
    <div>
      Hello <?php echo $_POST["name"]; ?>!<br>
      You are a(n) <?php echo $_POST["occ"]; ?> in <?php echo $_POST["city"]; ?>. You are awesome!
    </div>
    
  </body>
</html>
