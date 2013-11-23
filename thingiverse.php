<?php
/**
 * Thingiverse
 *
 * A PHP wrapper for the Thingiverse API.
 *
 * @package  MakerBot
 * @subpackage  Thingiverse
 * @author  Greg Walden <greg.walden@makerbot.com>
 * @link  https://github.com/gswalden/thingiverse
 * @version  0.1
 */
class Thingiverse {

	const BASE_URL = 'https://api.thingiverse.com/';

	public $access_token;
	public $response_data;
	public $response_code;
	public $last_response_error;

	protected $client_id;
	protected $client_secret;
	protected $redirect_uri;

	protected $post_params = NULL;
	protected $url = NULL;

	protected $available_licenses = array('cc', 'cc-sa', 'cc-nd', 'cc-nc-sa', 'cc-nc-nd', 'pd0', 'gpl', 'lgpl', 'bsd');

	public function __construct($token = NULL)
	{
		// Required
		$this->client_id = '';
		$this->client_secret = '';

		// Optional, can also be set in Thingiverse app settings 
		$this->redirect_uri = '';

		// Optional, if you already have your valid token. Otherwise, call oAuth().
		$this->access_token = $token;
	}

	public function makeLoginURL()
	{
		$url = 'https://www.thingiverse.com/login/oauth/authorize?client_id=' . $this->client_id;
		if ( ! empty($this->redirect_uri))
			$url .= '&redirect_uri=' . $this->redirect_uri;
		return $url;
	}

	public function oAuth($code)
	{
		$this->url = 'https://www.thingiverse.com/login/oauth/access_token';
		$this->post_params['client_id']     = $this->client_id; 
		$this->post_params['client_secret'] = $this->client_secret;
		$this->post_params['code']          = $code;
		
		$response = $this->_send('POST', TRUE);
		
		preg_match('/access_token=(\w+)&token_type/', $response, $match);
		$this->access_token = $match[1];
	}

	public function getUser($username = 'me')
	{
		$this->url = self::BASE_URL . 'users/' . $username;

		return $this->_send();
	}

	public function updateUser($username, $bio = NULL, $location = NULL, $default_license = NULL, $full_name = NULL)
	{
		$this->url = self::BASE_URL . 'users/' . $username;

		if ($bio !== NULL)
			$this->post_params['bio'] = $bio;
		if ($location !== NULL)
			$this->post_params['location'] = $location;
		if ($default_license !== NULL && in_array($default_license, $this->available_licenses))
			$this->post_params['default_license'] = $default_license;
		if ($full_name !== NULL)
			$this->post_params['full_name'] = $full_name;

		return $this->_send('PATCH');
	}

	public function getUserThings($username = 'me')
	{
		$this->url = self::BASE_URL . 'users/' . $username . '/things';

		return $this->_send();
	}

	public function getUserLikes($username = 'me')
	{
		$this->url = self::BASE_URL . 'users/' . $username . '/likes';

		return $this->_send();
	}

	public function getUserCopies($username = 'me')
	{
		$this->url = self::BASE_URL . 'users/' . $username . '/copies';

		return $this->_send();
	}

	public function getUserCollections($username = 'me')
	{
		$this->url = self::BASE_URL . 'users/' . $username . '/collections';

		return $this->_send();
	}

	public function getUserDownloads($username = 'me')
	{
		$this->url = self::BASE_URL . 'users/' . $username . '/downloads';

		return $this->_send();
	}

	public function followUser($username)
	{
		$this->url = self::BASE_URL . 'users/' . $username . '/followers';

		return $this->_send('POST');
	}

	public function unfollowUser($username)
	{
		$this->url = self::BASE_URL . 'users/' . $username . '/followers';

		return $this->_send('DELETE');
	}

	public function getThing($id)
	{
		$this->url = self::BASE_URL . 'things/' . $id;

		return $this->_send();
	}

	public function getThingImages($id, $image_id = NULL)
	{
		$this->url = self::BASE_URL . 'things/' . $id . '/images/';
		if ($image_id !== NULL)
			$this->url .= $image_id;

		return $this->_send();
	}

	public function deleteThingImage($id, $image_id)
	{
		$this->url = self::BASE_URL . 'things/' . $id . '/images/' . $image_id;

		return $this->_send('DELETE');
	}

	public function getThingFiles($id, $file_id = NULL)
	{
		$this->url = self::BASE_URL . 'things/' . $id . '/files/';
		if ($file_id !== NULL)
			$this->url .= $file_id;

		return $this->_send();
	}

	public function deleteThingFile($id, $file_id)
	{
		$this->url = self::BASE_URL . 'things/' . $id . '/files/' . $file_id;

		return $this->_send('DELETE');
	}

