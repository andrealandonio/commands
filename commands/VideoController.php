<?php
namespace app\commands;

use yii\console\{Controller, ExitCode};
use app\components\behaviors\MessageBehavior;

/**
 * Manages video utilities.
 *
 * @author Andrea Landonio <landonio.andrea@gmail.com>
 * @since 1.0
 */
class VideoController extends Controller
{
	/**
	 * @var int $fps the video frame rate
	 */
	public $fps = 30;

	/**
	 * @var string $size the video frame size
	 */
	public $size = '1600x900';

	/**
	 * Define controller options.
	 *
	 * @param string $actionID
	 *
	 * @return array|string[]
	 */
	public function options($actionID): array
	{
		return ['fps', 'size'];
	}

	/**
	 * Define controller behaviors.
	 *
	 * @return array
	 */
	public function behaviors(): array
	{
		return [
			// Add named behavior "message" with configurations
			'message' => [
				'class' => MessageBehavior::className(),
				'controller' => $this
			]
		];
	}

	/**
	 * Check controller dependencies.
	 *
	 * @return int
	 */
	protected function checkDependencies(): int {
		/**
		 * @var MessageBehavior $message
		 */
		$message = $this->getBehavior('message');

		// Check if ffmpeg is installed
		$check_ffmpeg_dependencies = shell_exec('whereis ffmpeg');
		if (empty($check_ffmpeg_dependencies) || strlen($check_ffmpeg_dependencies) < 9)
		{
			$message->error('Missing ffmpeg, please install it!');
			return ExitCode::SOFTWARE;
		}

		return ExitCode::OK;
	}

	/**
	 * This command perform the index action.
	 *
	 * @param string $action the action to be performed. (values: compress)
	 * @param string $input the input file to be managed.
	 * @param string $output the output file to be generated.
	 *
	 * @return int
	 */
    public function actionIndex(string $action, string $input = '', string $output = ''): int
    {
	    /**
	     * @var MessageBehavior $message
	     */
	    $message = $this->getBehavior('message');

	    // Check if mandatory services/packages are installed
	    if ($exit_code = $this->checkDependencies() !== ExitCode::OK) return $exit_code;

	    switch ($action) {
		    case 'compress': {
		    	if (!empty($input) && !empty($output)) {
				    $message->info('Starting video compression..');
			        echo shell_exec('ffmpeg -i ' . $input . ' -r ' . $this->fps . ' -s ' . $this->size . ' -strict -2 ' . $output);
			    }
			    else {
				    $message->error('Missing input/output fields');
				    return ExitCode::USAGE;
			    }

			    break;
		    }
		    default: {
			    $message->error('Invalid action');
			    return ExitCode::USAGE;
		    }
	    }

	    return ExitCode::OK;
    }
}
