
<style type="text/css">
.body .container .block .head.yellow{
padding: 4px 4px 0px 10px;
background-color: black !important;
}
</style>
<script type="text/javascript">
/*$(document).ready(function(){
$("#nameErMsg").hide();
$("#emailErMsg").hide();
$("#genderErMsg").hide();
$("#firstnameErMsg").hide();
$("#passwordErMsg").hide();
$("#assignMenuErMsg").hide();
$("#userRoleErMsg").hide();
$("#dialog-confirm").hide();
var nameError = false;
var emailError = false;
var genderError= false;
var firstnameError= false;
var passwordError= false;
var assignMenuError = false;
var userRoleError = false;
$('#savebtn').on('click', function()
{
user_name_validation();
firstname_validation();
var userTypeChk = [];
$.each($("input[name='user_type']:checked"), function(){
userTypeChk.push($(this).val());
});
userTypeChk= userTypeChk.toString();
if(userTypeChk==''){
userTypeError= true;
$("#userTypeErMsg").html("Please Select User Type !");
$('#userTypeErMsg').show(200);
}
else
{
userTypeError= false;
$('#userTypeErMsg').hide(200);
}

var userRole= [];
$.each($("input[name='userRole']:checked"),function(){
userRole.push($(this).val());
});
userRole= userRole.toString();
if(userRole==''){
userRoleError= true;
$("#userRoleErMsg").html("Please Select at least One User Role !");
$('#userRoleErMsg').show(200);
}
else{
userRoleError= false;
$("#userRoleErMsg").hide(200);
}
var assignMenu = [];
$.each($('option[name="opt"]:selected'),(function(){
assignMenu.push($(this).val());
}));
assignMenu = assignMenu.toString();
if(assignMenu ==''){
assignMenuError= true;
$("#assignMenuErMsg").html("Please Assign at least One menu to user");
$('#assignMenuErMsg').show(200);
}
else{
userRoleError= false;
$("#assignMenuErMsg").hide(200);
}
var userGenderChk = [];
$.each($("input[name='user_gender']:checked"), function(){
userGenderChk.push($(this).val());
});
userGenderChk= userGenderChk.toString();
if(userGenderChk==''){
genderError= true;
$("#genderErMsg").html("Please Select Gender");
$('#genderErMsg').show(200);
}
else
{
genderError= false;
$('#genderErMsg').hide(200);
}
/*var img = $("#user_img").val();
if(img == ''){
imgError= true;
$("#imgErMsg").html("Image is required !");
$('#imgErMsg').show(200);
}
else
{
var file_extension = img.split('.').pop().toLowerCase();

if(file_extension !='jpg' && file_extension !='jpeg' && file_extension !='png')
{
imgError= true;
$("#imgErMsg").html("Invalid File type ! Upload only(.jpg |.jpeg |.png)");
$('#imgErMsg').show(200);
}
else{
imgError= false;
$('#imgErMsg').hide(200);
}
}*/

/*users_passem_validation();

if(nameError !=true && emailError !=true && genderError !=true && firstnameError !=true && passwordError !=true && firstnameError !=true && userRoleError !=true && assignMenuError !=true){
var userName = $("#user_name").val();
var userMenu  = assignMenu;
var userRoled = userRole;
var userEmail = $("#user_email").val();
var userGender = userGenderChk;
var firstName = $("#first_name").val();
var lastName = $("#last_name").val();
var userPassword = $("#user_password").val();
var isDelete = $("#is_delete").val();*/
// var property = document.getElementById("model_img").files[0];

