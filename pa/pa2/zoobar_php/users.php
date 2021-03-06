<?php 
  require_once("includes/common.php"); 

  global $php_self;
  global $secret_token;

  nav_start_outer("Users", $secret_token);
  nav_start_inner();

  /* UNTRUSTED DATA SANITIZATION */
  $selecteduser = sanatize_username ($_GET['user']); // only allow innocuous characters
  /* END UNTRUSTED DATA SANITIZATION */ 
?>
 <form name="profileform" method="GET" action="<?php echo $php_self ?>">
 <nobr>User:
 <input type="text" name="user" value="<?php echo $selecteduser; ?>" size="10">
 <input type="submit" value="View"></nobr>
</form>
<div id="profileheader"><!-- user data appears here --></div>
<?php 
  $sql = "SELECT Profile, Username, Zoobars FROM Person " . 
         "WHERE Username='$selecteduser'";
  $rs = $db->executeQuery($sql);
  if ( $rs->next() ) { // Sanitize and display profile
    list($profile, $username, $zoobars) = $rs->getCurrentValues();
    
    /* UNTRUSTED DATA SANITIZATION */
    $zoobars = (int)$zoobars;
    $username = sanatize_username ($username);
    $profile = prepare_profile_for_output($profile);
    /* END UNTRUSTED DATA SANITIZATION */

    echo "<div class='profilecontainer'><b>Profile</b>";
    echo "<p id='profile'>$profile</p></div>";
  } else if($selecteduser) {  // user parameter present but user not found
    echo '<p class="warning" id="baduser">Cannot find that user.</p>';
  }
  $zoobars = ($zoobars > 0) ? $zoobars : 0;
  echo "<span id='zoobars' class='$zoobars'/>";	
?><script type="text/javascript">
  var total = parseInt(document.getElementById('zoobars').className);
  function showZoobars(zoobars) {
    document.getElementById("profileheader").innerHTML =
      "<?php echo $selecteduser ?>'s zoobars:" + zoobars;
    if (zoobars < total) {
      setTimeout("showZoobars(" + (zoobars + 1) + ")", 100);
    }
  }
  if (total > 0) showZoobars(0);  // count up to total
</script>
<?php 
  nav_end_inner();
  nav_end_outer(); 
?>