	public function getThingLikes($id)
	{
		$this->url = self::BASE_URL . 'things/' . $id . '/likes';

		return $this->_send();
	}

	public function getThingAncestors($id)
	{
		$this->url = self::BASE_URL . 'things/' . $id . '/ancestors';

		return $this->_send();
	}	
	
	public function getThingDerivatives($id)
	{
		$this->url = self::BASE_URL . 'things/' . $id . '/derivatives';

		return $this->_send();
	}	

	public function getThingTags($id)
	{
		$this->url = self::BASE_URL . 'things/' . $id . '/tags';

		return $this->_send();
	}

	public function getThingCategory($id)
	{
		$this->url = self::BASE_URL . 'things/' . $id . '/categories';

		return $this->_send();
	}

	public function updateThing($id, $name = NULL, $license = NULL, $category = NULL, $description = NULL, $instructions = NULL, $is_wip = NULL, $tags = NULL)
	{
		$this->url = self::BASE_URL . 'things/' . $id;

		if ($name !== NULL)
			$this->post_params['name'] = $name;
		if ($license !== NULL && in_array($license, $this->available_licenses))
			$this->post_params['license'] = $license;
		if ($category !== NULL)
			$this->post_params['category'] = $category;
		if ($description !== NULL)
			$this->post_params['description'] = $description;
		if ($instructions !== NULL)
			$this->post_params['instructions'] = $instructions;
		if ($is_wip !== NULL && is_bool($is_wip))
			$this->post_params['is_wip'] = $is_wip;
		if ($tags !== NULL && is_array($tags))
			$this->post_params['tags'] = $tags;

		return $this->_send('PATCH');
	}

	public function createThing($name, $license, $category, $description = NULL, $instructions = NULL, $is_wip = NULL, $tags = NULL, $ancestors = NULL)
	{
		$this->url = self::BASE_URL . 'things/';

		$this->post_params['name'] = $name;

		if (in_array($license, $this->available_licenses))
			$this->post_params['license'] = $license;
		else
			return 'Not a valid license.';

		$this->post_params['category'] = $category;
		if ($description !== NULL)
			$this->post_params['description'] = $description;
		if ($instructions !== NULL)
			$this->post_params['instructions'] = $instructions;
		if ($is_wip !== NULL && is_bool($is_wip))
			$this->post_params['is_wip'] = $is_wip;
		if ($tags !== NULL && is_array($tags))
			$this->post_params['tags'] = $tags;
		if ($ancestors !== NULL && is_array($ancestors))
			$this->post_params['ancestors'] = $ancestors;

		return $this->_send('POST');
	}

	public function deleteThing($id)
	{
		$this->url = self::BASE_URL . 'things/' . $id;

		return $this->_send('DELETE');
	}

	public function uploadThingFile($id, $filename)
	{
		$this->url = self::BASE_URL . 'things/' . $id . '/files';

		$this->post_params['filename'] = $filename;

		return $this->_send('POST');
	}

	public function publishThing($id)
	{
		$this->url = self::BASE_URL . 'things/' . $id . '/publish';

		return $this->_send('POST');
	}

	public function getThingCopies($id)
	{
		$this->url = self::BASE_URL . 'things/' . $id . '/copies';

		return $this->_send();
	}

	public function uploadThingCopyImage($id, $filename)
	{
		$this->url = self::BASE_URL . 'things/' . $id . '/copies';

		$this->post_params['filename'] = $filename;

		return $this->_send('POST');
	}

	public function likeThing($id)
	{
		$this->url = self::BASE_URL . 'things/' . $id . '/likes';

		return $this->_send('POST');
	}

	public function deleteLike($id)
	{
		$this->url = self::BASE_URL . 'things/' . $id . '/likes';

		return $this->_send('DELETE');
	}

	public function getFile($id)
	{
		$this->url = self::BASE_URL . 'files/' . $id;

		return $this->_send();
	}

	public function finalizeFile($id)
	{
		$this->url = self::BASE_URL . 'files/' . $id . '/finalize';

		return $this->_send('POST');
	}

	public function getCopies($id = NULL)
	{
		$this->url = self::BASE_URL . 'copies/';

		if ($id !== NULL)
			$this->url .= $id;

		return $this->_send();
	}

	public function getCopyImages($id)
	{
		$this->url = self::BASE_URL . 'copies/' . $id . '/images';

		return $this->_send();
	}

	public function deleteCopy($id)
	{
		$this->url = self::BASE_URL . 'copies/' . $id;

		return $this->_send('DELETE');
	}

