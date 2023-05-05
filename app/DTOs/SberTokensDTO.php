<?php

namespace App\DTOs;

class SberTokensDTO
{
	public function __construct(
		public readonly string $accessToken,
	 	public readonly string $refreshToken
	){}
}
