<?php
class Thumbnail {
	public $final_width_of_image;
	public $path_to_image_directory = "";
	public $path_to_thumbs_directory = "";
	public $url_to_thumbs_directory = "";
	public $valid_extensions = "";
	public function __construct($width, $height = null) {
		$this->path_to_image_directory = $_SERVER ['DOCUMENT_ROOT'] . '/images/';
		$this->path_to_thumbs_directory = $_SERVER ['DOCUMENT_ROOT'] . '/images/thumb/';
		$this->url_to_thumbs_directory = 'http://' . $_SERVER ['SERVER_NAME'] . '/images/thumb/';
		$this->valid_extensions = array (
				'jpeg',
				'jpg',
				'png',
				'gif' 
		);
		$this->final_width_of_image = $width;
		$this->final_height_of_image = $height;
	}
	function createThumbnail($filename) {
		if (preg_match ( '/[.](jpg)$/', $filename )) {
			$im = imagecreatefromjpeg ( $path_to_image_directory . $filename );
		} else if (preg_match ( '/[.](gif)$/', $filename )) {
			$im = imagecreatefromgif ( $this->path_to_image_directory . $filename );
		} else if (preg_match ( '/[.](png)$/', $filename )) {
			$im = imagecreatefrompng ( $this->path_to_image_directory . $filename );
		}
		$ox = imagesx ( $im );
		$oy = imagesy ( $im );
		$nx = $this->final_width_of_image;
		$ny = floor ( $oy * ($this->final_width_of_image / $ox) );
		$nm = imagecreatetruecolor ( $nx, $ny );
		imagecopyresized ( $nm, $im, 0, 0, 0, 0, $nx, $ny, $ox, $oy );
		
		if (! file_exists ( $this->path_to_thumbs_directory )) {
			if (! mkdir ( $this->path_to_thumbs_directory )) {
				die ( "There was a problem. Please try again!" );
			}
		}
		imagejpeg ( $nm, $this->path_to_thumbs_directory . $filename );
		$tn = '<img src="' . $this->url_to_thumbs_directory . $filename . '" alt="image" />';
		$tn .= '<br />Congratulations. Your file has been successfully uploaded, and a thumbnail has been created.';
		echo $tn;
	}
}
if (isset ( $_FILES ['image'] )) {
	if ($_FILES ['image']) {
		$file = $_FILES ['image'] ['name'];
		$tmp = $_FILES ['image'] ['tmp_name'];
		$ext = strtolower ( pathinfo ( $file, PATHINFO_EXTENSION ) );
		$thumb = new Thumbnail ( 50 );
		$final_image = rand ( 1000, 1000000 ) . time () . "." . $ext;
		// check's valid format
		if (in_array ( $ext, $thumb->valid_extensions )) {
			$path = $thumb->path_to_image_directory . strtolower ( $final_image );
			if (move_uploaded_file ( $tmp, $path )) {
				$thumb->createThumbnail ( strtolower ( $final_image ) );
			} else {
				echo "Not Uploaded!";
			}
		}
	}
}
?>
<html>
<head>
<title>Image Thumbnail</title>
</head>
<body>
	<form method="post" enctype="multipart/form-data">
		<input type="file" name="image" id="image"> <input type="submit"
			name="submit" id="submit" value="insert">
	</form>
</body>
</html>

