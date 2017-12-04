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
			return $newImage;
		}
		else {
			throw new \Exception('cc');
		}
	}

	public function createMontageFromString($image_path, $sticker_path, int $width, int $height)
	{

	}

	public function getStringFromImage($image, $mimetype)
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

	private function getImageDimensions($gd_image)
	{
		return array(
			"width" => imagesx($gd_image),
			"height" => imagesy($gd_image)
		);
	}
}