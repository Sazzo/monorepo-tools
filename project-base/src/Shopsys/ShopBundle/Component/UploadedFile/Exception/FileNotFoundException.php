<?php

namespace Shopsys\ShopBundle\Component\UploadedFile\Exception;

use Exception;
use Shopsys\ShopBundle\Component\UploadedFile\Exception\FileException;

class FileNotFoundException extends Exception implements FileException {

	/**
	 * @param string $message
	 * @param \Exception|null $previous
	 */
	public function __construct($message = '', Exception $previous = null) {
		parent::__construct($message, 0, $previous);
	}

}