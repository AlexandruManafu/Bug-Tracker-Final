<?php
include('header.php');
?>

<div class="wrapper">
	<div class="content">
		<div class="register-form">
			<form action="scripts/signup-script.php" method="post" onsubmit="return checkform(this);">
				<h2> Registration </h2><br>
				<input class="bigger-custom-input" type="text" name="user" placeholder="User-Name"><br>
				<input class="bigger-custom-input" type="text" name="email" placeholder="Email adress"><br>
				<input class="bigger-custom-input" type="password" name="pwd" placeholder="Password"><br>
				<input class="bigger-custom-input" type="password" name="pwdrepeat" placeholder="Repeat Password" oninput="displayCaptcha()" onclick="displayCaptcha()" onchange="displayCaptcha()">
				<br>
				<br>
				<div class="capbox">
					<div id="CaptchaDiv"></div>
					<div class="capbox-inner">
						Type the number:<br>
						<input type="hidden" id="txtCaptcha">
						<input type="text" name="CaptchaInput" id="CaptchaInput" size="15"><br>
					</div>
				</div>
				<div>
					<button class="sign-up_button" type="submit" name="submit">Sign-Up</button>
				</div>
			</form>
		</div>
		<?php
		if (isset($_GET['error'])) {
			$errorList = array();
			$errorList['emptyField'] = "<p class='error'>No fields can be empty.</p>";
			$errorList['invalidUsername'] = "<p class='error'>The username can contain letters and digitis and it must be atleast of length 4.</p>";
			$errorList['usernameExists'] = "<p class='error'>Username already exists.</p>";
			$errorList['invalidEmail'] = "<p class='error'>The email entered is invalid.</p>";
			$errorList['invalidPassword'] = "<p class='error'>The password must be at least 8 symbols and the two passwords fileds must match.</p>";
			$errorList['registerSuccess'] = "<p class='sign-upSuccess'>Account creation was succesfull, you may now log-in</p>";

			if (isset($errorList[$_GET['error']])) {
				echo $errorList[$_GET['error']];
			}
		}
		?>
	</div>

</div>
</div>

<script>
	function displayCaptcha() {
		document.getElementsByClassName("capbox")[0].style.display = "inline-block";
	}

	function checkform(theform) {
		var why = "";
		if (theform.CaptchaInput.value == "") {
			why += "- Please Enter CAPTCHA Code.\n";
		}

		if (theform.CaptchaInput.value != "") {
			if (ValidCaptcha(theform.CaptchaInput.value) == false) {
				why += "- The CAPTCHA Code Does Not Match.\n";
			}
		}

		if (why != "") {
			alert(why);
			return false;
		}
	}

	var a = Math.ceil(Math.random() * 9) + '';
	var b = Math.ceil(Math.random() * 9) + '';
	var c = Math.ceil(Math.random() * 9) + '';
	var d = Math.ceil(Math.random() * 9) + '';
	var e = Math.ceil(Math.random() * 9) + '';

	var code = a + b + c + d + e;
	document.getElementById("txtCaptcha").value = code;
	document.getElementById("CaptchaDiv").innerHTML = code;

	// Validate input against the generated number
	function ValidCaptcha() {
		var str1 = removeSpaces(document.getElementById('txtCaptcha').value);
		var str2 = removeSpaces(document.getElementById('CaptchaInput').value);
		if (str1 == str2) {
			return true;
		} else {
			return false;
		}
	}

	function removeSpaces(string) {
		return string.split(' ').join('');
	}
</script>
</body>

<html>