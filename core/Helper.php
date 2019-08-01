<?php

namespace Core;

class Helper
{

	public static function generateRandomPassword(int $len = 8): string
	{
		$alphabet = 'abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789';
		$pass = [];

		for ($i = 0; $i < $len; $i++) {
			$n = rand(0, strlen($alphabet) - 1);
			$pass[] = $alphabet[$n];
		}
		return implode($pass);
	}

	public static function getPasswordHash(string $password): string
	{
		return password_hash($password, PASSWORD_BCRYPT);
	}

}
