<?php
class Thingiverse() {

	CONST BASE_URL = 'https://api.thingiverse.com/';

	public $access_token = NULL;

	protected $client_id;
	protected $client_secret;
	protected $code;
	protected $redirect_uri;

	protected $post_params = FALSE;
	protected $url = FALSE;

	protected $available_licenses = ['cc', 'cc-sa', 'cc-nd', 'cc-nc-sa', 'cc-nc-nd', 'pd0', 'gpl', 'lgpl', 'bsd'];

	public function __construct()
	{
		require 'thingverse_keys.php';
		$this->client_id = $client_id;
		$this->client_secret = $client_secret;
		$this->code = '8b8f87b414ed84352deb2fb6591f50b5';
		$this->access_token = '4f973581c849ec6e1d6acd8fd4794a3d';
		$this->redirect_uri = 'http://localhost/code';
	}

	/*public function endpoint($param)
	{
		$this->url = self::BASE_URL . $param;
		$this->_send();
	}*/

	public function makeURL()
	{
		return 'https://www.thingiverse.com/login/oauth/authorize?client_id=' . $this->client_id . '&edirect_uri=' . $this->redirect_uri;
	}

	public function getUser($username = 'me')
	{
		$this->url = self::BASE_URL . 'users/' . $username;

		return $this->_send();
	}

	public function updateThing($username = 'me', $bio = NULL, $location = NULL, $default_license = NULL)
	{
		$this->url = self::BASE_URL . 'users/' . $username;

		if ($bio !== NULL)
			$this->post_params['bio'] = $bio;
		if ($location !== NULL)
			$this->post_params['location'] = $location;
		if ($default_license !== NULL && in_array($default_license, $this->available_licenses))
			$this->post_params['default_license'] = $default_license;

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

	public function createThing($id, $name, $license, $category, $description = NULL, $instructions = NULL, $is_wip = NULL, $tags = NULL, $ancestors = NULL)
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

	public function deleteThing($id)
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

	public function getFeatured()
	{
		$this->url = self::BASE_URL . 'featured/';

		return $this->_send();
	}

	public function search($term)
	{
		$this->url = self::BASE_URL . 'search/' . $term;

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

	protected function _send($type = 'GET')
	{
		$curl = curl_init();

		curl_setopt($curl, CURLOPT_URL, $this->url);

		if ($type == 'POST')
		{
			if ($this->post_params !== FALSE) 
				curl_setopt($curl, CURLOPT_POSTFIELDS, $post_params);
			curl_setopt($curl, CURLOPT_POST, TRUE)	
		}
		elseif ($type == 'PATCH' OR $type == 'DELETE')
		{
			curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $type);
		}
		
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

		if ( ! empty($this->access_token))
			curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $this->access_token));

		$data = curl_exec($curl);

		curl_close($curl);

		$this->_reset();

		return json_decode($data);
	}

	protected function _oauth()
	{
		$this->url = 'https://www.thingiverse.com/login/oauth/access_token';
		$this->post_params['client_id']     = $this->client_id; 
		$this->post_params['client_secret'] = $this->client_secret;
		$this->post_params['code']          = $this->code;
		$this->_send();
	}

	protected function _reset()
	{
		$this->post_params = FALSE;
		$this->url = FALSE;
	}
}