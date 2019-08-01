<?php

use Phinx\Seed\AbstractSeed;

class SessionSpeaker extends AbstractSeed
{

	/**
	 * @return string[]
	 */
	public function getDependencies(): array
	{
		return [
			'Participant',
			'Speaker',
		];
	}

	public function run(): void
	{
		$this
			->table('SessionSpeaker')
			->insert([
				[
					'ID' => 1,
					'SessionId' => 1,
					'SpeakerId' => 1,
				],
				[
					'ID' => 2,
					'SessionId' => 2,
					'SpeakerId' => 1,
				],
				[
					'ID' => 3,
					'SessionId' => 2,
					'SpeakerId' => 2,
				],
			])
			->save()
		;
	}

}
