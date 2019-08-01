<?php

use Phinx\Seed\AbstractSeed;

class SessionParticipant extends AbstractSeed
{

	/**
	 * @return string[]
	 */
	public function getDependencies(): array
	{
		return [
			'Participant',
			'Session',
		];
	}

	public function run(): void
	{
		$this
			->table('SessionParticipant')
			->insert([
				[
					'ID' => 1,
					'SessionId' => 2,
					'ParticipantId' => 1,
				],
				[
					'ID' => 2,
					'SessionId' => 1,
					'ParticipantId' => 1,
				],
				[
					'ID' => 3,
					'SessionId' => 1,
					'ParticipantId' => 2,
				],
			])
			->save()
		;
	}

}
