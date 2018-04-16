<form method="post" action="options.php"> 
<?php
  settings_fields( $this->slug );
  do_settings_sections( $this->slug );
?> 
<?php submit_button(); ?>
</form>