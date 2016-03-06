<link href='http://fonts.googleapis.com/css?family=Raleway:400,200' rel='stylesheet' type='text/css'>
<script type="text/javascript">
    $(document).ready(function(){
        $('#change').click(function(){
            var data = $('#changePass').serialize();
            $.ajax({
                type: 'post',
                url: '<?php echo base_url("welcome/changePassword"); ?>',
                data: data,
                success: function (data) {
                    $('#contenedor').html(data);
                    location.reload();
                }
            });
        });

        $('.borrar_reg').click(function(){
            var id = this.id;
            var data = $('#formDel-'+id).serialize();
            $.ajax({
                type: 'post',
                url: '<?php echo base_url("welcome/borrarReg"); ?>',
                data: data,
                success: function (data) {
                    $('#mensaje').html(data);
                    location.reload();
                }
            });
        });

        $('.close').click(function(){
            location.reload();
        });

        $('#captcha-contenedor').hide();

        var i = 0;
        $('#save').click(function(){
            var data = $('#formCifra').serialize();
            i = i + 1;

            if(i > 10){
                $('#captcha-contenedor').show();
                $('#save').attr('disabled',true);
            }else{
                $('#captcha-contenedor').hide();
                $('#save').attr('disabled',false);
            }
                $.ajax({
                    type: 'post',
                    url: '<?php echo base_url("welcome/capturarCifra"); ?>',
                    data: data,
                    success: function (data) {
                        $('#contenedor-modal').html(data);
                    }
                });
        });

           /* var explode = function(){
              alert("Boom!");
            };
            setTimeout(explode, 2000);*/


            $("#formCifra").clientSideCaptcha({
                input: "#captchaText", 
                display: "#captcha",
                pass : function() { 
                    alert("Passed!");
                    $('#captcha-contenedor').hide();
                    $('#save').attr('disabled',false);
                    i = 0;
                    return false; 
                },
                fail : function() { 
                    alert("Failed!"); return false; 
                }
            });    

    });
