<?php

namespace Model;

use Core\Model;
use DateTime;

class Session extends Model
{

	/**
	 * @var string
	 */
	protected static $tableName = 'Session';

	/**
	 * @ColumnName ID
	 *
	 * @var int
	 */
	protected $id;

	/**
	 * @ColumnName Name
	 *
	 * @var string
	 */
	protected $name;

	/**
	 * @ColumnName TimeOfEvent
	 *
	 * @var DateTime
	 */
	protected $timeOfEvent;

	/**
	 * @ColumnName Description
	 *
	 * @var string
	 */
	protected $description;

	/**
	 * @ColumnName Places
	 *
	 * @var int
	 */
	protected $places;

	/**
	 * @return Speaker[]
	 */
	public function getSpeakers(): array
	{
		$speakerTableName = Speaker::$tableName;

		$result = $this->db->query("
			SELECT $speakerTableName.*
			FROM `SessionSpeaker`
			LEFT JOIN $speakerTableName ON `$speakerTableName`.`ID` = `SessionSpeaker`.`SpeakerId` 
			WHERE `SessionId` = {$this->id}
		");

		$data = [];
		foreach ($result as $item) {
			$data[] = new Speaker($item);
		}

		return $data;
	}

	/**
	 * @return Participant[]
	 */
	public function getParticipant(): array
	{
		$participantTableName = Participant::$tableName;

		$result = $this->db->query("
			SELECT $participantTableName.*
			FROM `SessionParticipant`
			LEFT JOIN $participantTableName ON `$participantTableName`.`ID` = `SessionParticipant`.`ParticipantId` 
			WHERE `SessionId` = {$this->getId()}
		");

		$data = [];
		foreach ($result as $item) {
			$data[] = new Speaker($item);
		}

		return $data;
	}

	public function isAvaiblePlace(): bool
	{
		if ($this->places === 0) {
			return false;
		}

		return count($this->getParticipant()) < $this->places;
	}

	public function isRegistedParticipant(Participant $participant): bool
	{
		$result = $this->db->query("
			SELECT *
			FROM `SessionParticipant`
			WHERE 
				`SessionId` = {$this->getId()}
				AND `ParticipantId` = {$participant->getId()}
		");

		return !empty($result);
	}

	/**
	 * @return string[]
	 */
	public function jsonSerialize(): array
	{
		return [
			'ID' => $this->id,
			'Name' => $this->name,
			'TimeOfEvent' => $this->timeOfEvent->format('Y-m-d H:i:s'),
			'Description' => $this->description,
			'Speakers' => $this->getSpeakers(),
		];
	}

}
