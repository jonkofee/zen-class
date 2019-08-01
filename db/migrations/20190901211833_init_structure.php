<?php

use Phinx\Migration\AbstractMigration;

class InitStructure extends AbstractMigration
{

	public function change(): void
	{
		$this
			->table('Participant', ['id' => 'ID'])
			->addColumn('Email', 'string', ['limit' => 255])
			->addColumn('Name', 'string', ['limit' => 255])
			->addIndex(['Email'])
			->save()
		;

		$this
			->table('News', ['id' => 'ID'])
			->addColumn('ParticipantId', 'integer')->addForeignKey('ParticipantId', 'Participant', 'ID', ['delete' => 'RESTRICT'])
			->addColumn('NewsTitle', 'text')
			->addColumn('NewsMessage', 'text')
			->addColumn('LikesCounter', 'integer')
			->save()
		;

		$this
			->table('Speaker', ['id' => 'ID'])
			->addColumn('Name', 'string', ['limit' => 255])
			->save()
		;

		$this
			->table('Session', ['id' => 'ID'])
			->addColumn('Name', 'string', ['limit' => 255])
			->addColumn('TimeOfEvent', 'timestamp')
			->addColumn('Places', 'integer')
			->save()
		;

		$this
			->table('SessionParticipant', ['id' => 'ID'])
			->addColumn('SessionId', 'integer')->addForeignKey('SessionId', 'Session', 'ID', ['delete' => 'CASCADE'])
			->addColumn('ParticipantId', 'integer')->addForeignKey('ParticipantId', 'Participant', 'ID', ['delete' => 'CASCADE'])
			->addIndex(['SessionId', 'ParticipantId'], ['unique' => true])
			->save()
		;

		$this
			->table('SessionSpeaker', ['id' => 'ID'])
			->addColumn('SessionId', 'integer')->addForeignKey('SessionId', 'Session', 'ID', ['delete' => 'CASCADE'])
			->addColumn('SpeakerId', 'integer')->addForeignKey('SpeakerId', 'Speaker', 'ID', ['delete' => 'CASCADE'])
			->addIndex(['SessionId', 'SpeakerId'], ['unique' => true])
			->save()
		;

		$this
			->table('Tables', ['id' => 'ID'])
			->addColumn('Name', 'string', ['limit' => 255])
			->addColumn('Hide', 'boolean', ['default' => true])
			->addIndex(['Name'])
			->save()
		;
	}

}
