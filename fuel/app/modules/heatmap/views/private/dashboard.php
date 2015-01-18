<table class="table table-striped">
    <thead>
        <th>User</th>
        <th>Url</th>
        <th>Vist Date</th>
        <th>View</th>
    </thead>
    <tbody>
        <?php
            foreach($users as $user)
            {                
                // make sure they have data
                $mongodb = \Mongo_Db::instance();
                $mongodb->where(array(
                    'user_id' => \Auth::get_user_id()[1],
                    'user' => $user['_id']
                ));
                
                $heatmap = $mongodb->get_one('heatmap');
                
                if(empty($heatmap) === false)
                {
                ?>
                    <tr>
                        <td><?php echo $user['_id']; ?></td>
                        <td><?php echo $user['url']; ?></td>
                        <td><?php echo \Date::forge($user['time']); ?></td>
                        <td><a href="<?php echo Uri::Create('heatmap/site/view/'.$user['_id']); ?>">View</a></td>
                    </tr>
                <?php   
                }
            }
        ?>
    </tbody>
</table>