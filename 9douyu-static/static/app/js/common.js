function appLogin(client) {
	if(client == "android") {
		window.jiudouyu.login();
	} else {
		window.location.href='objc:doFunc1';
	}
}

function appRegister(client) {
	if(client == "android") {
		window.jiudouyu.goRegister();
	} else {
		window.location.href='objc:doFunc2';
	}
}


function appShare(client) {
	if(client == "android") {
		window.jiudouyu.goShare();
	} else {
		window.location.href='objc:doFunc3';
	}
}