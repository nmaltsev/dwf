<?php

class AlertButton extends AWidget {
	
	const style = '
.btn_alert {
	border: 1px solid red;
}
	';

	const script = '
function handleAlertButtonClick(e){alert("Hello world! " + e.target.getAttribute("wid"));}
	';
	
	public function __construct(){
		parent::__construct();
		$this['wid'] = 'alert_button';
		$this['id']= $this->id();
	}

	public function render(){ ?>
<button wid="<?php $this->puts('id');?>" class="btn_alert" onclick="handleAlertButtonClick(event)">Press me</button>
<p><?php echo $this->get('action');?></p>
<?php
		// echo "<script>", AlertButton::script, "</script>";
		// echo "<style>", AlertButton::style, "</style>";
	}
}

?>