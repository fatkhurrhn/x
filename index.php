<?php
if($_SERVER['REQUEST_METHOD'] == 'POST'){
	include_once("idgen.php");
}

$f = "visit.php";
if(!file_exists($f)){
	touch($f);
	$handle =  fopen($f, "w" ) ;
	fwrite($handle,0) ;
	fclose ($handle);
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>ID Generator in PHP</title>
		<link href="img/favicon.ico" rel="icon" type="image">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css">
		<link rel="stylesheet" href="css/datepicker.css">
		<link rel="stylesheet" href="css/style.css">
		<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script src="js/datepicker.js"></script>
		<script src="js/navbarclock.js"></script>
    </head>
		<body onload="startTime()">
			<nav class="navbar-inverse" role="navigation">
				<a href="" target="_blank">
					<img src="img/niemand.png" class="hederimg">
				</a>
				<div id="clockdate">
					<div class="clockdate-wrapper">
						<div id="clock"></div>
						<div id="date"><?php echo date('l, F j, Y'); ?></div>
					</div>
				</div>
				<div class="pagevisit">
					<div class="visitcount">
						<?php
						$handle = fopen($f, "r");
						$counter = ( int ) fread ($handle,20) ;
						fclose ($handle) ;
						
						if($_SERVER['REQUEST_METHOD'] != 'POST'){
							$counter++ ;
						}
						
						echo "This Page is Visited ".$counter." Times";
						$handle =  fopen($f, "w" ) ;
						fwrite($handle,$counter) ;
						fclose ($handle) ;
						?>
					</div>
				</div>
			</nav>
			<div class="topmost container" style="margin-top:8em;">
				<div class="col-md-6">
					<div class="panel panel-primary">
						<div class="panel-heading">
							<center>
								<strong class="panelinputtitle">Personal Information</strong>
							</center>
						</div>
						<div class="panel-body" style="min-height:220px;">
							<div class="container-fluid">
								<form method = "post" enctype="multipart/form-data" id="uploadForm">
									<div class = "form-group">
										<input type = "text" class = "form-control" placeholder="Your Name" name="visitornewm" value="<?php echo @$_POST['visitornewm']; ?>">
									</div>
									<div class = "form-group">
										<input type="text" readonly name="dateinput" class="datepicker-here form-control" placeholder="Your Birthday" data-language="en" value="<?php echo @$_POST['dateinput'];;?>">
									</div>
									<div class = "form-group">
										<input type = "submit" class = "btn btn-primary btn-block" name="process" value="Generate ID">
									</div>
								</form>
								
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="panel panel-primary">
						<div class="panel-heading">
							<center>
								<strong class="panelinputtitle">Generated ID Card</strong>
							</center>
						</div>
						<div class="panel-body" style="min-height:230px;">
							<center>
								<?php
								if($_SERVER['REQUEST_METHOD'] == 'POST'){
									echo '<img src="'.$img2.'">';
								}else{
									echo "<img src='img/id.png' alt='' class='resultimg'/>";
								}
								?>
								
								<a class="btn btn-primary submitBtn" style="width:210px; margin:5px 0;" href="download.php?file=<?php echo @$text; ?>.png ">Download ID Card</a>
							</center>
						</div>
					</div>
				</div>
			</div>
		</body>	
</html>