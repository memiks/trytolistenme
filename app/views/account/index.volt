	<div class="center">
    	<div class="center">
			<div class="row">
				<div class="col w10"><label for="name">{{ translate._("name") }}</label></div>
				<div class="col w20">{{ text_field("name") }}</div>
        	</div>
			<div class="row">
				<div class="col w10"><label for="fingerprint">{{ translate._("fingerprint") }}</label></div>
				<div class="col w20">{{ text_field("fingerprint") }}</div>
			</div>
    		<div class="row">
    			<div class="col w10"><label for="name">{{ translate._("passphrase") }}</label></div>
    			<div class="col w20">{{ text_field("passphrase") }}</div>
        	</div>
		</div>
	    <div class="center">
	        <button type="button" class="button button-primary"
	            onclick="verifyPassPhrase()">{{ translate._("verifypassphrase") }} ?</button>
    	</div>
    	<div id="keys" style="display:none;">
	    	<a id="publiclink" download="public.key">{{ translate._("publickey") }}</a>
		    <pre id="public"></pre>
	    	<a id="privatelink" download="private.key">{{ translate._("privatekey") }}</a>
	        <pre id="private"></pre
        </div>
	</div>
<script>
    function verifyPassPhrase() {
        var key = localStorage.getItem('privateKey');
        if(openpgp.key.readArmored(key).keys[0].decrypt($('#passphrase').val())) {
            // Save passphrase to sessionStorage
            alert("{{ translate._("passphraseok") }}");
        } else {
            alert("{{ translate._("passphraseko") }}");
        }
    }
	$(document).ready(function(){
        if(checkLocalStorage()) {
        	if(localStorage.getItem('publicKey')) {
        		$('#keys').show();
	            $('#name').val(sessionStorage.getItem('user'));
	            $('#publiclink').attr('href',"data:text;charset=utf-8,"+encodeURI(localStorage.getItem('publicKey')));
	            $('#public').html($('<code/>').text(localStorage.getItem('publicKey')));
	            $('#privatelink').attr('href',"data:text;charset=utf-8,"+encodeURI(localStorage.getItem('privateKey')));
	            $('#private').html($('<code/>').text(localStorage.getItem('privateKey')));
	            $('#fingerprint').val(localStorage.getItem('fingerprint'));
        	}
        } else {
        	alert("{{ translate._("nolocalestorage") }}");
        }
	});
</script>   
