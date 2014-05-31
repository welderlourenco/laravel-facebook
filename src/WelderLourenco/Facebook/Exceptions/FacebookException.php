<?php namespace WelderLourenco\Facebook\Exceptions;

class FacebookException extends \Exception
{
	public function __construct($message)
	{
		parent::__construct($message);
	}
}