<?php
include('header.php');
?>
<link rel = "stylesheet" href="./style/todo.css">
	<div class="wrapper">
	<div class="content">
	<?php
		require_once "scripts/functions.php";
		require_once "scripts/database-handler.php";
		
		if(isset($_SESSION["usersId"]) && isset($_GET["project"]) )
		{
			$currentPage = basename(__FILE__);
			$_SESSION["currentPage"] = $currentPage;
			$inProjectFile = true;
			$code = $_GET["project"];
			$projectCode = getProjectCode($con,$code);
			if( isOwnerProject($con,$_GET["project"],$_SESSION["usersName"]) )
			{
				$userRole = "manager";
			}
			else if( isDevInProject($con,$projectCode,$_SESSION["usersName"]) )
			{
				$code = $_GET["project"];
				$projectCode = getProjectCode($con,$code);
				$userRole = "developer";
			}
			else
			{
				header("location: ./projects.php?error=projectNotJoined");
				exit();
			}
		}
		else
		{
			header("location: ./index.php");
		}
		
		?>
		
		<div class = "rightDivContainingTable">
            <table class="container">
                <?php
                    echo createTableHead(array("Title", "State", "Deadline", "Priority"));
                    echo createTableBody($con,$_GET["project"]); 
                    $urlPath = $_SERVER["REQUEST_URI"];
					
					
                    ?>
                
            </table>
        </div>
            <div class = "leftDivContainingDescription"> 
                <h1> Description </h1>
                <hr>
                <p id = "descriptionText"> </p>
            </div>
        </div>
		
		
		
		
		<?php
		
		if(isset($_GET["error"]))
		{
			createIssueDisplay($currentPage,$code,$projectCode,$_GET["error"],$userRole);
		}
			else
		{
			createIssueDisplay($currentPage,$code,$projectCode,NULL,$userRole);
		}
				
		require_once "projectIssueOptions.php";
			
		?>
	
	
	</div>
	</div>
	</body>
	
</html>

<?php 
    function createTableHead($params){
        $html = "";
        $html .= "<thead><tr>";
        for($i = 0; $i < count($params); $i++){
            $html .= "<th><h1>".$params[$i]."</h1></th>";
        }
        $html .= "</tr></thead>";
        return $html;
    }

    function createTableBody($con,$projectId){
		$projectCode = getProjectCode($con,$_GET["project"]);
		
        $issues = listAllIssuesOfAProject($con, $projectCode); 
        $html = "<tbody>"; 
        while($row = mysqli_fetch_array($issues)){
            $issueId = $row["issueId"]; //<a class='issueButton' href=".$_SESSION["currentPage"]."?project=".$projectId."&selectedIssue=".$issue["issueId"]>
			$issueTitle = $row["issueTitle"];
			$issuePlace = $row["issuePlace"];
            $issuePriority = $row["issuePriority"];
            $issueStatus = $row["issueStatus"];
            $details = $row["issueDetails"];
            $issueDeadline = $row["issueDeadline"];
            $html .= "<tr><td onclick = 'displayDescription(\"$details\")'><a class='issueButton' href=".$_SESSION['currentPage']."?project=".$projectId."&selectedIssue=".$issueId.">".strval($issueTitle)."</a></td><td>". $issueStatus."</td><td>". $issueDeadline."</td><td>".$issuePriority."</td></tr>";
           
        }
        $html .= "</tbody>";
        return $html; 
    }

    ?>
        <script>
            function displayDescription(description){
                document.getElementById("descriptionText").innerHTML = description;
            }
        </script>
    <?php
    require_once "projectIssueOptions.php";
?>