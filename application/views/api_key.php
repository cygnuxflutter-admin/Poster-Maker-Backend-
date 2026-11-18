<div class="container">
	<div class="page-header">
		<div class="wrap-title">
			<div class="icon">
				<i class="fa-solid fa-key"></i>
			</div>
			<h1>API Key Settings <small><span class="cgreen"><i class="fa-solid fa-circle" style="font-size: 8px;"></i> Active</span></small></h1>
		</div>
		<ul class="breadcrumb">
			<li><a href="<?php echo base_url(); ?>site">Home</a></li>
			<li class="active">API Keys</li>
		</ul>
		<div class="clear"></div>
	</div>

	<div class="row">	
		<div class="col-md-10 col-md-offset-1">
			<div class="block">
				<div class="head">                                
					<h2><i class="fa-solid fa-shield-halved"></i> Application Authentication Keys</h2>
					<ul class="buttons">
						<li>
							<button type="button" class="btn btn-primary" onclick="saveKey()">
								<i class="fa-solid fa-floppy-disk"></i> Save Key
							</button>
						</li>
					</ul>
				</div> 
				
				<div class="data-fluid">
					<form>
						<div class="row-form">
							<div class="col-sm-3">
								<label style="font-weight: 600; color: #334155;"><i class="fa-brands fa-android" style="color: #10b981; margin-right: 6px;"></i> Android Key:</label>
							</div>
							<div class="col-sm-6">
								<input class="form-control" type="text" placeholder="Android API Key" id="android_key" name="android_key" required value="<?php echo $apiKey['android_key']; ?>" />
							</div>
							<div class="col-sm-3" align="center">
								<button type="button" class="btn btn-sm btn-info" onclick="key_generate(1);">
									<i class="fa-solid fa-arrows-rotate"></i> Generate New Key
								</button>
							</div>
						</div>
						
						<div class="row-form">
							<div class="col-sm-3">
								<label style="font-weight: 600; color: #334155;"><i class="fa-brands fa-apple" style="color: #64748b; margin-right: 6px;"></i> iOS Key:</label>
							</div>
							<div class="col-sm-6">
								<input class="form-control" type="text" placeholder="iOS API Key" id="ios_key" name="ios_key" required value="<?php echo $apiKey['iOS_key']; ?>" />
							</div>
							<div class="col-sm-3" align="center">
								<button type="button" class="btn btn-sm btn-info" onclick="key_generate(2);">
									<i class="fa-solid fa-arrows-rotate"></i> Generate New Key
								</button>
							</div>
						</div>
					</form>
				</div>
				
				<div class="data-fluid" style="padding-top: 0;">
					<div class="row-form" style="border-top: none;">
						<p><b class="er-msg" style="color: #ef4444; font-weight: 600;"></b><b class="sc-msg" style="color: #10b981; font-weight: 600;"></b></p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script type='text/javascript' src='<?php echo base_url(); ?>assets/js/jquery.sortable.js'></script>

<script>
	function makeid() {
		var text = "";
		var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz01234567898^!~";
		for (var i = 0; i < 16; i++)
			text += possible.charAt(Math.floor(Math.random() * possible.length));
		return text;
	}

	function key_generate(id){
		$(".er-msg").html("");
		$(".sc-msg").html("");
		if(id == 1){
			$("#android_key").css({"border-color" : ""});
			$("#android_key").val(makeid());
		}else{
			$("#ios_key").css({"border-color" : ""});
			$("#ios_key").val(makeid());
		}
	}

	function saveKey() {
		$(".er-msg").html("");
		$(".sc-msg").html("");
		$("#android_key").css({"border-color" : ""});
		$("#ios_key").css({"border-color" : ""});
		if(!$("#android_key").val()) {
			$("#android_key").css({"border-color" : "#ef4444"});
		}else if(!$("#ios_key").val()){
			$("#ios_key").css({"border-color" : "#ef4444"});
		}else{
			var androidKey = $("#android_key").val();
			var iosKey = $("#ios_key").val();
			$.ajax({
				url : "<?php echo base_url(); ?>api_key/saveApi",
				type : "post",
				data : { androidKey : androidKey, iosKey : iosKey },
				success : function(result){
					if(result == "true"){
						$(".sc-msg").html('<i class="fa-solid fa-circle-check"></i> API Key saved successfully.');
					}else{
						$(".er-msg").html('<i class="fa-solid fa-circle-xmark"></i> Something went wrong. Please try again later.');
					}
				}
			});
		}
	}
</script>
