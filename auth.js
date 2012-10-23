function openWindow(url, title, width, height) {
	var left = (screen.width - width) / 2;
	var top = (screen.height - height) / 2;
	return window.open(url, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+width+', height='+height+', top='+top+', left='+left);
}

function unlink(profile) {
	$.ajax({
		url: '/Special:SocialLogin',
		data: {action: 'unlink', profile: profile},
		success: function(response) {
			if (/.*yes.*/.test(response)) $('#' + profile.replace('@', '_').replace('.', '_')).remove();
			else alert('Не удалось отсоединить профиль социальной сети.');
		}
	});
}

function gup(url, name) {
	name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
	var regexS = "[\\#&]"+name+"=([^&#]*)";
	var regex = new RegExp( regexS );
	var results = regex.exec( url );
	return (results == null)?"":results[1];
}

function tryLogin(data, cb) {
	var formText = '<form action="" method="post"><input type="hidden" name="action" value="login" />';
	$.each(data, function(key, value) { 
		formText += '<input type="hidden" name="' + key + '" value="' + value + '" />';
	});
	formText += '</form>';
	var form = $(formText);
	$('body').append(form);
	$(form).submit();
}

function hacking() {
	alert('Что-то не так! Возможно вы не тот, за кого себя выдаёте, ай-ай-ай!');
}

function login(url, cb) {
	var win = openWindow(url, "sl", 620, 370);
	var pollTimer = window.setInterval(function() { 
		try {
			if (win.document.URL.indexOf(window.document.location.host) >= 0) {
				window.clearInterval(pollTimer);
				var url = win.document.URL;
				var code = gup(url, 'code');
				win.close();
				cb(code);
			}
		} catch(e) {
		}
	}, 300);
}