	public function getCollections($id = NULL)
	{
		$this->url = self::BASE_URL . 'collections/';

		if ($id !== NULL)
			$this->url .= $id;

		return $this->_send();
	}

	public function getCollectionThings($id)
	{
		$this->url = self::BASE_URL . 'collections/' . $id . '/things';

		return $this->_send();
	}

	public function createCollection($name, $description = NULL)
	{
		$this->url = self::BASE_URL . 'collections/';

		$this->post_params['name'] = $name;
		if ($description !== NULL)
			$this->post_params['description'] = $description;

		return $this->_send('POST');
	}

	public function addCollectionThing($id, $thing_id, $description = NULL)
	{
		$this->url = self::BASE_URL . 'collections/' . $id . '/thing/' . $thing_id;
		if ($description !== NULL)
			$this->post_params['description'] = $description;

		return $this->_send('POST');
	}

	public function deleteCollectionThing($id, $thing_id)
	{
		$this->url = self::BASE_URL . 'collections/' . $id . '/thing/' . $thing_id;

		return $this->_send('DELETE');
	}

	public function updateCollection($id, $name, $description = NULL)
	{
		$this->url = self::BASE_URL . 'collections/' . $id;

		$this->post_params['name'] = $name;
		if ($description !== NULL)
			$this->post_params['description'] = $description;

		return $this->_send('PATCH');
	}

	public function deleteCollection($id)
	{
		$this->url = self::BASE_URL . 'collections/' . $id;

		return $this->_send('DELETE');
	}

	public function getNewest()
	{
		$this->url = self::BASE_URL . 'newest/';

		return $this->_send();
	}

	public function getPopular()
	{
		$this->url = self::BASE_URL . 'popular/';

		return $this->_send();
	}

	public function getFeatured($return_complete = FALSE)
	{
		$this->url = self::BASE_URL . 'featured/';
		$this->url .= ($return_complete) ? '?return=complete' : '';

		return $this->_send();
	}

	public function search($term)
	{
		$this->url = self::BASE_URL . 'search/' . urlencode($term);

		return $this->_send();
	}

	public function getCategories($id = NULL)
	{
		$this->url = self::BASE_URL . 'categories/';

		if ($id !== NULL)
			$this->url .= $id;

		return $this->_send();
	}

	public function getCategoryThings($id)
	{
		$this->url = self::BASE_URL . 'categories/' . $id . '/things';

		return $this->_send();
	}

	public function getTagThings($tag)
	{
		$this->url = self::BASE_URL . 'tags/' . $tag . '/things';

		return $this->_send();
	}

	public function getTags($tag = NULL)
	{
		$this->url = self::BASE_URL . 'tags/';

		if ($tag !== NULL) 
			$this->url .= $tag;

		return $this->_send();
	}

	protected function _reset()
	{
		$this->post_params = NULL;
		$this->url = NULL;
	}

	protected function _send($type = 'GET', $is_oauth = FALSE)
	{
		if (empty($this->access_token) && ! $is_oauth)
			exit('No access token.');
		if (empty($this->url))
			exit('No URL.');

		$curl = curl_init();

		curl_setopt($curl, CURLOPT_URL, $this->url);

		$type = strtoupper($type);
		switch ($type)
		{
			case 'POST'  :
			case 'PATCH' :
			case 'DELETE':
				if ( ! $is_oauth)
					curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($this->post_params));
				else
					curl_setopt($curl, CURLOPT_POSTFIELDS, $this->post_params);
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $type);
			case 'GET':
				break;
			default:
				exit("Invalid request type: '$type'.");		
		}
		
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

		if ( ! $is_oauth)
		{
			curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $this->access_token, 'Content-Type: application/json'));
		}

		curl_setopt($curl, CURLOPT_TIMEOUT, 10);
		curl_setopt($curl, CURLOPT_HEADER, 1);

		$response = curl_exec($curl);
		$this->response_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		
		$header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
		$response_header = substr($response, 0, $header_size);
		$response_body = substr($response, $header_size);

		if ($this->response_code != 200)
		{
			if (preg_match('/x-error: (.+)/i', $response_header, $match))
				$this->last_response_error = $match[1];
			else
				$this->last_response_error = 'No error given in header. Check response body.';
		}
		else
			$this->last_response_error = '';

		// Uncomment next four lines to see/debug full cURL response
		// $curl_info = curl_getinfo($curl);
		// var_dump($curl_info);
		// var_dump($response_header);
		// var_dump($response_body);

		curl_close($curl);

		$this->_reset();

		if ($is_oauth)
			return $response_body;

		$this->response_data = json_decode($response_body);

		return $this->response_code;
	}
}
