
    <main class="dashboard">
        
    <header>
            <h2>Administrar Mascotas</h2>
            <a href="<?=URL."/admin/sessionClose"?>" class="close"></a>
        </header>
       <a href="<?=URL."/admin/new"?>" class="add"></a>   
       <table>
           <tbody>

           <?php
           if(isset($data['mascotas'])){
                    foreach($data['mascotas'] as $value){
                        ?>
                        <tr>
                            <td>
                                <figure class="photo">
                                    <img src="<?=$value["photo"]?>" alt="">
                                </figure>
                                <div class="info">
                                    <h3><?= $value["name"]?></h3>
                                    <h4><?=$value["race"]?></h4>
                                </div>
                                <div class="controls">
                                    <a href="<?=URL."/admin/show/".$value["id"]?>" class="show"></a>
                                    <a href="<?=URL."/admin/edit/".$value["id"]?>" class="edit"></a>
                                    <form action="<?=URL."/admin/delete/".$value["id"]?>" method="post"><input type="hidden" name="photo" value="<?=$value["photo"]?>"><button class="delete"></button></form>
                                </div>
                            </td>
                        </tr>
                        <?php
                    }
                }
                ?>
           
           
       </tbody></table>
    
    </main>