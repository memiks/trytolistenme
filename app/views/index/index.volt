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
        	    {{ form("login") }}
                	<div class="center">
            			<div class="row">
            				<div class="col w10"><label for="name">{{ translate._("name") }}</label></div>
            				<div class="col w20">{{ text_field("name") }}</div>
            				<div class="col"></div>
                    	</div>
            			<div class="row">
            				<div class="col w10"><label for="fingerprint">{{ translate._("fingerprint") }}</label></div>
            				<div class="col w20">{{ text_field("fingerprint") }}</div>
            				<div class="col"></div>
            			</div>
        			</div>
            	    <div class="center">
            	        <button type="button" class="button button-primary"
            	            onclick="verifyPassPhrase()">{{ translate._("login") }}</button>
                	</div>
                </form>
        		<div class="row">
        			<div class="col w10"><label for="name">{{ translate._("passphrase") }}</label></div>
        			<div class="col w20">{{ text_field("passphrase") }}</div>
        			<div class="col"></div>
            	</div>
            </div>
    	    <div class="seven columns">
    	    	{{ translate._("helplogin") }}
    	        <h4>{{ translate._("ifnot") }} {{ link_to('signup', translate._("signuphere")) }}</h4>
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
            alert("{{ translate._("badpassphrase") }}");
        }
    }
	$(document).ready(function(){
        if(checkLocalStorage()) {
            $('#fingerprint').val(localStorage.getItem('fingerprint'));
            $('#passphrase').val(localStorage.getItem('passphrase'));
        } else {
        	alert("{{ translate._("nolocalestorage") }}");
        }
	});
</script>   
