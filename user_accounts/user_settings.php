<?php
    require_once '../global.php';
    require_once '../header.php';
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
      <td id="options" height="315px" width="150px" valign="top">
      <table id= >
	<tr height="10px" id="password">
	  <td style="color:#700000;font-family:arial,sans-serif">
	    Change Your Password </p>
	  </td>
	</tr>
	<tr height="10px" id="displayName">
	  <td style="color:#700000;font-family:arial,sans-serif">
	    Change Your Display Name</p>
	  </td>
	</tr>
	<tr height="10px" id="removeAccount">
	  <td style="color:#700000;font-family:arial,sans-serif">
	    Delete Account</p>
	  </td>
	</tr>
      </table>
      </td>
      <td name="forms" style="padding-top:25px;padding-left:50px">
	<iframe frameborder="0" height="250px" width="400px"
	src="change_password_form.php" id="passwordForm"></iframe>
	<iframe frameborder="0" height="250px" width="400px"
	src="change_disp_name.html" id="dispNameForm"> </iframe>
	<iframe frameborder="0" height="250px" width="400px"
	src="remove_account.html" id="rmvAccountForm"> </iframe>
      </td>
      </tr>
      </table>
    </div>
<?php include '../sidebar.php'; ?>
</div>

<?php include '../footer.php'; ?>
