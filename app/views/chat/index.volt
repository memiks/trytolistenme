<!DOCTYPE html>
        {{ form("id" : "chatsent") }}
        <h3>Chat</h3>
			<div class="row">
			    <div class="nine columns"><ul id="chat"></ul></div>
			    <div class="three columns">
			        <div id="friends">
    			        <ul>
    		            </ul>
    		            <button type="button" class="addfriendtoggle">{{ translate._("addafriend") }} ?</button>
		            </div>
		            <div id="addfrienddiv" style="display:none">
                        {{ form("id" : "addfriendform" ) }}
                            <h4>{{ translate._("addafriend") }}</h4>
                			<label for="name">{{ translate._("name") }}</label><br/>
                			{{ text_field("name") }}
                			<label for="fingerprint">{{ translate._("fingerprint") }}</label><br/>
                			{{ text_field("fingerprint") }}
                        </form>
            			<button type="button" class="button button-primary" id="addfriendok">{{ translate._("add") }}</button>
            			<button type="button" class="addfriendtoggle">{{ translate._("cancel") }}</button>
                    </div>

	            </div>
		    </div>
			<label for="name">{{ translate._("message") }}</label><br/>
			{{ text_area("message", 
                "style" : "width:100%",
                "rows" : 6
            ) }}
			<button type="button" class="button button-primary" id="sent">{{ translate._("send") }}</button>
			{{ hidden_field("friend_id") }}
			{{ hidden_field("friend") }}
        </form>
        
    <script type="text/javascript">
        $(document).ready(function() {
        	trytolisten.chat();
        });
    </script>
