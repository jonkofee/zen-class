<?php

namespace Controller;

use Core\Controller;
use Core\Exception\BadRequest;
use Core\Exception\NotFound;
use Core\UserException;
use Model\Participant;
use Model\Session;
use Model\SessionParticipant;
use Model\Table;

class Index extends Controller
{

	/**
	 * @throws BadRequest
	 *
	 * @return mixed[]
	 */
	public function tableAction(): array
	{
		$tableName = $this->table;
		$id = $this->id;

		if (empty($tableName)) {
			throw new BadRequest('Необходимо указать имя таблицы');
		}

		if (!is_null($id) && !is_numeric($id)) {
			throw new BadRequest('Не верный формат ID. Нужно указать числом');
		}

		$table = Table::findByName($tableName);

		if (empty($table) || $table[0]->isHide()) {
			throw new BadRequest('Такая таблица отсутствует');
		}

		$tableClassName = "\Model\\$tableName";

		return $id ? $tableClassName::find($id) : $tableClassName::all();
	}

	/**
	 * @throws BadRequest
	 * @throws NotFound
	 * @throws UserException
	 */
	public function sessionSubscribeAction(): void
	{
		$sessionId = $this->sessionId;
		$userEmail = $this->userEmail;

		if (empty($sessionId)) {
			throw new BadRequest('Необходимо указать номер лекции');
		}
		if (empty($userEmail)) {
			throw new BadRequest('Необходимо указать электронную почту пользователя');
		}

		if (!filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
			throw new BadRequest('Не верный формат электронной почты');
		}
		if (!is_numeric($sessionId)) {
			throw new BadRequest('Не верный формат ID лекции. Нужно указать числом');
		}

		$session = Session::find($sessionId);
		if (empty($session)) {
			throw new NotFound('Такоя лекция отсутствует');
		}
		/** @var Session $session */
		$session = $session[0];

		if (!$session->isAvaiblePlace()) {
			throw new UserException('Извините, все места заняты');
		}

		$participant = Participant::findByEmail($userEmail);
		if (empty($participant)) {
			throw new NotFound('Такой пользователь не существует');
		}
		/** @var Participant $participant */
		$participant = $participant[0];

		if ($session->isRegistedParticipant($participant)) {
			throw new UserException('Пользователь уже зарегистрирован на эту лекцию');
		}

		$register = new SessionParticipant([
			'SessionId' => $session->getId(),
			'ParticipantId' => $participant->getId(),
		]);
		$register->save();

		$this->setMessage('Спасибо, вы успешно записаны!');
	}

}
