	<div class="center">
		<div class="row">
    	    <div class="eleven columns" style="text-align:center;">
                <iframe width="560" height="315" src="https://www.youtube.com/embed/JV_r6d44Tnk?rel=0" frameborder="0" allowfullscreen></iframe>
            </div>
    	    <div class="one columns">
                <iframe src="https://www.indiegogo.com/project/trytolisten-me-web-site-privacy/embedded/9880246" width="222px" height="445px" frameborder="0" scrolling="no"></iframe>
            </div>
        </div>
		<div class="row">
    	    <div class="five columns">
        	    <?php echo $this->tag->form(array('login')); ?>
                	<div class="center">
            			<div class="row">
            				<div class="col w10"><label for="name"><?php echo $this->translate->_('name'); ?></label></div>
            				<div class="col w20"><?php echo $this->tag->textField(array('name')); ?></div>
            				<div class="col"></div>
                    	</div>
            			<div class="row">
            				<div class="col w10"><label for="fingerprint"><?php echo $this->translate->_('fingerprint'); ?></label></div>
            				<div class="col w20"><?php echo $this->tag->textField(array('fingerprint')); ?></div>
            				<div class="col"></div>
            			</div>
        			</div>
            	    <div class="center">
            	        <button type="button" class="button button-primary"
            	            onclick="verifyPassPhrase()"><?php echo $this->translate->_('login'); ?></button>
                	</div>
                </form>
        		<div class="row">
        			<div class="col w10"><label for="name"><?php echo $this->translate->_('passphrase'); ?></label></div>
        			<div class="col w20"><?php echo $this->tag->textField(array('passphrase')); ?></div>
        			<div class="col"></div>
            	</div>
            </div>
    	    <div class="seven columns">
    	    	<?php echo $this->translate->_('helplogin'); ?>
    	        <h4><?php echo $this->translate->_('ifnot'); ?> <?php echo $this->tag->linkTo(array('signup', $this->translate->_('signuphere'))); ?></h4>
    	    </div>
        </div>
    </div>
<script>
    function verifyPassPhrase() {
        var key = localStorage.getItem('privateKey');
        if(openpgp.key.readArmored(key).keys[0].decrypt($('#passphrase').val())) {
            // Save passphrase to sessionStorage
            sessionStorage.setItem('passphrase', $('#passphrase').val());
            sessionStorage.setItem('user', $('#name').val());
            $('form').submit();
        } else {
            alert("<?php echo $this->translate->_('badpassphrase'); ?>");
        }
    }
	$(document).ready(function(){
        if(checkLocalStorage()) {
            $('#fingerprint').val(localStorage.getItem('fingerprint'));
            $('#passphrase').val(localStorage.getItem('passphrase'));
        } else {
        	alert("<?php echo $this->translate->_('nolocalestorage'); ?>");
        }
	});
</script>   
