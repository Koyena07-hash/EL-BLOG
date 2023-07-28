<?php
	include('connect.php');
	$sql = "SELECT title, contents, date_posted FROM posts WHERE id =" . $_GET['id'];
	$result = $conn->query($sql);
	$result2 = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
	<head>

		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="description" content="">
		<meta name="author" content="">
		<title>
			<?php
				if ($result->num_rows > 0)
					while ($row = $result->fetch_assoc())
						echo $row["title"];
			?>
		</title>
		<!-- Custom fonts for this template -->
		<link href='https://fonts.googleapis.com/css?family=Lora:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
		<link href='https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>

		<!-- Custom styles for this template -->
		<link href="css/navbar.css" rel="stylesheet">
		<link href='css/post.css' rel='stylesheet'>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	</head>
	<body>
	<nav class='nav_bar'>
			<a class='nav_bar_link' href="index.php">
				<img class ='nav_bar_image' src="img/logo.png" class="logo" alt="">
			</a>
			<div class ='nav_bar_div'>
				<ul class='nav_bar_ul'>
					<li class='nav_bar_li'>
						<a class='nav_bar_elements' href="index.php">Home</a>
					</li class='nav_bar_li'>
					<li class='nav_bar_li'>
						<a class='nav_bar_elements' href="about.php">About  </a>
					</li>
					<li class='nav_bar_li'>
						<a class='nav_bar_elements' href="contact.php">Contact Us</a>
					</li>
					<!-- <li class='nav_bar_li'>
						<a class='nav_bar_elements' href="admin.php">Admin</a>
					</li> -->
					<?php
						session_start();
						if (isset($_SESSION['user_user'])){
							echo'   <li class="nav_bar_li">
										<a class="nav_bar_elements" href="logout.php">User Logout</a>
									</li>';
						}
						else if(empty($_SESSION['user_user'])){
							echo'	<li class="nav_bar_li">
										<a class="nav_bar_elements" href="u_login.php">User Login</a>
									</li>';
						}
					?>
				</ul>
			</div>
			<div class='welcome'>
				<?php
					$error = '';
					if (isset($_SESSION['user_user'])){
						$name=$_SESSION['user_user'];
						echo "Welcome ".$name;
						}
              	?>
			</div>
		</nav>
		<header class="masthead">
			<div class="first-cont">
				<?php
					include 'lib/Parsedown.php';
					$parsedown = new Parsedown();
					if ($result2->num_rows > 0) {
						while ($row = $result2->fetch_assoc()) {
							$postcontent = $parsedown -> text($row["contents"]);
							echo '	<div class="post-normal">
										<h3 class="post-title">' . $row["title"] . '</h3>
										<h3 class="post-content">' . $postcontent . '</h3>
										<p class="post-meta"> Posted on : ' . date("d.m.Y", strtotime($row["date_posted"])) . '</p>
									</div>';
						}
					}
					echo '<br><br><h1 class="commenttitle" style="color:black">Comments</h1>';
					echo '
						<div class="post-normal center">
							<br>
							<form action="" method="post">
								<textarea type="text" name="postid" class="commentinput"></textarea><br>
								<input type="submit" name="tocomment" class="commentsubmit"/>
							</form>
						';
					$sql3 = "SELECT comnts,date_posted,name FROM comments WHERE pid =" . $_GET['id'];
					$result3 = $conn->query($sql3);
					if ($result3->num_rows > 0) {
						while ($row = $result3->fetch_assoc()) {
							echo '
								<div class="commentcard">
									<h3 class="comment-content">' . $row["name"] . '</h3>
									<h3 class="comment-content">' . $row["comnts"] . '</h3>
									<p class="post-meta"> Posted on : ' . date("d.m.Y", strtotime($row["date_posted"])) . '</p>
								</div>
								';
						}
					}
					echo '<br></div>';
				?>
			<?php
				$error = '';
				if (isset($_POST['tocomment']))
				{	
					if (isset($_SESSION['user_user'])){
						if (!empty($_POST['postid'])) {
							$stmt = $conn->prepare("INSERT INTO comments (`pid`, `comnts`, `date_posted`,`name`) VALUES ( ?, ?, ?, ?)");
							$stmt->bind_param("isss", $u_name, $u_user, $time,$name);
					
							$u_name = $_GET['id'];
							$u_user = $_POST['postid'];
							$time = date("Y/m/d");
							$name=$_SESSION['user_user'];
							$stmt->execute();
							header("location:post.php?id=".$u_name);
							}
							else{
								$error = '<p class="error">Please fill all fields.</p>';
							}
							echo $error;
						}
					else{
							$error = '<p class="error">Please login.</p>';
							echo $error;
							header("location:u_login.php");
						}
				}
				?>
			</div>
		</header>
		<!-- Footer -->
		<footer><br><br>
			<div class="footer" id="footer">
				<div class="top" style="border-top: 4px white solid;">
					<ul>
						<li><a href="https://www.facebook.com/"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
						<li><a href="https://twitter.com/"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
						<li><a href="https://in.linkedin.com//"><i class="fa fa-linkedin" aria-hidden="true"></i></a></li>
						<li><a href="https://www.instagram.com/"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
						<li><a href="mailto:eldevelopers@gmail.com"><i class="fa fa-envelope" aria-hidden="true"></i></a></li>
					</ul>
				</div>
				<div class="bottom">
					<div class="b">
						<h6>2021 © EL, All rights reserved.</h6>
					</div>
					<div class="a">
						<h3>Email</h3>
						<h6>eldevelopers@gmail.com</h6>
					</div>
				</div>
			</div>
    	</footer>
	</body>
</html>