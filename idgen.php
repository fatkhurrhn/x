<?php
$f = "idnumber.php";
if(!file_exists($f)){
	touch($f);
	$handle =  fopen($f, "w" ) ;
	fwrite($handle,date("y")."00000") ;
	fclose ($handle);
}
$handle = fopen($f, "r");
$idnumber = ( int ) fread ($handle,20) ;
fclose ($handle) ;
$idnumber++ ;
$handle =  fopen($f, "w" );
fwrite($handle,$idnumber);
fclose ($handle);

$iddir = 'cards';
$text = ucwords($_POST['visitornewm']); // Participant Name
$profileimage = 'img/blend.jpg';   // QT Code Image
$font_size = 13;               // Font size is in pixels.
$font_file = 'img/fb.otf';         // path to your font file
$img = 'final.png';            // path to temporary image
$birthday = $_POST['dateinput'];
list($monthofdate, $dayofdate, $yearofdate) = explode(' ', $birthday);
$age = date("Y") - $yearofdate ;

// path & name of the image to save on server
$img2 = $iddir.'/'.strtolower(preg_replace('#[^0-9a-zA-z]#','',$text)).'.png'; 

//creates 'cards' folder if not exists
if(!file_exists($iddir)){
	mkdir($iddir, 0777, true);
}

//generate virtual image from profile image
$virtualprofile = imagecreatefromjpeg($profileimage);

//returns profile image's width and height
list($profilewid, $profilehayt) = getimagesize($profileimage);

//initiate new width and height for profile image
$newprofilewid = 150;
$newprofilehayt = 150;

$destination = imagecreatetruecolor($newprofilewid, $newprofilehayt);
imagecopyresampled($destination, $virtualprofile, 0, 0, 0, 0, $newprofilewid, $newprofilehayt, $profilewid, $profilehayt);
imagejpeg($destination, 'tmp.jpg', 100);

$backgroundimage = imagecreatefrompng('img/id.png');// Load the stamp and the photo to apply the watermark to
$profilestamp = imagecreatefromjpeg('tmp.jpg'); // First we create our stamp image manually from GD

// Set the margins for the stamp and get the height/width of the stamp image
$marge_right = 307;
$marge_bottom = 44;

// Get image Width and Height of Profile Image
$sx = imagesx($profilestamp);
$sy = imagesy($profilestamp);

//coordinates of destination point
$xcoordest = imagesx($backgroundimage) - $sx - $marge_right;
$ycoordest = imagesy($backgroundimage) - $sy - $marge_bottom;

//merges two images into one
imagecopymerge($backgroundimage, $profilestamp, $xcoordest, $ycoordest, 0, 0, $sx, $sy, 100);

// Save the image to file and free memory
imagepng($backgroundimage, 'final.png');
imagedestroy($backgroundimage);

$im = imagecreatefrompng($img); // get the image in php
$textcolor = imagecolorallocate($im, 51, 102, 153); // text color

// Get image Width and Height of Temporary Image
$image_width = imagesx($im);  
$image_height = imagesy($im);

imagettftext($im, 12, 0, 300, 75, $textcolor, $font_file, $idnumber); // Add ID Number to image:
imagettftext($im, 12, 0, 300, 105, $textcolor, $font_file, $text); // Add Name to image:
imagettftext($im, 12, 0, 300, 140, $textcolor, $font_file, $age); // Add Age to image:
imagettftext($im, 12, 0, 300, 178, $textcolor, $font_file, $birthday); // Add Birthday to image:
imagettftext($im, 12, 0, 300, 212, $textcolor, $font_file, date('F j, Y')); // Add Date Issued to image:

imagepng($im, $img2, 9); // save the image on server
imagedestroy($im); // Destroy image in memory to free-up resources:

unlink('final.png');//Remove Temporary Background Image
unlink('tmp.jpg');//Remove Temporary Profile Image
?>