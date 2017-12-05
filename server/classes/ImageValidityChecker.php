<?php

namespace server\classes;

class ImageValidityChecker
{
	private $sizeMaxOfFile;

	public function __construct(int $size)
	{
		$this->sizeMaxOfFile = $size;
	}

	public function checkFileErrors($file)
	{
		if (isset($file)) {
			return $file['error'];
		}
		return null;
	}

	public function fileSizeIsValid($file)
	{
		if (isset($file)) {
			if ($file['size'] > $this->sizeMaxOfFile) {
				return false;
			}
			return true;
		}
		return null;
	}

	public function imageTypeIsValid($file)
	{
		if (isset($file)) {
			$finfo = new \finfo(FILEINFO_MIME_TYPE);
			$mimetype = $finfo->buffer(file_get_contents($file['tmp_name']));
			return in_array($mimetype, array('image/jpeg', 'image/png', 'image/gif'));
		}
		return null;
	}
}
