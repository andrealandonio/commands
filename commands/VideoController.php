<?php
namespace app\commands;

use yii\console\Controller;
use yii\console\ExitCode;
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
	public function options($actionID)
	{
		return ['fps', 'size'];
	}

	/**
	 * Define controller behaviors.
	 *
	 * @return array
	 */
	public function behaviors()
	{
		return [
			'message' => [
				'class' => MessageBehavior::className(),
				'controller' => $this
			]
		];
	}

	/**
	 * This command perform what you have entered as action.
	 * Valid actions are:
	 * - compress
	 *
	 * @param string $action the action to be performed.
	 * @param string $input the input file to be managed.
	 * @param string $output the output file to be generated.ss
	 *
	 * @return int
	 */
    public function actionIndex(string $action, string $input = '', string $output = '')
    {
	    /**
	     * @var MessageBehavior $message
	     */
	    $message = $this->getBehavior('message');

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
