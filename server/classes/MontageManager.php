<?php

namespace server\classes;

class MontageManager
{
	public function createMontageFromFile($image_path, $sticker_path, int $width, int $height)
	{
		$gd_image = imagecreatefromstring(file_get_contents($image_path));
		$gd_sticker = imagecreatefrompng('..' . $sticker_path);

		if ($gd_image && $gd_sticker) {
			$imageDimensions = $this->getImageDimensions($gd_image);

			$newImage = imagecreatetruecolor($width, $height);
			imagecopyresampled($newImage, $gd_image, 0, 0, 0, 0, $width, $height, $imageDimensions['width'], $imageDimensions['height']);
			imagecopy($newImage, $gd_sticker, 0, 0, 0, 0, 480, 360);

			imagedestroy($gd_image);
			imagedestroy($gd_sticker);

			$mimetype = $this->getMimeType($image_path);
			$newImageString = $this->getStringFromImage($newImage, $mimetype);

			return $newImageString;
		} else {
			throw new \Exception('cc');
		}
	}

	private function getImageDimensions($gd_image)
	{
		return array(
			"width" => imagesx($gd_image),
			"height" => imagesy($gd_image)
		);
	}

	public function createMontageFromWebcam($image, $sticker)
	{
		$img = str_replace('data:image/png;base64,', '', $image);
		$img = str_replace(' ', '+', $img);
		$img = base64_decode($img);
		$img = imagecreatefromstring($img);
		$sticker = imagecreatefrompng('..' . $sticker);

		if ($img && $sticker) {
			imagecopy($img, $sticker, 0, 0, 0, 0, imagesx($sticker), imagesy($sticker));

			ob_start();
			header('Content-Type: image/png');
			imagepng($img);
			$image_string = ob_get_contents();
			ob_end_clean();

			imagedestroy($img);
			return "data:image/png;base64," . base64_encode($image_string);
		}
		return null;
	}

	private function getStringFromImage($image, $mimetype)
	{
		ob_start();
		switch ($mimetype) {
			case 'image/png':
				header('Content-Type: ' . $mimetype);
				imagepng($image);
				break;
			case 'image/jpeg':
				header('Content-Type: ' . $mimetype);
				imagejpeg($image);
				break;
			case 'image/gif':
				header('Content-Type: ' . $mimetype);
				imagegif($image);
				break;
		}
		$image_string = ob_get_contents();
		ob_end_clean();
		imagedestroy($image);
		return "data:" . "$mimetype" . ";base64," . base64_encode($image_string);
	}

	private function getMimeType($image_path)
	{
		$finfo = new \finfo(FILEINFO_MIME_TYPE);
		$mimetype = $finfo->buffer(file_get_contents($image_path));
		return $mimetype;
	}

}