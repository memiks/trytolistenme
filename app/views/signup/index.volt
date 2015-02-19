		<div class="row">
    	    <div class="five columns">
                <h4>{{ translate._("signupusingthisform") }}</h4>
    
                    {{ form("signup/register") }}
                    
                        <p>
                            <label for="name">{{ translate._("name") }}</label>
                            {{ text_field("name") }}
                        </p>
                        
                        <p>
                            <label for="fingerprint">{{ translate._("fingerprint") }}</label>
                        </p>
                            {{ text_field("fingerprint") }}
                        <p>
                            {{ submit_button(translate._("signup") , "class" : "button button-primary") }}
                        </p>
                            {{ hidden_field("key") }}
                    </form>
                        <p>
                            <label for="email">{{ translate._("passphrase") }}</label>
                        </p>
                            {{ text_field("passphrase") }}
                        
                    <br/>
                    <button type="button" class="button button-primary" id="generateKeysButton">{{ translate._("generatekeys") }} ?</button>
            </div>
    	    <div class="seven columns">
	{{ translate._("howtosignup") }}
	{{ translate._("howtochatwithfirends") }}
	{{ translate._("whereismykeysandwhatissent") }}
    	    </div>
        </div>
        <br/>
        <button type="button" onclick="checkKeys()">{{ translate._("checkkeys") }} ?</button>
        <pre id="public"></pre>
        <pre id="private"></pre>

    <script type="text/javascript">
			function checkKeys() {
                if(checkLocalStorage()) {
                    $('#public').html($('<code/>').text(localStorage.getItem('publicKey')));
                    $('#key').val(localStorage.getItem('publicKey'));
                    $('#private').html($('<code/>').text(localStorage.getItem('privateKey')));
                    $('#fingerprint').val(localStorage.getItem('fingerprint'));
                    alert(sessionStorage.getItem('passphrase'));
                } else {
                	alert("{{ translate._("nolocalestorage") }}");
                }
			}
        $(document).ready(function() {
            trytolisten.initialise();
            
            $('button#generateKeysButton').on('click',function() {
                trytolisten.generateKeys($('#passphrase').val(),$('#name').val(),function() {
                    $('#public').html($('<code/>').text(trytolisten.keyPair.publicKeyArmored));
                    $('#key').val(trytolisten.keyPair.publicKeyArmored);
                    $('#private').html($('<code/>').text(trytolisten.keyPair.privateKeyArmored));

                    trytolisten.publicKey = openpgp.key.readArmored(trytolisten.keyPair.publicKeyArmored);
                    var fingerprint = trytolisten.publicKey.keys[0].primaryKey.fingerprint;
                    $('#fingerprint').val(fingerprint);

                    sessionStorage.setItem('passphrase',$('#passphrase').val());

                    if(checkLocalStorage()) {
                    	localStorage.setItem('privateKey',trytolisten.keyPair.privateKeyArmored);
                    	localStorage.setItem('publicKey',trytolisten.keyPair.publicKeyArmored);
                    	localStorage.setItem('fingerprint', fingerprint);
                    } else {
	                	alert("{{ translate._("nolocalestorage") }}");
                    }
                    
                    log(fingerprint);
                });
            });
        });
    </script>

