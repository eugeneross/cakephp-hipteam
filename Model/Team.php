<?php
App::uses('HipTeamAppModel', 'HipTeam.Model');

/** Loading the HttpSocket to make API calls */
App::uses('HttpSocket', 'Network/Http');

class Team extends HipTeamAppModel {

	public $name = 'Team';

	const API_URI = 'api.hipchat.com';
	const API_URI_VERSION = '/v2';
	const API_PROTOCOL = 'https';

	const CACHE_KEY = 'bb603fa50aa54e69b5a766cea45330c2';
	const CACHE_CONFIG = 'hipteam_team';

/**
 * Get team datas from HipChat API
 *
 * @param  boolean $include_extra_datas Should we include extra datas from users ? (profile pic, statuses, ...). This requires an extra request.
 * @throws Exception If an APi key hasn't been provided.
 * @throws Exception If something went wrong with the API response.
 * @link   https://www.hipchat.com/docs/apiv2/method/get_all_users See for more informations about HipChat API.
 * @return array Returns team datas freshly fetched from HipChat.
 *               Also includes headers in case of you want to check how many requests have been made in the past 5 minutes.
 */
	public function getTheTeam($include_extra_datas = false) {
		$api_key = Configure::read('HipTeam.API_KEY');
		if(empty($api_key)) {
			throw new Exception('You need to provide an API key to fetch data from HipChat');
		}

		// Init the HttpSocket class
		$socket = new HttpSocket();
		$request = $socket->request(array(
			'uri' => array(
				'scheme' => self::API_PROTOCOL,
				'host' => self::API_URI,
				'path' => self::API_URI_VERSION.'/user'
			),
			'header' => array(
				'Authorization' => 'Bearer '.$api_key
			)
		));

		// Checking if there are any errors from the API request.
		if(!$request->isOK()) {
			throw new Exception('Something went wrong: '. $request->body);
		}

		$data['HipTeam']['headers'] = $request->headers;
		$data['HipTeam']['members'] = json_decode($request->body, true)['items'];

		// If we actually want to fetch those extra infos like profile pictures
		if($include_extra_datas) {
			foreach($data['HipTeam']['members'] as $key => $member) {
				$new_member = json_decode($this->getExtraInformation($member['id']), true);
				$default_profile_picture = Configure::read('HipTeam.DEFAULT_PROFILE_PICTURE');

				// Checking if the profile picture is the HipChat's default one. If it is, replace it by the one we've set before.
				if(strpos($new_member['photo_url'], 'silhouette_125.png') !== false && !empty($default_profile_picture)) {
					$new_member['photo_url'] = $default_profile_picture;
				}
				$data['HipTeam']['members'][$key]['extra_infos'] = $new_member;
			}
		}

		return $data;
	}

/**
 * Get any extra informations from a given user id
 * @param  int $id A HipChat user id
 * @throws Exception If the id is empty
 * @throws Exception If something went wrong with the API answer
 * @return array Returns extra informations for a given user such as status and profile picture.
 */
	private function getExtraInformation($id = null) {
		if(empty($id)) {
			throw new Exception('You need to provide an id to be able to make API calls');
		}

		$socket = new HttpSocket();
		$request = $socket->request(array(
			'uri' => array(
				'scheme' => self::API_PROTOCOL,
				'host' => self::API_URI,
				'path' => self::API_URI_VERSION. '/user/'.$id
			),
			'header' => array(
				'Authorization' => 'Bearer '.Configure::read('HipTeam.API_KEY')
			)
		));

		if(!$request->isOK()) {
			throw new Exception('Something went wrong: '. $request->body());
		}

		return $request->body;
	}
}
