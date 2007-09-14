<?php head(); ?>

	<div id="primary">
	<?php section_nav();?>
	</div>
	
	<div id="secondary">
	<h2><?php echo $exhibit->title; ?></h2>
	<h3><?php echo $section->title; ?></h3>
	
	<?php echo flash(); ?>
	<?php render_exhibit_page(); ?>

<div class="exhibit-description">
	<?php echo nls2p($exhibit->description); ?>
</div>

<div id="exhibit-sections">	
	<?php foreach($exhibit->Sections as $section) {
		$uri = exhibit_uri($exhibit, $section);
		echo '<h3><a href="' . $uri . '"' . (is_current($uri) ? ' class="current"' : ''). '>' . $section->title . '</a></h3><p>' . $section->description . '</p>';
	} ?>
</div>

<div id="exhibit-credits">	
	<h3>Credits</h3>
	<p><?php echo h($exhibit->credits); ?></p>
</div>

<?php foot(); ?>