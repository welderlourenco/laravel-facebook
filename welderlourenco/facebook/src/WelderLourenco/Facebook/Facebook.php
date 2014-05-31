<?php namespace WelderLourenco\Facebook;

use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookSDKException;
use Facebook\FacebookRequestException;

use WelderLourenco\Facebook\Exceptions\FacebookException;

use Symfony\Component\HttpFoundation\Session\Session as SymfonySession;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Facebook
{

	/**
	 * @var FacebookSession
	 */
	private $session = null;

	/**
	 * @var SymfonySession
	 */
	private $sysession = null;

	public function __construct()
	{
		$this->startSession();
		$this->setDefaultApp();
	}

	/**
	 * Start the php session with symfony.
	 * 
	 */
	private function startSession()
	{
		$this->sysession = new SymfonySession;
		$this->sysession->start();
	}

	/**
	 * Set a default facebook app.
	 */
	private function setDefaultApp()
	{
		FacebookSession::setDefaultApplication(\Config::get('facebook::app.appId'), \Config::get('facebook::app.appSecret'));
	}

	/**
	 * Get the redirect login helper.
	 * 
	 * @return FacebookRedirectLoginHelper
	 */
	private function getRedirectLoginHelper()
	{
		try
		{
			return new FacebookRedirectLoginHelper(\Config::get('facebook::app.redirectUrl'));
		}
		catch (FacebookSDKException $ex)
		{
			throw new FacebookException($ex->getMessage());
		}
	}

	/**
	 * Get the FacebookSession through an access_token.
	 *
	 * @param  string $accessToken
	 * @return FacebookSession
	 */
	private function getFacebookSession($accessToken)
	{
		$facebookSession = new FacebookSession($accessToken);

		try 
		{
		  $facebookSession->validate();

		  return $facebookSession;
		} 
		catch (FacebookRequestException $ex)
		{
			throw new FacebookException($ex->getMessage());
		}
		catch (\Exception $ex) 
		{
			throw new FacebookException($ex->getMessage());
		}
	}

	/**
	 * Trigger method that can get either a facebook session with access token or a redirect login helper.
	 *
	 * @param  string $accessToken The facebook access token.
	 * @return mixed
	 */
	public function connect($accessToken = null)
	{
		if (is_null($accessToken))
		{
			return $this->getRedirectLoginHelper();
		}
		else
		{
			return $this->getFacebookSession($accessToken);
		}
	}

	/**
	 * Get the redirect postback sent from facebook processed, ready to a facebook session.
	 * 
	 * @return connect($accessToken)
	 */
	public function process()
	{
		try
		{
			$redirectLoginHelper = $this->getRedirectLoginHelper();

			return $this->connect($redirectLoginHelper->getSessionFromRedirect()->getToken());
		}
		catch(FacebookRequestException $ex) 
		{
			throw new FacebookException($ex->getMessage());
		} 
		catch(\Exception $ex) 
		{		 
			throw new FacebookException($ex->getMessage());
		}
	}

	/**
	 * Make a request into facebook api.
	 *
	 * @param  FacebookSession $fbsession
	 * @param  string $method
	 * @param  string $call
	 * @return FacebookRequest
	 */
	public function api(FacebookSession $fbsession, $method, $call)
	{
		try 
		{
		  $facebookResponse = (new FacebookRequest($fbsession, $method, $call))->execute();

		  return $graphObject = $facebookResponse->getGraphObject();
		} 
		catch (FacebookRequestException $ex)
		{
		  throw new FacebookException($ex->getMessage());
		}
		catch (\Exception $ex)
		{
		  throw new FacebookException($ex->getMessage());
		}
	}

	/**
	 * Change the appId, appSecret and redirectUrl before connecting.
	 *
	 * @param  string $appId
	 * @param  string $appSecret
	 * @param  string $redireectUrl
	 * @return this
	 */
	public function change($appId, $appSecret, $redirectUrl = null)
	{
		\Config::set('facebook::app.appId', $appId);
		\Config::set('facebook::app.appSecret', $appSecret);
		if (!is_null($redirectUrl))
		\Config::set('facebook::app.redirectUrl', $redirectUrl);
		return $this;
	}

}