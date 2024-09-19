<!doctype html>
<html lang="en" dir="ltr">

<?php $title='Error' ?>

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
						<h2 class="geex-content__error__title">404</h2>
						<h3 class="geex-content__error__subtitle">Page Not Found</h3>     
						<p class="geex-content__error__desc">Sorry, the page you seems looking for, has been moved, redirected or removed permanently.</p>
						<a class="geex-btn" href="index.php"> Back to Homepage</a>
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