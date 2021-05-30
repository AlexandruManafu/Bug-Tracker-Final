<?php
	include('header.php');
?>

<div class="wrapper">
	<div class="content">
    	<?php
			require_once "scripts/functions.php";
			require_once "scripts/database-handler.php";
			if(isset($_SESSION["usersId"]) && isset($_GET["project"]) ){
				$currentPage = basename(__FILE__);
				$_SESSION["currentPage"] = $currentPage;
				$inProjectFile = true;
				$code = $_GET["project"];
				$projectCode = getProjectCode($con,$code);
				if( isOwnerProject($con,$_GET["project"],$_SESSION["usersName"]) ){
					$userRole = "manager";
				}
				else if( isDevInProject($con,$projectCode,$_SESSION["usersName"]) ){
					$code = $_GET["project"];
					$projectCode = getProjectCode($con,$code);
					$userRole = "developer";
				}
				else{
					header("location: ./projects.php?error=projectNotJoined");
					exit();
				}
			}
			else{
				header("location: ./index.php");
			}
		?>
		<div class='minimumHeight'>
			<table class="container">
				<?php
					echo createTableHead(array("Title", "Place", "Deadline"));
					echo createTableBody($con,$_GET["project"]); 
					$urlPath = $_SERVER["REQUEST_URI"];
					?>
			</table>
		
		</div>
		<?php 
		require_once("projectIssueOptions.php");
		?>
	</div>
</div>

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
		$priorities = array();
		$priorities[0]="Abandoned";
		$priorities[1]="Low";
		$priorities[2]="Medium";
		$priorities[3]="High";
		
        $html = "<tbody>"; 
		$issues = listIssuesDeadline($con, $projectCode); 
		$html .= createTablePart($issues,$projectId);
		$issues = listIssuesNoDeadline($con, $projectCode); 
		$html .= createTablePart($issues,$projectId);
		
        $html .= "</tbody>";
        return $html; 
    }
	
	function createTablePart($issues,$projectId)
	{
		$result = "";
		 while($row = mysqli_fetch_array($issues)){
			$issueId = $row["issueId"];
			$issueTitle = $row["issueTitle"];
			$issuePlace = $row["issuePlace"];
            $issuePriority = $row["issuePriority"];
            $issueDeadline = $row["issueDeadline"];
			if($issueDeadline=="0000-00-00")
			{
				$issueDeadline = "None";
			}
			//<a class='issueButton' href=".$_SESSION['currentPage']."?project=".$projectId."&selectedIssue=".$issueId.">".strval($issueTitle)."</a>
			if(!isMobileDev())
				$dots = 10;
			else
				$dots = 15;
            $result .= "<tr><td>".displayIssue($row,$projectId,20,$dots)."</td><td>". $issuePlace."</td><td>". dateDisplay($issueDeadline)."</td><td></tr>";
        }
		
		return $result;
	}

?>