<?php
	//Script to populate left hand bar with content.
	//Will either use pre-set links for Admin panel, or gather latest stories for story pages
	function leftbarItem($text, $href,$desc){
		return "<a class='sidebarlink' href='$href'><div class='sidebar'><h3>$text</h3><p>$desc</p></div></a>";
	}
?>
	<div id="leftbar">
			
		<?php
			if(!isset($boolAdmin)){
				echo "Page syntax is invalid!";
				die();
			}elseif($boolAdmin == false){
				//Universal items:
				echo leftbarItem("Bottlec[r]ap Home", "/index.php", "Bottlec[r]ap home");
				echo "<a class='sidebarlink' href='http://bottlecap-press.myshopify.com/products/bottlecap'><div class='sidebar'><h2>Buy a copy, why don't ya!</h2></div></a>";
				
				if(!isset($issueID)){
					//Code to display issues
					//Code will assume user is on index.php
					$hellaQuery = "SELECT A1.*, 
							(SELECT A2.story_ID 
						    FROM `bcrp_stories` A2
						    WHERE A2.issue_ID = A1.issue_ID
						    ORDER BY story_ID ASC
						    LIMIT 1) story_ID
						FROM `bcrp_issues` A1
						WHERE issue_Post_Date <= CURRENT_DATE();";
					$sidebarLinks = $mysqli->query($hellaQuery);
					while($row = $sidebarLinks->fetch_array(MYSQLI_ASSOC)){
						echo leftbarItem($row["issue_Name"], "/story.php?storyID=" . $row["story_ID"], "Published " . date("M d, Y", strtotime($row["issue_Post_Date"])));
					}
				}else{
					$hellaQuery = "SELECT A1.story_Name, A1.story_ID, A3.auth_Name
						FROM `bcrp_stories` A1, `bcrp_issues` A2, `bcrp_authors` A3
						WHERE A2.issue_ID = ? AND A1.issue_ID = A2.issue_ID AND A1.auth_ID = A3.auth_ID AND A2.issue_Post_Date <= CURRENT_DATE()	
						ORDER BY A1.story_ID ASC";
					//$sidebarLinks = $mysqli->query($hellaQuery);
					$stmt = $mysqli->stmt_init();
					$stmt->prepare($hellaQuery);
					$stmt->bind_param('s', $issueID);
					$stmt->execute();
					$sidebarLinks = $stmt->get_result();
					$stmt->close();
					while($row = $sidebarLinks->fetch_array(MYSQLI_ASSOC)){
						echo leftbarItem($row["story_Name"], "/story.php?storyID=" . $row["story_ID"], "By " . $row["auth_Name"]);
					}
					
					//Finish this.
				}
				
				?>
				
				<div class='sidebar'>
					<h3><a class="sidebarlink" style="color: rgba(58, 87, 149, 1)" href='https://www.facebook.com/bottlecapzine'>Like us on Facebook!</a></h3>
					<h3><a class="sidebarlink" style="color: rgba(85, 172, 238, 1)" href='http://www.twitter.com/bottlecappress'>Follow us on Twitter!</a></h3>
					<h3><a class="sidebarlink" style="color: rgba(50, 80, 109, 1)" href='http://www.bottlecappress.tumblr.com'>Do whatever it is you do on Tumblr!</a></h3>
				</div>
				<div class="padding">
					<script type="text/javascript">
						var sc_project=10181143; 
						var sc_invisible=1; 
						var sc_security="aebcfc87"; 
						var scJsHost = (("https:" == document.location.protocol) ?
						"https://secure." : "http://www.");
						document.write("<sc"+"ript type='text/javascript' src='" +
						scJsHost+
						"statcounter.com/counter/counter.js'></"+"script>");
					</script>
					<noscript><div class="statcounter"><a title="shopify
						analytics" href="http://statcounter.com/shopify/"
						target="_blank"><img class="statcounter"
						src="http://c.statcounter.com/10181143/0/aebcfc87/1/"
						alt="shopify analytics"></a></div></noscript>
				</div>
				<?php
				
			}else{
				if(isset($previewSite) && $previewSite == true){
					//normal leftbar items for main site without restrictions
					
					//Universal items:
					echo leftbarItem("Bottlec[r]ap Home", "/admin/unfetter/index.php", "Bottlec[r]ap (admin) home");
					echo leftbarItem("Back to panel", "/admin/index.php", "Go back to admin panel");

					if(!isset($issueID)){
						//Code to display issues
						//Code will assume user is on index.php
						$hellaQuery = "SELECT A1.*, 
								(SELECT A2.story_ID 
							    FROM `bcrp_stories` A2
							    WHERE A2.issue_ID = A1.issue_ID
							    ORDER BY story_ID ASC
							    LIMIT 1) story_ID
							FROM `bcrp_issues` A1;";
						$sidebarLinks = $mysqli->query($hellaQuery);
						while($row = $sidebarLinks->fetch_array(MYSQLI_ASSOC)){
							echo leftbarItem($row["issue_Name"], "/admin/unfetter/story.php?storyID=" . $row["story_ID"], "Published " . date("M d, Y", strtotime($row["issue_Post_Date"])));
						}
					}else{
						$hellaQuery = "SELECT A1.story_Name, A1.story_ID, A3.auth_Name
							FROM `bcrp_stories` A1, `bcrp_issues` A2, `bcrp_authors` A3
							WHERE A2.issue_ID = ? AND A1.issue_ID = A2.issue_ID AND A1.auth_ID = A3.auth_ID
							ORDER BY A1.story_ID ASC";
						//$sidebarLinks = $mysqli->query($hellaQuery);
						$stmt = $mysqli->stmt_init();
						$stmt->prepare($hellaQuery);
						$stmt->bind_param('s', $issueID);
						$stmt->execute();
						$sidebarLinks = $stmt->get_result();
						$stmt->close();
						while($row = $sidebarLinks->fetch_array(MYSQLI_ASSOC)){
							echo leftbarItem($row["story_Name"], "/admin/unfetter/story.php?storyID=" . $row["story_ID"], "By " . $row["auth_Name"]);
						}
						
						//Finish this.
					}
				
					?>
				
					<div class='sidebar'>
						<h3><a class="sidebarlink" style="color: rgba(58, 87, 149, 1)" href='https://www.facebook.com/bottlecapzine'>Like us on Facebook!</a></h3>
						<h3><a class="sidebarlink" style="color: rgba(85, 172, 238, 1)" href='http://www.twitter.com/bottlecappress'>Follow us on Twitter!</a></h3>
						<h3><a class="sidebarlink" style="color: rgba(50, 80, 109, 1)" href='http://www.bottlecappress.tumblr.com'>Do whatever it is you do on Tumblr!</a></h3>
					</div>
					<div class="padding"></div>
					<?php
					
				}else{
					$self = htmlspecialchars($_SERVER["PHP_SELF"]);
					if(isset($loggedIn) && $loggedIn==true){
						?><div class="sidebar"><h3 id="loggedIn">Logged in as <?php echo $_SESSION["user"]; ?>.</h3>
						<form id="logout" method="post" action="<?php echo $self; ?>">
							<input type="hidden" name="logout" value="true" />
							<input id="logoutbutton" type="submit" name="submit" value="Logout" />
						</form></div><?php
						echo leftbarItem("Home", "/admin/index.php","Return to main admin page");
						echo leftbarItem("Account Management","/admin/account.php","Change account email and/or password");
						echo leftbarItem("Register new user","/admin/register.php","Register new admin");
						echo leftbarItem("New Issue","/admin/newissue.php","Create a new issue");
						echo leftbarItem("New Author","/admin/newauthor.php","Create a new author");
						echo leftbarItem("New Story","/admin/newstory.php","Create a new story");
						echo leftbarItem("Manage Issues","/admin/manageissue.php","Manage existing issues");
						echo leftbarItem("Manage Authors","/admin/manageauthor.php","Manage existing authors");
						echo leftbarItem("Manage Stories","/admin/managestory.php","Manage existing storys");
						echo leftbarItem("Manage homepage","/admin/managehome.php", "Edit home page content");
						echo leftbarItem("Preview Site","/admin/unfetter/index.php", "View site with admin privileges");
						echo "<div class='padding'></div>";
					}else{
						//Create giant red bar where sidebar would be
						?>
						<div id="notlogged"><span>NOT LOGGED IN<span></div>
						<?php
						//add divs behind not logged in bar to make it look cool
						echo "<div id='notloggedbackground'>";
						?><div class="sidebar"><h3 id="loggedIn">Not logged in currently.</h3>
						<form id="logout" method="post" action="#">
							<input id="logoutbutton" type="submit" name="submit" value="Logout" />
						</form></div><?php
						echo leftbarItem("Home", "","Return to main admin page");
						echo leftbarItem("Account Management","","Change account email and/or password");
						echo leftbarItem("Register new user","","Register new admin");
						echo leftbarItem("New Issue","","Create a new issue");
						echo leftbarItem("New Author","","Create a new author");
						echo leftbarItem("New Story","","Create a new story");
						echo leftbarItem("Manage Issues","","Manage existing issues");
						echo leftbarItem("Manage Authors","","Manage existing authors");
						echo leftbarItem("Manage Stories","","Manage existing storys");
						echo leftbarItem("Manage homepage","", "Edit home page content");
						echo leftbarItem("Preview Site", "", "View site with admin privileges");
						echo "<div class='padding'></div>";
						echo "</div>";
					}
				}
			}
		?>
					
	</div>
