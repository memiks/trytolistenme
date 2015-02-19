<html>
    <head>
        <meta charset='utf-8'>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="/favicon.ico" />
        <link rel="shortcut icon" href="/favicon.ico" />
         {{ assets.outputJs('headerJS') }}
         {{ assets.outputCss('headerCSS') }}
    </head>
    <body>
        <div class="u-full-width header">
        	<a href="/"><h1>Try To Listen. <span style="color:#CC0000">Me</span></h1></a>
        	<b>{{ translate._("trytolistenmephrase") }}</b>
	        <hr/>
        </div>
        {{ flash.output() }}
        <div class="container">
            {{ content() }}
        </div>

        <div class="u-full-width header">
	        <h4>{{ translate._("indiegogolink") }}<br/></h4>
	        <hr/>
        	<h3><a href="/">{{ translate._("home") }}</a> - <a href="/login">{{ translate._("login") }}</a> - <a href="/chat">{{ translate._("chat") }}</a> - 
        	<a href="/account">{{ translate._("account") }}</a> - <a href="/signup">{{ translate._("signup") }}</a></h3>
        	<br><a href="https://github.com/memiks/trytolistenme/"><img src="/static/github-icon.png"> Source Github</a>
        </div>

		{# Inject the 'security' service #}
		<input type="hidden" name="token" value="{{ security.getToken() }}">
    </body>
</html>
{%- macro error_messages(message, field, type) %}
    <div>
        <span class="error-type">{{ type }}</span>
        <span class="error-field">{{ field }}</span>
        <span class="error-message">{{ message }}</span>
    </div>
{%- endmacro %}
<script>
    $(document).ready(function(){
        $('div.success').fadeOut(3000,function(){$(this).remove()});
    });
</script>

