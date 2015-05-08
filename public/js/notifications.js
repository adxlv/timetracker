$( document ).ready(function(){
console.group('Notifications::');
	function callAlert() {
		var n = new Notification('Vai stundas jau atzīmēji?', {
			body: 'Laiks jau sākt domāt par došanos mājās, bet vai savas nostrādātās stundas jau atzīmēji?',
			icon: 'http://time.brandbox.digital/favicon.png',

		});
		console.log('Notifikācija::Vai stundas jau atzīmēji?');
		prepareNotificationCall()
	}

	function prepareNotificationCall() {
		var now = moment()
		var when = moment().hour(17).minute(30).second(0)

		var fomnow = 0 - now.diff(when)
		if (fomnow>500) {
			console.log('Notifikācija būs pēc',fomnow/1000, 'sekundēm');
			setTimeout(callAlert, fomnow);
		} else {
			console.log('Nav jau jēga vairs notifikāciju sūtīt. Sūtam rīt');
			when.add(1,'d')
			fomnow = 0 - now.diff(when)

			setTimeout(callAlert, fomnow);
		}
	}

	//Test If notifications exist 
	if (typeof window.Notification === 'undefined') {
		console.log('Notifications Does not exist');
		exit;
	}

	//Check if notifications are permited by user
	if (window.Notification.permission == 'default') {
		window.Notification.requestPermission()
	}

	prepareNotificationCall()

	// setTimeout(callAlert, fomnow);

console.groupEnd();
})