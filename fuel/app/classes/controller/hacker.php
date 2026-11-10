<?php

/**
 * Legacy URL: /hacker/access/<username> now lives at /profile/<username>.
 */
class Controller_Hacker extends Controller {
	public function action_access($username = '') {
		Response::redirect(Uri::create('profile/' . rawurlencode($username)), 'location', 301);
	}
}
