   <?php if(isset($wh) && !empty($wh)){ ?>

     <?php 
     $getRole = $this->db->where('user_id',$this->session->userdata('poster_sess_user_id'))->get('login_user')->result_array();
     $roled = explode(",",$getRole[0]['user_role']);
     ?>
     <?php
     foreach($getRole as $allrole)
     { 
      foreach($roled as $usrrole)
      {
        if($usrrole=="UPDATE")
        {
          ?>
           <!--  <a href="<?php echo $value['path']."/".$value['data_id']; ?>" class="button green">
               <div class="icon"><span class="ico-pencil"></span></div>
             </a> -->
             <a href="<?php echo $value['path']."?edit=".$value['data_id']; ?>" class="button green">
              <div class="icon"><span class="ico-pencil"></span></div>
            </a>
            <?php 
          }
        }
        foreach($roled as $usrrole)
        {
         if($usrrole=="DELETE")
         {
          ?>
          <button class="delete button red" style="background: none;border: none;margin: 0;padding: 0;" id="<?php echo $value['data_id']; ?>" onclick="delete_poster(<?php echo $value['data_id'] ?>)"><div class="icon"><span class="ico-remove"></span></div></button> 
          <?php 
        }

      } 
    } 
    ?>

    <?php } else{ ?>

      <?php 
      $getRole = $this->db->where('user_id',$this->session->userdata('poster_sess_user_id'))->get('users')->result_array();
      $roled = explode(",",$getRole[0]['user_role']);
      ?>
      <?php
      foreach($getRole as $allrole)
      { 
        foreach($roled as $usrrole)
        {
          if($usrrole=="UPDATE")
          {
            ?>
            <button data-toggle="modal" data-target="<?php echo $value['modal'];?>" class="update button green" style="background: none;border: none;margin: 0;padding: 0;" id="<?php echo $value['data_id']; ?>"><div class="icon"><span class="ico-pencil"></span></div></button>
            <?php 
          }
        }
        foreach($roled as $usrrole)
        {
         if($usrrole=="DELETE")
         {
          ?>
          <button class="delete button red" style="background: none;border: none;margin: 0;padding: 0;" id="<?php echo $value['data_id']; ?>" onclick="delete_poster(<?php echo $value['data_id'] ?>)"><div class="icon"><span class="ico-remove"></span></div></button> 
          <?php 
        }

      } 
    } 
  }
  ?>

