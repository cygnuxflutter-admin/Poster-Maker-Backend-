$(function(){

	$("#nameErMsg").hide();
	$("#updescErMsg").hide();
	$("#upimgErMsg").hide();
	$("#upcoinErMsg").hide();
	$("#updiamondErMsg").hide();

	 
	
	$("#up_model_desc").on('blur' , function(){
		models_desc_validation();
	});

	$("#up_model_name").on('keyup blur', function(){
		models_name_validation()
	});

        // Only Alphabet ----Validation
        $("#up_model_name").keydown(function(e)
        {
        	var key = e.keyCode;
        	if (!((key == 9) || (key == 8) || (key == 32) || (key == 46) || (key >= 35 && key <= 40) || (key >= 65 && key <= 90))) {e.preventDefault();}

        }) ;

        $("#up_model_coin").keydown(function(e)
        {
        	var key = e.keyCode;
        	if (key != 46 && key > 31 && (key < 48 || key > 57) && (key < 96 || key > 105 && key != 110) ){e.preventDefault();}
        });
        $("#up_model_diamond").keydown(function(e)
        {
        	var key = e.keyCode;
        	if (key != 46 && key > 31 && (key < 48 || key > 57) && (key < 96 || key > 105 && key != 110) ){e.preventDefault();}
        });
        //--------------------------------------------


        // No one can Copy Cut And Pase anything -- Validation
        $('#up_model_name,#up_model_coin,#up_model_diamond').bind("cut copy paste",function(e) {
        	e.preventDefault();
        });
        //--------------------------------------------

        function models_name_validation()
        {
        	var nameLength = $("#up_model_name").val().length;

        	if($("#up_model_name").val() =='')
        	{
        		upnameError = true;
        		$("#upnameErMsg").html("Model Name is required !");
        		$('#upnameErMsg').show(200);
        	}
        	else if(nameLength < 3 || nameLength > 25)
        	{
        		nameError = true;
        		$("#upnameErMsg").html("Length must at least more than 3 character long");
        		$('#upnameErMsg').show(200);
        	}
        	else{
        		nameError=false;
        		$("#upnameErMsg").hide(200);
        	}

        }

        function models_desc_validation()
        {
        	var desc= $("#up_model_desc").val();
        	desclen = desc.length;
        	if(desc =='')
        	{
        		descError= true;
        		$("#updescErMsg").html("Description is required");
        		$('#updescErMsg').show(200);
        	}
        	else if(desclen < 10){
        		descError= true;
        		$("#updescErMsg").html("Minimum 10 Character Description need ! ");
        		$('#updescErMsg').show(200);
        	}
        	else{
        		descError= false;
        		$('#updescErMsg').hide(200);
        	}
        }
        function models_cd_validation(){
        	var coins = $("#up_model_coin").val();
        	var diam = $("#up_model_diamond").val();

        	if(coins ==''){
        		coinError= true;
        		$("#upcoinErMsg").html("Coin is required");
        		$('#upcoinErMsg').show(200);
        	}
        	else{
        		coinError=false;
        		$('#upcoinErMsg').hide(200);
        	} 
        	if(diam ==''){
        		diamondError= true;
        		$("#updiamondErMsg").html("Diamonds is required");
        		$('#updiamondErMsg').show(200);
        	}
        	else{
        		diamondError= false;
        		$('#updiamondErMsg').hide(200);
        	}
        }




    });