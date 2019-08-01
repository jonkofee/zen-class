<?php

use Phinx\Seed\AbstractSeed;

class News extends AbstractSeed
{

	/**
	 * @return string[]
	 */
	public function getDependencies(): array
	{
		return [
			'Participant',
		];
	}

	public function run(): void
	{
		$this
			->table('News')
			->insert([
				[
					'ID' => 1,
					'ParticipantId' => 1,
					'NewsTitle' => 'New agenda!',
					'NewsMessage' => 'Please visit our site!',
					'LikesCounter' => 0,
				],
				[
					'ID' => 2,
					'ParticipantId' => 1,
					'NewsTitle' => 'TITLE',
					'NewsMessage' => 'Message',
					'LikesCounter' => 1,
				],
			])
			->save()
		;
	}

}
