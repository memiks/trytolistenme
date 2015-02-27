var keyring;

debug = false;

function log(message) {
    if(debug) {
        console.log(message);
    }
}

function checkLocalStorage() {
    try {
	    return 'localStorage' in window && window.localStorage !== null;
	} catch (e) {
	    return false;
	}
}

var trytolisten = {
    initialized : false,
	keyring: null,
	generateOptions : {
		numBits: 2048,
		userId: null,
		passphrase: null
	},
	
	loadKeysFromLocaleStorage: function() {
        var privkey = localStorage.getItem('privateKey');
        trytolisten.privatekeys = [];
        if(privkey) {
            key = openpgp.key.readArmored(privkey).keys;
            trytolisten.privatekeys[key[0].users[0].userId.userid]=key;
        }
	  
        var pubkey = localStorage.getItem('publicKey');
        trytolisten.publickeys = [];
        if(pubkey) {
            key = openpgp.key.readArmored(pubkey).keys[0];
            trytolisten.publickeys[key.users[0].userId.userid]=key;
        }
	},
	
	initialise: function() {
		//if (window.crypto.getRandomValues) {
		    //openpgp.config.localeStorage.read();
			keyring = new openpgp.Keyring();
			//TODO openpgp.js needs to improve config support, this is a hack.
			trytolisten.worker = new openpgp.initWorker('/js/openpgp.worker.js');

	        trytolisten.initialized = true;
		//} else {
		//    trytolisten.initialized = false;
		//}
	},

	importKeys: function(keyPair) {
		trytolisten.keyPair = keyPair;
        if(checkLocalStorage()) {
        	localStorage.setItem('privateKey',trytolisten.keyPair.privateKeyArmored);
        	localStorage.setItem('publicKey',trytolisten.keyPair.publicKeyArmored);
        	localStorage.setItem('fingerprint', fingerprint);
        } else {
        	alert('Sorry your browser does not support Locale Storage, please save the private key yourself !');
        }
	},

	importUserKey: function(pubkey,user) {
        if(pubkey) {
            key = openpgp.key.readArmored(pubkey).keys[0];
            trytolisten.publickeys[user]=key;
        }
	},

	generateKeys: function(passphrase, user, callback) {
		if (trytolisten.initialized) {
		    var options = trytolisten.generateOptions;
		    options.passphrase = passphrase;
		    options.userId = user;
		    
			var keyPair = openpgp.generateKeyPair(trytolisten.generateOptions)
            .then(trytolisten.importKeys)
            .then(callback);
		}
	},

	getFingerPrintForUser: function(user) {
	    if(trytolisten.publickeys[user]) {
	        return trytolisten.publickeys[user].primaryKey.fingerprint;
	    } else {
	        return false;
	    }

	},

	encryptMessageForUser: function(user, message, callback) {
        openpgp.encryptMessage(trytolisten.publickeys[user], message).then(callback)
        .catch(function(error) {
	       log(error);
            // failure
        });

	},
	encryptMessage: function(message, callback) {
	    trytolisten.encryptMessageForUser(sessionStorage.getItem('user'), message, callback);

	},
	decryptMessageForUser: function(user, message, passphrase, callback) {
	    var privateKey = trytolisten.privatekeys[user][0];
        privateKey.decrypt(passphrase);
        pgpMessage = openpgp.message.readArmored(message);

		return openpgp.decryptMessage(privateKey, pgpMessage).then(callback);

	},
	decrypt: function(message, callback) {
	    return trytolisten.decryptMessageForUser(sessionStorage.getItem('user'), message, sessionStorage.getItem('passphrase'), callback);
	},
	decryptMessage: function(message, passphrase, callback) {
	    return trytolisten.decryptMessageForUser(sessionStorage.getItem('user'), message, passphrase, callback);
	},

	receptNewUser : function (jSonUser, password) {
		if (jsonUser !== null) {
			var jsonUser = JSON.parse(jSonUser);
			if (jsonUser.user !== null && jsonUser.key !== null) {
				trytolisten.keys.publics[jsonUser.user] = trytolisten.decryptMessage(jsonUser.key, password);
			}
		}
	},

	createJsonMessage : function (user, message) {
		var JObjectMessage = {
			"user": user,
			"message": trytolisten.encryptMessageForUser(user, message),
		};
		return JSON.stringify(JObjectMessage, replacer);
		//return JObjectMessage;
	},
	addToFriendList: function(name,fingerprint) {
        
        if(!trytolisten.friends) {
            trytolisten.friends = {};
        }

        trytolisten.friends[name]=fingerprint;

        return trytolisten.friends;
	},
	updateFriendList: function(newFriends) {
        if(!trytolisten.friends) {
            trytolisten.friends = {};
        }

        for (var key in newFriends) {
            trytolisten.friends[key] = newFriends[key];
        }

        return trytolisten.friends;
	},
	updateHTMLFriendList: function(friends) {
	    $('#friends ul').empty();
	    for(var key in friends) {
	        $('#friends ul').append($('<li/>').data('fingerprint',friends[key]).text(key));
	    }
	},
	retrieveFriendList: function() {
        $.ajax({url:'/friend',
            method: 'post'})
            .done(function(response) {
                response = JSON.parse(response);
                if(response.error) {
                } else if(response.friends) {
                	log(response.friends);
                    trytolisten.decryptMessage(response.friends, sessionStorage.getItem('passphrase'), function(message){
                        var allFriends = JSON.parse(message);
                        var friends = [];
                        if(allFriends) {
                        	friends = trytolisten.updateFriendList(allFriends);
                        	trytolisten.updateHTMLFriendList(friends);
                        }
                    });
                }
            });
	},
	getNewChat: function() {
        var friend = $("#friend").val();
        if(friend) {
	        var fingerprint = trytolisten.getFingerPrintForUser(friend);
	        log('fingerprint='+fingerprint);
	        if(!fingerprint) {
	        	fingerprint = $("#friend_id").val();
	            log('fingerprint='+fingerprint);
	            $.ajax({
	            	type:"POST",
			        url:"/friend/getkey",
					data:{name:friend,
						fingerprint: fingerprint
					},
					success: function(result) {
						key = JSON.parse(result);
						trytolisten.importUserKey(key,friend);
					},
			        async:false
			    });
			    fingerprint = trytolisten.getFingerPrintForUser(friend);
	        }
	        log('fingerprint='+fingerprint);
	        
	        if(fingerprint) {
	            $.ajax({
	                type:"POST",
	                url:"/chat/get",
	                data:{
	                    friend:fingerprint,
	                    min:trytolisten.chatid
	                },
	                success:function(data){
	                	chats =  JSON.parse(data);
	                	if(chats && chats.length > 0) {
	                		ids=[];
		                	for(var chat in chats) {
		                		if(parseInt(chats[chat].id)>trytolisten.chatid) {
		                		    trytolisten.chatid=chats[chat].id;
	                    		    ids.push(chats[chat].id);
		                		}
		                		trytolisten.decrypt(chats[chat].message,trytolisten.appendChat);
		                	}
	                    	if(ids && ids.length > 0) {
	                    		trytolisten.deleteChat(ids,fingerprint);
	                    	}
	                	}
	                    return false;
	                }
	            });
	        }
        }
    	if(trytolisten.chattimeout) {
    		log('new chat cleartimeout');
            clearTimeout(trytolisten.chattimeout);
    	}
		log('new chat settimeout');
    	trytolisten.chattimeout = setTimeout(trytolisten.getNewChat, 1000);
	},
	getCurrentChat: function() {
    	$('#chat').empty();
    	trytolisten.chatid=-1;
        var friend = $("#friend").val();
        var fingerprint = trytolisten.getFingerPrintForUser(friend);
        log('fingerprint='+fingerprint);
        if(!fingerprint) {
        	fingerprint = $("#friend_id").val();
            log('fingerprint='+fingerprint);
            $.ajax({
            	type:"POST",
		        url:"/friend/getkey",
				data:{name:friend,
					fingerprint: fingerprint
				},
				success: function(result) {
					key = JSON.parse(result);
					trytolisten.importUserKey(key,friend);
				},
		        async:false
		    });
		    fingerprint = trytolisten.getFingerPrintForUser(friend);
        }
        log('fingerprint='+fingerprint);
        if(fingerprint) {
            $.ajax({
                type:"POST",
                url:"/chat/get",
                data:{
                    friend:fingerprint
                },
                success:function(data){
                	chats =  JSON.parse(data);
                	if(chats && chats.length > 0) {
                		ids=[];
                    	$('#message').val("");  
                    	for(var chat in chats) {
                    		if(parseInt(chats[chat].id)>trytolisten.chatid) {
                    		    trytolisten.chatid=chats[chat].id;
                    		    ids.push(chats[chat].id);
                    		}
                    		trytolisten.decrypt(chats[chat].message,trytolisten.appendChat);
                    	}
                    	if(ids && ids.length > 0) {
                    		trytolisten.deleteChat(ids,fingerprint);
                    	}
                	} else {
                		trytolisten.chatid=0;
                	}
			    	if(trytolisten.chattimeout) {
			            clearTimeout(trytolisten.chattimeout);
			    	}
			    	trytolisten.chattimeout = setTimeout(trytolisten.getNewChat, 1000);
                    return false;
                }
            });
        }      
	},

	deleteChat: function(ids,fingerprint) {
        log('DELETE BEGIN');
        log('fingerprint='+fingerprint);
        log('ids='+ids);
        if(fingerprint && ids) {
            $.ajax({
                type:"POST",
                url:"/chat/delete",
                data:{
                    friend: fingerprint,
                    ids: ids
                }
            });
        }      
        log('DELETE END');
	},
	
	appendChat: function(message) {
		$('#chat').append($("<li>").text(message));
	},
	
	chat : function() {
        trytolisten.loadKeysFromLocaleStorage();
        trytolisten.retrieveFriendList();
        
        $("#friends ul").delegate("li",'click',function(){
        	$("#friends ul li").removeClass("active");
        	$(this).addClass("active");
        	$('#friend_id').val($(this).data('fingerprint'));
        	$('#friend').val($(this).text());
        	trytolisten.getCurrentChat();
        });
        
        $("#sent").on('click',function() {
            var mess = $("#message").val();
            var messag = mess;
            var friend = $("#friend").val();
            var fingerprint = trytolisten.getFingerPrintForUser(friend);
            if(!fingerprint) {
                $.ajax({
                	type:"POST",
			        url:"/friend/getkey",
					data:{name:friend,
						fingerprint: $("#friend_id").val()
			         	
					},
					success: function(result) {
						key = JSON.parse(result);
						log(key);
						trytolisten.importUserKey(key,friend);
					},
			        async:false
			    });
			    fingerprint = trytolisten.getFingerPrintForUser(friend);
            }
            if(fingerprint) {
                var from = localStorage.getItem('fingerprint');
                
                trytolisten.encryptMessageForUser(friend,mess, function(pgpmessage) {
                    $.ajax({
                        type:"POST",
                        url:"/chat/sent",
                        data:{message:pgpmessage,
                            from:from,
                            friend:fingerprint
                        },
                        success:function(data){
                            return false;
                        }
                    });
                    $('#chat').append($('<li>').text($("#message").val()));
                    $("#message").val('');
                    if(trytolisten.chattimeout) {
                        clearTimeout(trytolisten.chattimeout);
                	}
                	trytolisten.chattimeout = setTimeout(trytolisten.getNewChat, 1000);
                    return false;
                });
            } else {
            	alert('Sorry we are not able to find your friend key!');
            }
        });
        trytolisten.initialise();

        $('.addfriendtoggle').on('click', function(){
            $('#addfrienddiv').toggle('slow');
            $('#friends').toggle();
        });
        
        $('#addfriendok').on('click', function(){
        	var name = $('#name').val();
        	var fingerprint = $('#fingerprint').val();
            $.ajax({
                type:"POST",
                url:"/friend/check",
                data:{name: name,
                	fingerprint: fingerprint
                }
           		,success:function(response){
           			response = JSON.parse(response);
           			if(response && !response.error) {
		                var friendList = trytolisten.addToFriendList(name, fingerprint);
		                var jsonfriend = JSON.stringify(friendList);
		                trytolisten.encryptMessage(jsonfriend, function(pgpmessage) {
		                    $.ajax({
		                        type:"POST",
		                        url:"/friend/add",
		                        data:{friends:pgpmessage}
		                   		,success:function(){
						            trytolisten.retrieveFriendList();
					                $('#addfrienddiv').toggle('slow');
					                $('#friends').toggle();
		                   		}
		                    });
		                    return false;
		                });
           			} else {
           				alert("this name or fingerprint is invalid !");
           			}
           		}
            });
        	
        });
	}
}
