<?php

namespace ErpNET\Saas\v1\Controllers\API;

use Illuminate\Routing\Controller;
use ErpNET\Saas\v1\Contracts\Repositories\UserRepository;

class UserController extends Controller
{
	/**
	 * The user repository instance.
	 *
	 * @var UserRepository
	 */
	protected $users;

	/**
	 * Create a new controller instance.
	 *
	 * @param  UserRepository  $users
	 * @return void
	 */
	public function __construct(UserRepository $users)
	{
		$this->users = $users;

		$this->middleware('auth');
	}

	/**
	 * Get the current user of the application.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getCurrentUser()
	{
		return $this->users->getCurrentUser();
	}
}
