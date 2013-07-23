<?php
class Thingiverse() {

	CONST BASE_URL = 'https://api.thingiverse.com/';

	public $access_token;

	protected $client_id;
	protected $client_secret;
	protected $code;
	protected $access_key;

	protected $post_params = FALSE;
	protected $url = FALSE;

	public function __construct()
	{
		require 'thingverse_keys.php';
		$this->client_id = $client_id;
		$this->client_secret = $client_secret;
		$this->code = '8b8f87b414ed84352deb2fb6591f50b5';
		$this->access_key = '4f973581c849ec6e1d6acd8fd4794a3d';
	}

	public function endpoint($param)
	{
		$this->url = self::BASE_URL . $param;
	}

	public function makeURL()
	{
		return 'https://www.thingiverse.com/login/oauth/authorize?client_id=' . $this->client_id . '&edirect_uri=http://localhost/code';
	}

	protected function _send()
	{
		$curl = curl_init();

		curl_setopt($curl, CURLOPT_URL, $this->url);

		if ($this->post_params !== FALSE) 
			curl_setopt($curl, CURLOPT_POSTFIELDS, $post_params);

		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $this->access_key));

		$data = curl_exec($curl);

		curl_close($curl);

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
}