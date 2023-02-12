//addresses 

$('#identical_addresses').click(function(){
	
	$('#form-actual-address').val($('#form-legal-address').val());
	
});


// Profile user-form
if ($("#profile-form").length > 0) {
$("#profile-form").validate({
  rules: {
    first_name: {
    required: true,
    maxlength: 50
  },
    last_name: {
    required: false,
    maxlength: 50
  },
  email: {
    required: true,
    maxlength: 50,
    email: true,
  },
  phone: {
    required: false,
    maxlength: 12
  },  
  description: {
    required: true,
    maxlength: 300
  },   
  },
  messages: {
  first_name: {
    required: "Укажите имя",
    maxlength: "Максимальная длина 50 символов"
  },
   last_name: {
    required: "Укажите фамилию",
    maxlength: "Максимальная длина 50 символов"
  },
    phone: {
    required: "Укажите номер телефона",
    maxlength: "Максимальная длина 12 символов"
  },
  email: {
    required: "Укажите e-mail",
    maxlength: "Максимальная длина 50 символов",
  },
  },
  submitHandler: function(form) {
  $.ajaxSetup({
    headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  $.ajax({
    url: $('#profile-form-url').val(),
    type: "POST",
    data: $('#profile-form').serialize(),
    dataType: "json",
    success: function( response ) {

	  alert(response.message);
 

     // document.getElementById("profile-form").reset(); 
    },
    error: function(response){
	alert(response.responseJSON.message);
	
}
   });
  }
  })
}








// Profile Organiztion user-form
if ($("#profile-organization-form").length > 0) {
$("#profile-organization-form").validate({
  rules: {
    title: {
    required: true,
    maxlength: 50
  },
    type: {
    required: true,
    maxlength: 50
  },
  inn: {
    required: true,
    digits: true,
    maxlength: 12
  },
  kpp: {
    required: true,
     digits: true,
    maxlength: 12
  },  
  ogrn: {
    required: true,
     digits: true,
    maxlength: 15
  },  
    director: {
    required: true,
    maxlength: 200
  },
    director_post: {
    required: true,
    maxlength: 200
  },
    accountant: {
    required: true,
    maxlength: 200
  },
    legal_address: {
    required: true,
    maxlength: 200
  },  
    actual_address: {
    required: true,
    maxlength: 200
  }, 
  },
  messages: {
  title: {
    required: "Укажите название",
    maxlength: "Максимальная длина 50 символов"
  },
    type: {
    required: "Укажите тип",
    maxlength: "Максимальная длина 50 символов"
  },
    inn: {
    required: "Укажите",
    digits: "Допустимы только цифры",
    maxlength: "Максимальная длина 12 символов"
  },  
  kpp: {
    required: "Укажите",
    digits: "Допустимы только цифры",
    maxlength: "Максимальная длина 12 символов"
  },  
  ogrn: {
   required: "Укажите",
   digits: "Допустимы только цифры",
    maxlength: "Максимальная длина 13 символов"
  },  
    director: {
    required: "Укажите",
    maxlength: "Максимальная длина 200 символов"
  },
    director_post: {
    required: "Укажите",
    maxlength: "Максимальная длина 200 символов"
  },
    accountant: {
    required: "Укажите",
    maxlength: "Максимальная длина 200 символов"
  },
    legal_address: {
    required: "Укажите",
    maxlength: "Максимальная длина 200 символов"
  },  
    actual_address: {
   required: "Укажите",
    maxlength: "Максимальная длина 200 символов"
  },
  },
  submitHandler: function(form) {
  $.ajaxSetup({
    headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });
  $('#submit2').html('Please Wait...');
  $("#submit2"). attr("disabled", true);
  $.ajax({
    url: $('#profile-form-organization-url').val(),
    type: "POST",
    data: $('#profile-organization-form').serialize(),
    dataType: "json",
    success: function( response ) {

	  alert(response.message);
     

    
    },
    error: function(response){
	alert(response.responseJSON.message);
	
}
   });
  }
  })
}



// Profile Requisites user-form
if ($("#profile-requisites-form").length > 0) {
$("#profile-requisites-form").validate({
  rules: {
    payment_account: {
    required: true,
    digits: true,
    maxlength: 20
  },
    correspondent_account: {
    required: false,
    digits: true,
    maxlength: 20
  },
  bik: {
    required: true,
    digits: true,
    maxlength: 9,
  },
  bank: {
    required: false,
    maxlength: 300
  },  
   
  },
  messages: {
   payment_account: {
    required: "Укажите счет",
    digits: "Допустимы только цифры",
    maxlength: "Максимальная длина 20 символов"
  },
   correspondent_account: {
    required: "Укажите счет",
    digits: "Допустимы только цифры",
    maxlength: "Максимальная длина 20 символов"
  },
   bik: {
    required: "Укажите БИК",
    digits: "Допустимы только цифры",
    maxlength: "Максимальная длина 12 символов"
  },
  bank: {
    required: "Укажите Банк",
    maxlength: "Максимальная длина 2000 символов",
  },
  },
  submitHandler: function(form) {
  $.ajaxSetup({
    headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });
  $('#submit3').html('Please Wait...');
  $("#submit3"). attr("disabled", true);
  $.ajax({
    url: $('#profile-form-requisites-url').val(),
    type: "POST",
    data: $('#profile-requisites-form').serialize(),
    dataType: "json",
    success: function( response ) {
console.log(response);
	  alert(response.message);
    

     // document.getElementById("profile-form").reset(); 
    },
    error: function(response){
	console.log(response);
	alert(response.responseJSON.message);
	
}
   });
  }
  })
}



// password reset
if ($("#profile-password-form").length > 0) {
$("#profile-password-form").validate({
   rules: {
    password: {
    required: true,
    maxlength: 20
    },
    password1: {
    required: true,
    maxlength: 20
    },
  password2: {
    required: true,
    maxlength: 20,
    },  
   
  },
  messages: {
  password: {
    required: "Укажите пароль",
    maxlength: "Максимальная длина 20 символов",
  },
    password1: {
    required: "Укажите пароль",
    maxlength: "Максимальная длина 20 символов",
  },
  password2: {
    required: "Укажите пароль",
    maxlength: "Максимальная длина 20 символов",
  },
  },
  submitHandler: function(form) {
  $.ajaxSetup({
    headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  $.ajax({
    url: $('#profile-form-password-url').val(),
    type: "POST",
    data: $('#profile-password-form').serialize(),
    dataType: "json",
    success: function( response ) {

	  alert(response.message);
     

      document.getElementById("profile-password-form").reset(); 
    },
    error: function(response){
	alert(response.responseJSON.message);
	
}
   });
  }
  })
}





