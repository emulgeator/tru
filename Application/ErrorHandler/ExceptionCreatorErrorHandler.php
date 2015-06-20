<?php

namespace Application\ErrorHandler;


/**
 * This error handler converts the desired errors to ErrorException
 *
 * @package    Application
 * @subpackage ErrorHandler
 */
class ExceptionCreatorErrorHandler {

	/**
	 * The errorLevels to convert to Exception.
	 *
	 * @var int
	 */
	protected $errorLevels;

	/**
	 * Holds the current set error reporting level.
	 *
	 * @var int
	 */
	protected $errorReporting;

	/**
	 * Set to TRUE if we are registered as the system error handler.
	 *
	 * @var bool
	 */
	protected $isRegistered = false;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->errorReporting = error_reporting();

		$this->errorLevels =
			(
				E_ERROR
				| E_WARNING
				| E_PARSE
				| E_NOTICE
				| E_CORE_ERROR
				| E_CORE_WARNING
				| E_COMPILE_ERROR
				| E_COMPILE_WARNING
				| E_USER_ERROR
				| E_USER_WARNING
				| E_USER_NOTICE
				| E_STRICT
				| E_RECOVERABLE_ERROR
				| E_DEPRECATED
				| E_USER_DEPRECATED
			)
			& $this->errorReporting;
	}

	/**
	 * Registers the error handler container as the system error handler.
	 *
	 * @return void
	 */
	public function register() {
		set_error_handler(array($this, 'handleError'));
		$this->isRegistered = true;
	}

	/**
	 * Unregisters as the system error handler container.
	 *
	 * @return void
	 *
	 * @codeCoverageIgnore
	 */
	public function unregister() {
		if ($this->isRegistered) {
			return;
		}

		restore_error_handler();
		$this->isRegistered = false;
	}

	/**
	 * Handles a PHP error
	 *
	 * @param int    $errorLevel   The error code {@uses E_*}
	 * @param string $message      The error message.
	 * @param string $file         The file where the error occurred.
	 * @param int    $line         The line in the file where the error occurred.
	 *
	 * @return void
	 *
	 * @throws \ErrorException
	 */
	public function handleError($errorLevel, $message, $file, $line) {
		// If the error was suppressed by the @ operator
		if (error_reporting() === 0) {
			return;
		}

		if (($this->errorLevels & $errorLevel) === 0) {
			return;
		}

		throw new \ErrorException($message, 0, $errorLevel, $file, $line);
	}
}