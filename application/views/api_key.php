
	<div class="container">  
		<div class="row">	
			<div class="head">                                
				<h2 class="col-md-10">API Key</h2>
				<h2 class="col-md-2">
					<a class="btn btn-lg btn-block btn-default" onclick="saveKey()">Save Key</a>
				</h2>
			</div> 
			<div class="block">
				<div class="data-fluid">
					<form>
						<div class="row-form">
							<div class="col-sm-3"><lable>Android Key:</lable></div>
							<div class="col-sm-6">
								<input class="form-control" type="text" placeholder="Android Key" id="android_key" name="android_key" required value="<?php echo $apiKey['android_key']; ?>" />
							</div>
							<div class="col-sm-3" align="center"><a style="text-decoration: none;cursor: pointer;" onclick="key_generate(1);">Generate New Key</a></div>
						</div>
						<div class="row-form">
							<div class="col-sm-3"><lable>IOS Key:</lable></div>
							<div class="col-sm-6">
								<input class="form-control" type="text" placeholder="IOS key" id="ios_key" name="ios_key" required value="<?php echo $apiKey['iOS_key']; ?>" />
							</div>
							<div class="col-sm-3" align="center"><a style="text-decoration: none;cursor: pointer;" onclick="key_generate(2);">Generate New Key</a></div>
						</div>
					</form>
				</div>
				<div class="data-fluid">
					<div class="row-form">
						<p><b class="er-msg" style="color: red;"></b><b class="sc-msg" style="color: green;"></b></p>
					</div>
				</div>
			</div>
		</div>
	</div>


<style type="text/css">

.head{
	display: flex;
}
.head h2{
	background: black;
	padding: 15px 10px 10px 20px;
	color: white;
	text-transform: uppercase;
}
</style>

<script type='text/javascript' src='<?php echo base_url(); ?>assets/js/jquery.sortable.js'></script>

<!-- <script type='text/javascript' src='<?php //echo base_url(); ?>assets/js/jquery.sortable.min.js'></script> -->

<script>



	function makeid() {

		var text = "";

		var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz01234567898^!~";



		for (var i = 0; i < 16; i++)

			text += possible.charAt(Math.floor(Math.random() * possible.length));



		return text;

	}



	function key_generate(id){

		$(".er-msg").html();

		$(".sc-msg").html();

		if(id == 1){

			$("#android_key").css({"border-color" : ""});

			$("#android_key").val(makeid());

		}else{

			$("#ios_key").css({"border-color" : ""});

			$("#ios_key").val(makeid());

		}

	}



	function saveKey() {

		$(".er-msg").html();
		$(".sc-msg").html();
		$("#android_key").css({"border-color" : ""});
		$("#ios_key").css({"border-color" : ""});
		if(!$("#android_key").val()) {
			$("#android_key").css({"border-color" : "red"});
		}else if(!$("#ios_key").val()){
			$("#ios_key").css({"border-color" : "red"});
		}else{
			var androidKey = $("#android_key").val();
			var iosKey = $("#ios_key").val();
			$.ajax({
				url : "<?php echo base_url(); ?>api_key/saveApi",
				type : "post",
				data : { androidKey : androidKey, iosKey : iosKey },
				success : function(result){
					if(result == "true"){
						$(".sc-msg").html("Api Key Save successfully.");
					}else{
						$(".er-msg").html("Something went wrong. Please try again later.");
					}

				}

			});

		}



		// console.log(idList);



	}

</script>