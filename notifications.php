<?php
include('header.php');
?>
	<div class="wrapper">
	<div class="content">
	<?php
		require_once "scripts/functions.php";
		require_once "scripts/database-handler.php";
		
		if(isset($_SESSION["usersId"]))
		{
			$currentPage = basename(__FILE__);
			$_SESSION["currentPage"] = $currentPage;
		}
		else
		{
			header("location: ./index.php");
		}
				//Idea create a form, within the form create the notifications, and also print N buttons eaach button will have as name the notification id
				
		$data = getNotifications($con,$_SESSION["usersName"]);
		

		if($data->num_rows==0)
			echo "<p id='centerText'>No Notifications<p>";	
			
		?>
		<div id = "listBox"> 
				<?php 
	
				if($data->num_rows!=0)
				{
					echo createNotificationList($data);
				
				echo "<br>";
				displayButton("exclusiveToggleWindow('confirm','deleteNotifications','block');", "Delete Notifications");	
				
				displayConfirmationWindow("deleteNotifications","scripts/deleteNotifications-script.php",$currentPage,null,null,null,
				"Are you sure you want to delete all your notifications?","Delete",null,"deleteNotifications");
				}
					
				?>
		</div>
		<?php 

			function createNotificationList($notifications){
				$html = "";
				while($row = mysqli_fetch_array($notifications))
				{
					//<input class = "seenNotification" type = "checkBox">
					$html .= '<p class = "notificationElement">' . $row["notificationContent"] . '</p>';
				}
					
				return $html;
			}
		?>
	
		
			
	
	
	
	</div>
	</div>
	</body>
	
<html>