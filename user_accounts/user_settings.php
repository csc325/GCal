<?php
    include '../global.php';
    include '../header.php';

if ($_GET['sent'] == 't') 
  echo "<h1 class = 'head'>Your password has been successfully changed \n</h1>";
elseif ($_GET['sent'] == 'f') 
  echo "<h1 class = 'head'> Failed to change password</h1>";
?>


<script>
function formSize() {
  (this).style.height="500px";
}
$(document).ready(function() {
    $("#dispNameForm").hide();
    $("#passwordForm").hide();
    $("#rmvAccountForm").hide();
});
$(document).ready(function() {
  $("#password").click(function () {
      $("#dispNameForm").hide();
      $("#passwordForm").show();
      $("#rmvAccountForm").hide()
  });
});
$(document).ready(function() {
  $("#displayName").click(function () {
      $("#passwordForm").hide();
      $("#dispNameForm").show()
      $("#rmvAccountForm").hide()
  });
});
$(document).ready(function()  {
  $("#removeAccount").click(function () {
      $("#passwordForm").hide();
      $("#dispNameForm").hide();
      $("#rmvAccountForm").show()
  });
});
</script>
<style type="css/text">
#accountSettings {
  width:600px;
  height:500px;
  border:"0px";
}
</style>

<div class="body">
    <div class="col large">

      <h1 class="head">User settings</h1>
      <table id="accountSettings">
      <tr>
	<td id="options" height="315px" width="150px" >
        <table id= >
	  <tr height="10px" id="password" OnMouseOver="this.style.cursor='pointer'">
	    <td style="color: firebrick;font-family:arial,sans-serif">
	      <p><b>Change Your Password</b></p>
	    </td>
	  </tr>

          <!-- 
          I don't know if people should be able to do this...
	  <tr height="10px" id="displayName" OnMouseOver="this.style.cursor='pointer'">
	    <td style="color: firebrick;font-family:arial,sans-serif">
	      <p><b>Change Your Display Name</b></p>
	    </td>
	  </tr>
          -->

	  <tr height="10px" id="removeAccount" OnMouseOver="this.style.cursor='pointer'">
	    <td style="color: firebrick;font-family:arial,sans-serif">
	      <p><b>Delete Account</b></p>
	    </td>
	  </tr>
	</table>
	</td>

	<td name="forms" style="padding-top:25px;padding-left:50px">
	  <div id="passwordForm">
	  <?php include 'change_password_form.php';?>
	  </div>
	  
          <!-- 
          Display name is tied to email; probably shouldn't be able to change
          <div id="dispNameForm">
	  <?php include 'change_disp_name.php';?>
	  </div>
          -->

	  <div id="rmvAccountForm">
	  <?php include 'remove_account.html';?>
	  </div>
	  
	  <!--
`	  <iframe frameborder="0" height="250px" width="300px"
	  src="change_password_form.php" id="passwordForm"></iframe>
	  <iframe frameborder="0" height="250px" width="300px"
	  src="change_disp_name.html" id="dispNameForm"> </iframe>
	  <iframe frameborder="0" height="250px" width="300px"
	  src="remove_account.html" id="rmvAccountForm"> </iframe>
	  -->
	</td>
      </tr>
      </table>
    </div>
<?php include '../sidebar.php'; ?>
</div>
<?php include '../footer.php'; ?>