</script>
<div class="container">
   <div class="row login_box">
            <?php 
                if (@$user_profile): ?>
                <div class="col-md-12 col-xs-12" align="center">
                     <div class="line">
                        <h1>Bienvenido</h1>
                     </div>
                     <div class="outter">
                        <img data-src="holder.js/140x140" class="image-circle img-responsive" alt="140x140" src="https://graph.facebook.com/<?=$user_profile['id']?>/picture?type=large">
                    </div>
                     <h2><?=$user_profile['name']?></h2> 
                  </div>
                  <div class="col-md-12 col-xs-12 login_control">
                     <center>
                       <div class="col-lg-12">
                           <div class="form-group">
                            <a href="<?= $logout_url != ''? $logout_url: $other_log_out ?>" class="btn btn-primary btn-sm" role="button"><i class="fa fa-facebook-official icon"> </i> <span class="letra-boton"> Cerrar Sesión</span></a>
                           </div>
                       </div> 
                     </center>
                    <div id="contenedor">
                        <?php if(!empty($datos)){ ?>
                            <form id="changePass">
                                <?php for($i=0; $i< count($datos); $i++){
                                    if($datos[$i]->id == $user_profile['id']){
                                        if($datos[$i]->login_inicio == 0){
                                ?>  
                                        <input type="hidden" name="id" value="<?php echo $datos[$i]->id ?>">
                                         <div class="control">
                                            <div class="label">Password</div>
                                            <input type="password" name="password" class="form-control" value=""/>
                                         </div>
                                         <center>
                                             <div class="form-group">
                                                <button id="change" type="button" class="btn btn-danger">Cambiar Password</button>
                                             </div> 
                                         </center>
                                <?php 
                                        }
                                    }
                                }
                            ?>
                            </form>
                        <?php }else{ ?>
                        <br><br><hr>
                            <div class="contenedores">
                             <div class="panel panel-primary">
                                <div class="panel-heading">
                                   <span class="glyphicon glyphicon-list"></span>Registros Númericos
                                   <div class="pull-right action-buttons">
                                      <div class="btn-group pull-right">
                                         <button type="button" class="btn btn-default btn-xs" id="new">
                                            <span class="color glyphicon glyphicon-plus" data-toggle="modal" data-target="#squarespaceModal" style="margin-right: 0px;"></span>
                                         </button>
                                      </div>
                                   </div>
                                </div>
                                <div class="panel-body">
                                        <?php if(!empty($registros)){?>
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-striped table-hover table-condensed">
                                                        <thead>
                                                           <tr>
                                                                <th class="letra-inf">Cifra</th>
                                                                <th class="letra-inf">Fecha</th>
                                                                <th class="letra-inf">Acciones</th>
                                                           </tr>
                                                        </thead>
                                                        <tbody>
                                            <?php 
                                                $i=0;
                                                foreach($registros as $reg){ ?>
                                                            <tr>
                                                                <td class="letra-inf"><?php echo $reg->cifra;?></td>
                                                                <td class="letra-inf"><?php echo $reg->fecha;?></td>
                                                                <td>
                                                                    <div class="pull-right action-buttons">
                                                                        <form id="formDel-borrar_reg<?php echo $i;?>">
                                                                            <input type="hidden" name="identi" value="<?php echo $reg->id_cifra;?>">
                                                                            <button type="button" id="borrar_reg<?php echo $i;?>" class="btn btn-danger btn-xs borrar_reg"><i class="glyphicon glyphicon-trash"></i></button>
                                                                        </form>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                            <?php $i++; }?>

                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div id="mensaje"></div>
                                    </div>            
                                        <div class="panel-footer">
                                          <div class="row">
                                              <div class="col-md-6">
                                                 <span class="label2">Registros por página <?php echo count($registros)?></span>
                                              </div>
                                              <div class="col-md-6">
                                                <ul class="pagination pagination-sm pull-right">
                                                    <?php echo "<li>".$links."</li>"; ?>
                                                </ul>
                                                 
                                              </div>
                                           </div>
                                        </div>   
                                        <?php }else{?>
                                        <ul>
                                            <li class="list-group-item">
                                                <div class="label">No hay Registros</div>
                                          </li>
                                        </ul>                                          
                                        <?php } ?>
                                </div>
                                
                             </div>
                            </div>
                        <?php } ?>    
                    </div>    
                  </div>
            <?php else:?>
                   <div class="col-md-12 col-xs-12" align="center">
                     <div class="line">
                        <h1>Bienvenido</h1>
                     </div>
                     <div class="outter"><img src="http://www.rafelsanso.com/wp-content/uploads/2013/08/1380737239_user_male2.png" class="image-circle img-responsive"/></div>
                     <br><span class="letra-sub">Porfavor Inicia Sesión</span>
                  </div>
                  <div class="col-md-12 col-xs-12 login_control">
                     <center>
                       <div class="col-lg-6">
                           <div class="form-group">
                            <a href="<?= $login_url ?>" class="btn btn-lg btn-primary btn-lg" role="button"><i class="fa fa-facebook-official icon"> </i> <span class="letra-boton"> Iniciar Sesión</span></a>
                           </div>
                       </div> 
                       <div class="col-lg-6">
                           <div class="form-group">
                            <a href="<?= $register_url ?>" class="btn btn-lg btn-primary btn-lg" role="button"><i class="fa fa-facebook-official icon"> </i> <span class="letra-boton"> Registrar</span></a>
                           </div>
                       </div> 
                     </center>
                     <form id="loginForm" method="Post" action="<?php echo base_url("welcome/loginNormal"); ?>">
                        <div class="control">
                            <div class="label">Username</div>
                            <input type="text" name="usr" class="form-control" value=""/>
                         </div>
                         <div class="control">
                            <div class="label">Password</div>
                            <input type="password" name="pass" class="form-control" value=""/>
                         </div>
                         <div align="center">
                            <button type="submit" id="login" class="btn btn-orange">LOGIN</button>
                         </div> 
                     </form>
                  </div>
            <?php endif; ?>



        <div class="text-center">
            <div style="margin-top: 30px;">
                <script type="text/javascript">
                    (function() {
                        var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
                        po.src = 'https://apis.google.com/js/plusone.js';
                        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
                    })();
                </script>
            </div>
        </div>
    </div>
</div>


<!-- line modal -->
<div class="modal fade" id="squarespaceModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" style="color:#fff;" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
            <h3 class="modal-title" id="lineModalLabel">Ingresar Cifra</h3>
        </div>
        <div class="modal-body">
            <form method="post" id="formCifra">
                  <input type="hidden" name="usr" value="<?php echo $user_profile['id']?>"> 
                  <div class="form-group">
                    <input type="text" class="form-control" id="exampleInputEmail1" name="cifra" placeholder="Cifra">
                  </div>
                  <div id="captcha-contenedor">
                        <div class="form-group">
                            <div class="label">
                                Introduzca el Captcha porfavor:
                            </div>
                            <div class="col-lg-6">
                                <input type="text" class="form-control" id="captchaText" />
                                <p id="captcha"></p>
                            </div>
                            <div class="col-lg-6">
                                <button type="submit" id="captcha-solve" class="btn btn-success btn-sm pull-right"> Resolver Captcha</button>
                            </div>
                        </div>
                  </div>
                    
              <div id="contenedor-modal"></div>
        </div>
            <div class="modal-footer">
                <div class="btn-group btn-group-justified" role="group" aria-label="group button">
                    <div class="btn-group" role="group">
                        <button type="reset" class="btn btn-danger" role="button">Limpiar</button>
                    </div>
                    <div class="btn-group" role="group">
                        <button type="button" id="save" class="btn btn-default btn-success" data-action="save" role="button">Envíar</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
  </div>
</div>