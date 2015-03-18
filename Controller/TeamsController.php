<?php
/** Loading the HipTeamAppController from the HipTeam plugin */
App::uses('HipTeamAppController', 'HipTeam.Controller');
App::uses('Team', 'HipTeam.Model');

class TeamsController extends HipTeamAppController {

	public $name = 'Teams';

/**
 * Simply displaying the team datas freshly fetched from HipChat API
 * This method should always reads from the cache.
 * @return void
 */
	public function display() {
		try {
			// Checking if there is any cached version already?
			// It should always have one to avoid the browser to make any API requests.
			$cached_version = Cache::read(Team::CACHE_KEY, Team::CACHE_CONFIG);

			// So yeah, if the cache is empty, the browser will call the API itself but it's not the best pratice.
			if(empty($cached_version)) {
				// If the cache is empty, let's call the API again.
				$cached_version = $this->Team->getTheTeam(true);
				// And write the result in the cache
				Cache::write(Team::CACHE_KEY, $cached_version, Team::CACHE_CONFIG);
			}

			// Sort members based on their online presence.
			foreach($cached_version['HipTeam']['members'] as $member) {
				$key = ($member['extra_infos']['presence']['is_online']) ? 'online' : 'offline';
				$team[$key][] = $member;
			}

			$team = Hash::sort($team, '{s}', 'desc');

		} catch(Exception $e) {
			$this->Session->setFlash('Something went wrong: '.$e->getMessage());
		}

		$this->set(compact('team'));
	}

/**
 * This allow non logged-in user to see the team page
 * @return void
 */
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('display');
	}
}
