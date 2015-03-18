<?php
/**
 * TeamShell
 * Makes HipChat Api calls and generates the cache
 * If the cache already exists, it overrides it.
 */
class TeamShell extends AppShell {

/** @var array load the Team model inside the shell */
	public $uses = array('HipTeam.Team');

/**
 * The main method is just calling the refreshCache one
 * Main is automatically called when we don't provide any argument to the shell command
 * @return void
 */
	public function main() {
		return $this->refreshCache();
	}

/**
 * Make API calls and then create or refresh the Cache.
 *
 * @see HipTeam::getTheTeam() Fetch team datas from HipChat API
 * @return void
 */
	public function refreshCache() {
		$this->out('Fetching data from '. Team::API_URI. '...');

		// Get the whole team. The 'true' param means that we want to fetch extra datas such as profile pictures and statuses, ...
		$team = $this->Team->getTheTeam(true);

		if(!empty($team)) {
			$this->out('Writing in the cache...');
			Cache::write(Team::CACHE_KEY, $team, Team::CACHE_CONFIG);
			$this->out('Done!');
		} else {
			$this->out("Something went wrong when fetching HipChat's API");
		}
	}
}
