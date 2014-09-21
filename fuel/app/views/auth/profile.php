<div class="col-sm-12 col-md-12 col-lg-12">
    <div class="well well-light well-sm no-margin no-padding">
        <div class="row">
            <div class="col-sm-3 profile-pic" style="cursor: pointer">
                <input id="profile_picture" type="file" style="visibility: hidden;"><?php echo \Html::img($user_image, array('class' => 'select_picture')); ?>
            </div>
            <div class="col-sm-6">
                <h1>
                    <?php echo $first_name; ?> <span class="semi-bold"><?php echo $last_name; ?></span>
                </h1>
                <ul class="list-unstyled">
                    <li>
                        <p class="text-muted">
                            <i class="fa fa-envelope"></i>&nbsp;&nbsp;<?php echo Auth::get('email'); ?>
                        </p>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function()
    {
       $('.select_picture').click(function()
       {
          $('#profile_picture').click();
       });
      
        $("#profile_picture").change(function(){
            readURL(this);
            
            data= new FormData();
            data.append("image", this.files[0]);
             
            $.ajax({
                url: '<?php echo Uri::Create('profile/update_img'); ?>',
                type : 'post',
                data: data,
                processData: false,
                contentType: false
            });
        });
    });
    
    function readURL(input)
    {
        if (input.files && input.files[0]) 
        {
            var reader = new FileReader();

            reader.onload = function (e) 
            {
                $('.select_picture').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>