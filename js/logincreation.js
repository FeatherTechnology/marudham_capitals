// Document is ready
$(document).ready(function () {

	let err_msg = $('#err_msg').val();
	// if(err_msg != ''){
	// 	Swal.fire({
	//         title: 'Invalid Login Credentials!',
	//         icon: 'error',
	// 		timer: 2000,
	// 		timerProgressBar: true,
	// 		didOpen: () => {
	// 			Swal.showLoading();
	// 			const timer = Swal.getPopup().querySelector("b");
	// 			timerInterval = setInterval(() => {
	// 			timer.textContent = `${Swal.getTimerLeft()}`;
	// 			}, 100);
	// 		},
	// 		willClose: () => {
	// 			clearInterval(timerInterval);
	// 		}
	//     });
	// }

	// Validate username
	$('#usernamecheck').hide();
	let usernameError = true;
	$('#lusername').keyup(function () {
		validateusername();
	});

	function validateusername() {
		let usernameValue = $('#lusername').val();
		if (usernameValue.length == '') {
			$('#lusername').css('border', '1px solid red')
			// $('#usernamecheck').show();
			usernameError = false;
			return false;
		}
		else {
			$('#lusername').css('border', '')
			// $('#usernamecheck').hide();
			usernameError = true;
		}
	}


	// Validate password
	$('#passwordcheck').hide();
	let passwordError = true;
	$('#lpassword').change(function () {
		validatepassword();
	});

	function validatepassword() {
		let passwordValue = $('#lpassword').val();
		if (passwordValue.length == '') {
			$('#lpassword').css('border', '1px solid red')
			// $('#passwordcheck').show();
			passwordError = false;
			return false;
		}
		else {
			$('#lpassword').css('border', '')
			// $('#passwordcheck').hide();
			passwordError = true;
		}
	}

	// Submit Button Onclick
	$('#lbutton').click(function () {
		validateusername();
		validatepassword();

		if (usernameError == true && passwordError == true) {
			return true;
		}
		else {
			return false;
		}
	});


});

