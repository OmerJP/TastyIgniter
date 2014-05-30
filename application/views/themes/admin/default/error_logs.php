<div id="box-content">
	<div id="notification">
		<?php if (validation_errors()) { ?>
			<?php echo validation_errors('<span class="error">', '</span>'); ?>
		<?php } ?>
		<?php if (!empty($alert)) { ?>
			<?php echo $alert; ?>
		<?php } ?>
	</div>

	<div class="box">
	<div id="update-box" class="content">
		<form accept-charset="utf-8" method="post" action="<?php echo current_url(); ?>">
			<textarea name="logs" wrap="off" readonly="readonly" style="width: 99%; height: 300px; padding: 5px; overflow: scroll; background: #FFF; border: 1px solid #E7E7E7; background-position: initial initial; background-repeat: initial initial;">
				<?php echo $logs; ?>
			</textarea>
		</form>
	</div>
	</div>
</div>
