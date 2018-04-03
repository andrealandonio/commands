<?php

/**
 * Layout "main" view
 *
 * @var $this yii\web\View
 * @var $content string
 */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>

<body>
    <?php $this->beginBody() ?>

    <!-- s:wrap -->
    <div class="wrap">

        <!-- s:navigation -->
        <?php
        NavBar::begin([
            'brandLabel' => Yii::$app->name,
            'brandUrl' => Yii::$app->homeUrl,
            'options' => [
                'class' => 'navbar-inverse navbar-fixed-top',
            ],
        ]);
        try {
	        $navigation_widget = Nav::widget([
		        'options' => ['class' => 'navbar-nav navbar-right'],
		        'items' => [
			        ['label' => 'About', 'url' => ['/site/about']],
			        ['label' => 'CloudSearch', 'url' => ['/site/cloudsearch']],
			        Yii::$app->user->isGuest ? (
			        ['label' => 'Login', 'url' => ['/site/login']]
			        ) : (
				        '<li>'
				        . Html::beginForm(['/site/logout'], 'post')
				        . Html::submitButton(
					        'Logout (' . Yii::$app->user->identity->username . ')',
					        ['class' => 'btn btn-link logout']
				        )
				        . Html::endForm()
				        . '</li>'
			        )
		        ],
	        ]);

	        echo $navigation_widget;
        }
        catch (\Exception $e) {
            echo $e->getMessage();
        }

        NavBar::end();
        ?>
        <!-- e:navigation -->

        <!-- s:container -->
        <div class="container">
            <?php
            try {
                $breadcrumb_widget = Breadcrumbs::widget([
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                ]);

                echo $breadcrumb_widget;
            }
            catch (\Exception $e) {
                echo $e->getMessage();
            }

            try {
	            $alert_widget = Alert::widget();

	            echo $alert_widget;
            }
            catch (\Exception $e) {
	            echo $e->getMessage();
            }

            echo $content;
            ?>
        </div>
        <!-- e:container -->

    </div>
    <!-- e:wrap -->

    <!-- s:footer -->
    <footer class="footer">
        <div class="container">
            <p class="pull-left">&copy; Andrea Landonio <?= date('Y') ?></p>

            <p class="pull-right"><?= Yii::powered() ?></p>
        </div>
    </footer>
    <!-- e:footer -->

    <?php $this->endBody() ?>

</body>
</html>

<?php $this->endPage() ?>
