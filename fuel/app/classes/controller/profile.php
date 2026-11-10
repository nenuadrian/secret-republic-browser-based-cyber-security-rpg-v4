<?php
use \Model\Achievements;

/**
 * Public player profiles: /profile/<username>
 */
class Controller_Profile extends Controller {
	/** /profile -> your own profile */
	public function action_index() {
		if (!Hacker::check()) Response::redirect(Uri::base());
		Response::redirect(Uri::create('profile/' . rawurlencode(Hacker::username())));
	}

	public function action_view($username = null) {
		$player = $username !== null ? Hacker::find_by_username($username) : false;
		if (!$player) throw new HttpNotFoundException();

		$player['achievements'] = Achievements::process($player['achievements']);

		return View::forge('profile/profile', array('hacker' => $player));
	}
}
