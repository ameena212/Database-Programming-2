
<?php include 'includes/language.php'; ?>
<?php include 'includes/header.php'; ?>

<!-- Welcome message -->
<div class="center-content" style="display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center; margin-top: 20px;">
    <br/>
	<h1><?php echo $translations['welcome_message']; ?></h1>
    <p><?php echo $translations['organize_message']; ?></p>
</div>

<!-- About section -->
<div class="aboutBk">
    <h5 class="aboutTitle"><?php echo $translations['about_title']; ?></h5>
    <p class="aboutText"><?php echo $translations['about_message']; ?></p>
</div>

<?php include 'includes/footer.php'; ?>