/* $.ajax({
url : '<?php base_url(); ?>updateuser',
type:'post',
data: {username:userName, assigned_menu:userMenu,first_name:firstName,last_name:lastName,email:userEmail,password:userPassword,gender:userGender,is_delete:isDelete,user_role:userRoled},
dataType:'json',
success:function(data){
alert(data);
window.location.href = "<?php echo base_url(); ?>";
},
error: function (result) {
alert('Oh no :(');
}
});*/
/*}
});
$("#user_name").on('keyup blur', function(){
user_name_validation();
});
// Only Alphabet ----Validation
$("#user_name").keydown(function(e)
{
var key = e.keyCode;
if ((key == 32)) {e.preventDefault();}
}) ;
$("#first_name,#last_name").keydown(function(e)
{
var key = e.keyCode;
if (!((key == 9) || (key == 8) ||  (key == 46) || (key >= 35 && key <= 40) || (key >= 65 && key <= 90))) {e.preventDefault();}
}) ;*/
/* $("#user_coin").keydown(function(e)
{
var key = e.keyCode;
if (key != 46 && key > 31 && (key < 48 || key > 57) && (key < 96 || key > 105 && key != 110) ){e.preventDefault();}
});
$("#model_diamond").keydown(function(e)
{
var key = e.keyCode;
if (key != 46 && key > 31 && (key < 48 || key > 57) && (key < 96 || key > 105 && key != 110) ){e.preventDefault();}
});*/
//--------------------------------------------
// No one can Copy Cut And Pase anything -- Validation
/*$('#user_name,#first_name,#last_name').bind("cut copy paste",function(e) {
e.preventDefault();//alert("you cant copy cut or paste here");
});
//--------------------------------------------

function user_name_validation()
{
var nameLength = $("#user_name").val().length;
if($("#user_name").val() =='')
{
nameError = true;
$("#nameErMsg").html("user Name is required !");
$('#nameErMsg').show(200);
}
else if(nameLength < 3 || nameLength > 25)
{
nameError = true;
$("#nameErMsg").html("Length must at least more than 3 character long");
$('#nameErMsg').show(200);
}
else{
nameError = false;
$('#nameErMsg').hide(200);
}
}
function firstname_validation()
{
var nameLength = $("#first_name").val().length;
if($("#first_name").val() =='')
{
firstnameError = true;
$("#firstnameErMsg").html("First Name is required !");
$('#firstnameErMsg').show(200);
}
else if(nameLength < 3 || nameLength > 25)
{
firstnameError = true;
$("#firstnameErMsg").html("Length must at least more than 3 character long");
$('#firstnameErMsg').show(200);
}
else
{
firstnameError = false;
$('#firstnameErMsg').hide(200);
}
}
$("#user_email").keyup(function(){
var pattern = new RegExp(/^[+a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i);
if(pattern.test($('#user_email').val())){
emailError=false;
$('#emailErMsg').hide(200);
}
else{
emailError= true;
$("#emailErMsg").html("Please enter Valid email format.");
$('#emailErMsg').show(200);
}
});
$("#user_password").keyup(function(){
var passPattern = new RegExp(/^(?=.*\d)(?=.*[!@#$%^&*])(?=.*[a-z])(?=.*[A-Z]).{8,}$/);
if(passPattern.test($('#user_password').val())){
passwprdError=false;
$('#passwordErMsg').hide(200);
}
else{
passwprdError= true;
$("#passwordErMsg").html("Password at least one lowercase, one uppercase, one Special character &amp; one digit minimum 8 Char Long");
$('#passwordErMsg').show(200);
}
});
function users_passem_validation()
{
var password = $("#user_password").val();
var email= $("#user_email").val();
var pattern = new RegExp(/^[+a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i);
var passPattern = /^(?=.*\d)(?=.*[!@#$%^&*])(?=.*[a-z])(?=.*[A-Z]).{8,}$/;
var passLength = password.length;
if(password =='')
{
passwprdError= true;
$("#passwordErMsg").html("Password is required");
$('#passwordErMsg').show(200);
}
else if(!passPattern.test($('#user_password').val()))
{
passwprdError= true;
$("#passwordErMsg").html("Password at least one lowercase, one uppercase char and least one digit minimum 8 Char Long");
$('#passwordErMsg').show(200);
}
else
{
passwprdError=false;
$('#passwordErMsg').hide(200);
}
if(email =='')
{
emailError= true;
$("#emailErMsg").html("Email is required");
$('#emailErMsg').show(200);
}
else if(pattern.test($('#user_email').val()))
{
emailError= false;
$('#emailErMsg').hide(200);
}
else
{
emailError= true;
$("#emailErMsg").html("Please enter Valid email format.");
$('#emailErMsg').show(200);
}
}
});*/
</script>
<div class="container">
    <div class="page-header">
        <div class="wrap-title">
            <div class="icon">
                <span class="ico-ok"></span>
            </div>
            <h1>ADD USERS <small>Add your Users</small></h1>
        </div>
        <ul class="breadcrumb">
            <li>
                <a href="index">Home</a>
            </li>
            <li class="active">Add Users</li>
        </ul>
        <div class="clear"></div>
    </div>
    <div class="row">
        <div class="col-md-8 col-sm-offset-1">
            <div class="block">
                <div id="error_text"><?php echo $this->session->flashdata('usrerr'); ?></div>
                <form method="POST" action="<?php echo base_url(); ?>user/insert">
                    <div class="data-fluid">
                        <div class="row-form">
                            <div class="col-md-2 text-right">Username:</div>
                            <div class="col-md-10">
                                <input type="text" class="form-control" name='user_name' value="" id="user_name" />
                                <span id="nameErMsg" class="bottom" style="color: red"></span>
                            </div>
                        </div>
                        
                        <div class="row-form">
                            <div class="col-md-2 text-right">Password:</div>
                            <div class="col-md-10">
                                <input type="password" class="form-control" name='user_password' id='user_password' value="" />
                                <span id="passwordErMsg" class="bottom" style="color: red"></span>
                            </div>
                        </div>
                        <div class="row-form">
                            <div class="col-md-2 text-right">Email:</div>
                            <div class="col-md-10">
                                <input type="email" class="form-control" name='user_email' id="user_email" value="" />
                                <span id="emailErMsg" class="bottom" style="color: red"></span>
                            </div>
                        </div>
                        <div class="row-form">
                            <div class="col-md-2 text-right"></div>
                            <div class="col-md-10">
                                <div class="block">
                                    <div class="head yellow" >
                                        <h2>Menu Assign</h2>
                                    </div>
                                    <select name="ms_example[]" multiple="multiple" id="msc">
                                        <?php
                                        $menu = $this->db->get('user_menu')->result_array();
                                        foreach ($menu as $menuItem)
                                        {
                                        ?>
                                        <option value="<?php echo $menuItem['menu_id']?>" name="opt"><?php echo $menuItem['menu_name'];?></option>
                                        <?php } ?>
                                    </select>
                                    <div class="btn-group">
                                        <button class="btn btn-mini" id="ms_select" type="button">Select all</button>
                                        <button class="btn btn-mini" id="ms_deselect" type="button">Deselect all</button>
                                    </div>
                                    <span id="assignMenuErMsg" class="bottom" style="color: red"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row-form">
                            <div class="col-md-2 text-right">User Role</div>
                            <div class="col-md-3">
                                
                                <input type="checkbox" value="INSERT" name="userRole[]" checked/>INSERT
                                <input type="checkbox" value="UPDATE" name="userRole[]" checked />UPDATE
                                <input type="checkbox" value="DELETE" name="userRole[]" checked />DELETE
                                <span id="userRoleErMsg" class="bottom" style="color: red"></span>
                            </div>
                        </div>
                        <div><input type="hidden" name="user_id" id="user_id" value=""></div>
                    </div>
                    <div class="toolbar bottom tar">
                        <div class="btn-group">
                            <button class="btn btn-danger" id="savebtn" type="submit" name="submit">Save</button>
                        </div>
                    </div>
                </form>
                
            </div>
        </div>
    </div>
</div>