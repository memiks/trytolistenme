<html>
    <head>
        <meta charset='utf-8'>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="/favicon.ico" />
        <link rel="shortcut icon" href="/favicon.ico" />
         <?php echo $this->assets->outputJs('headerJS'); ?>
         <?php echo $this->assets->outputCss('headerCSS'); ?>
    </head>
    <body>
        <div class="u-full-width header">
        	<a href="/"><h1>Try To Listen. <span style="color:#CC0000">Me</span></h1></a>
        	<b><?php echo $this->translate->_('trytolistenmephrase'); ?></b>
	        <hr/>
        </div>
        <?php echo $this->flash->output(); ?>
        <div class="container">
            <?php echo $this->getContent(); ?>
        </div>

        <div class="u-full-width header">
	        <h4><?php echo $this->translate->_('indiegogolink'); ?><br/></h4>
	        <hr/>
        	<h3><a href="/"><?php echo $this->translate->_('home'); ?></a> - <a href="/login"><?php echo $this->translate->_('login'); ?></a> - <a href="/chat"><?php echo $this->translate->_('chat'); ?></a> - 
        	<a href="/account"><?php echo $this->translate->_('account'); ?></a> - <a href="/signup"><?php echo $this->translate->_('signup'); ?></a></h3>
        </div>

		
		<input type="hidden" name="token" value="<?php echo $this->security->getToken(); ?>">
    </body>
</html><?php function vmacro_error_messages($__p) { if (isset($__p[0])) { $message = $__p[0]; } else { if (isset($__p['message'])) { $message = $__p['message']; } else {  throw new \Phalcon\Mvc\View\Exception("Macro error_messages was called without parameter: message");  } } if (isset($__p[1])) { $field = $__p[1]; } else { if (isset($__p['field'])) { $field = $__p['field']; } else {  throw new \Phalcon\Mvc\View\Exception("Macro error_messages was called without parameter: field");  } } if (isset($__p[2])) { $type = $__p[2]; } else { if (isset($__p['type'])) { $type = $__p['type']; } else {  throw new \Phalcon\Mvc\View\Exception("Macro error_messages was called without parameter: type");  } }  ?>
    <div>
        <span class="error-type"><?php echo $type; ?></span>
        <span class="error-field"><?php echo $field; ?></span>
        <span class="error-message"><?php echo $message; ?></span>
    </div><?php } ?>
<script>
    $(document).ready(function(){
        $('div.success').fadeOut(3000,function(){$(this).remove()});
    });
</script>

