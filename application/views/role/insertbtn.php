<?php 
$getRole = $this->db->where('user_id',$this->session->userdata('poster_sess_user_id'))->get('login_user')->result_array();
$roled = explode(",",$getRole[0]['user_role']);
?>
<?php
foreach($getRole as $allrole)
{ 
    foreach($roled as $usrrole)
    {
        
        if($usrrole=="INSERT")
        {
            ?>
            <ul class="buttons">
                <li><button style="background: none;border: none;" onclick="document.location.href='<?php echo base_url($path)?>'"><div class="icon"><span class="ico-add"></span></div><h4 style="display: inline">&nbsp; <?=$title?></h4></button>
                </li>
            </ul>
            <?php 
        }
    }
} 
?>