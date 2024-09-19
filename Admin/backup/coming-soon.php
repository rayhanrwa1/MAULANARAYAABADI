<!doctype html>
<html lang="en" dir="ltr">

<?php $title='Coming-Soon' ?>

<?php include './partials/head.php' ?>

<body class="geex-dashboard">
	
<?php include './partials/header.php'?>

<main class="geex-main-content">		

<?php include './partials/sidebar.php'?>
	
<?php include './partials/customizer.php'?>

		<div class="geex-content">
			<div class="geex-content__section geex-content__error">
				<div class="geex-content__error__wrapper">
					<div class="geex-content__error__content">
						<h3 class="geex-content__error__subtitle">Coming Soon</h3>     
						<p class="geex-content__error__desc">We will return very soon</p>
						<div id="geex-countdown" class="geex-countdown">
							<ul class="geex-countdown__wrapper">
								<li><span class="geex-countdown__days"></span>days</li>
								<li><span class="geex-countdown__hours"></span>Hours</li>
								<li><span class="geex-countdown__minutes"></span>Minutes</li>
								<li><span class="geex-countdown__seconds"></span>Seconds</li>
							</ul>
						</div>
					</div><!-- .page-content -->
				</div>
			</div>
		</div>
  	</main>

	<!-- inject:js-->
	<?php include './partials/script.php'?>
	<!-- endinject-->
</body>

</html>