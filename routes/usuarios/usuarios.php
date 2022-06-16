<?php

/////////////////////////ciudades///////////////////////////////////////////////////////////////////////////////
$app->get('/usuarios', function ($request,$response) {

    $sql="SELECT usuarios.id as id, usuarios.usuario as nombre, usuarios.username as username,roles.nombre as rol,roles.id as idrol,
    usuarios.status as status, usuarios.caracteristicas as caracteristicas
     FROM usuarios LEFT JOIN roles ON usuarios.rol_id = roles.id;";

    try {
        $db=new DB();
        $conn= $db->connect();

        $stmt=$conn->query($sql);
        $ciudades=$stmt->fetchAll(PDO::FETCH_OBJ);

        $db=null;
        $response->getBody()->write(json_encode($ciudades));
        return $response
            ->withHeader('content-type','application-json')
            ->withStatus(200);

    } catch (PDOException $e) {
        $error=array(
            "message" =>$e->getMessage()
        );
        $response->getBody()->write(json_encode($error));
        return $response
        ->withHeader('content-type','application-json')
        ->withStatus(500);
    }
   
});
$app->post('/usuarios', function ($request,$response,array $args) {
    $params = (array)$request->getParsedBody();

    $usuario = strtoupper($params['nombre']);
    $username = strtoupper($params['username']);
    $pass = $params['pass'];
    $idrol= $params['idrol'];
    $caracteristicas = strtoupper($params['caracteristicas']);
    $status= "ACTIVO";

    $sql1="SELECT * FROM usuarios where UPPER(username) = UPPER('$username')";
    $db1=new DB();
    $conn1= $db1->connect();
    $stmt1=$conn1->query($sql1);
    $data1=$stmt1->fetchAll();

     if(!$data1){
        $sql="INSERT INTO usuarios(usuario,password,caracteristicas,username,status,rol_id) VALUES 
        (:usuario,:pass,:caracteristicas,:username,:status,:idrol)";
        $db=new DB();
        $conn= $db->connect();

        $stmt=$conn->prepare($sql);
        $stmt->bindParam(':usuario',$usuario);
        $stmt->bindParam(':pass',$pass);
        $stmt->bindParam(':caracteristicas',$caracteristicas);
        $stmt->bindParam(':username',$username);
        $stmt->bindParam(':status',$status);
        $stmt->bindParam(':idrol',$idrol);
        $usuarios=$stmt->execute();


        $response->getBody()->write(json_encode($usuarios));
        return $response
            ->withHeader('content-type','application-json')
            ->withStatus(200);

        } else {
            $data3[]=array("Result"=>"Error el nombre ya existe");
            $response->getBody()->write(json_encode($data3));
            return $response
                ->withHeader('content-type','application-json')
                ->withStatus(500);
        }
       
});

$app->put('/usuarios/{id}', function ($request,$response,array $args) {
    $params = (array)$request->getParsedBody();

    $id = $args['id'];
    $username = strtoupper($params['username']);
    $idrol= $params['idrol'];
    $estatus= strtoupper($params['status']);
    $caracteristicas = strtoupper($params['caracteristicas']);
    
   
    //si el nombre q vas a registrar es el mismo que ya tenias antes esta bien actualiza, todo queda igual
    $caso1=" SELECT * FROM usuarios WHERE UPPER(usuario) = UPPER('$username') and UPPER(usuario) =
    UPPER((SELECT usuario FROM usuarios WHERE id = '$id'))";
    $db1=new DB();
    $conn1= $db1->connect();
    $stmt1=$conn1->query($caso1);
    $data1=$stmt1->fetchAll();
    
    //si el nombre de la ciudad no es igual al q tenias, entonces verifica si no existe uno igual, 
    //si es asi da error
    $caso2=" SELECT * FROM usuarios WHERE UPPER(usuario) = UPPER('$username') and UPPER(usuario) !=
    UPPER((SELECT usuario FROM usuarios WHERE id = '$id'))";
    $db2=new DB();
    $conn2= $db2->connect();
    $stmt2=$conn2->query($caso2);
    $data2=$stmt2->fetchAll();

     if($data1){
        $sql="UPDATE usuarios SET username=:username,caracteristicas=:caracteristicas,status=:estatus,rol_id=:idrol WHERE id =:id";
        $db=new DB();
        $conn= $db->connect();

        $stmt=$conn->prepare($sql);
        $stmt->bindParam(':id',$id);
        $stmt->bindParam(':username',$username);
        $stmt->bindParam(':caracteristicas',$caracteristicas);
        $stmt->bindParam(':estatus',$estatus);
        $stmt->bindParam(':idrol',$idrol);
        $roles=$stmt->execute();
        
        $response->getBody()->write(json_encode($roles));
        return $response
            ->withHeader('content-type','application-json')
            ->withStatus(200);

    } elseif($data2) {
            $data3[]=array("Result"=>"Error el nombre ya existe");
            $response->getBody()->write(json_encode($data3));
            return $response
                ->withHeader('content-type','application-json')
                ->withStatus(500);

    } else {
            $sql="UPDATE usuarios SET username=:username,caracteristicas=:caracteristicas,status=:estatus,rol_id=:idrol WHERE id =:id";
            $db=new DB();
            $conn= $db->connect();
    
            $stmt=$conn->prepare($sql);
            $stmt->bindParam(':id',$id);
            $stmt->bindParam(':username',$username);
            $stmt->bindParam(':caracteristicas',$caracteristicas);
            $stmt->bindParam(':estatus',$estatus);
            $stmt->bindParam(':idrol',$idrol);
            $roles=$stmt->execute();
    
            $response->getBody()->write(json_encode($roles));
            return $response
                ->withHeader('content-type','application-json')
                ->withStatus(200);
        }
        
});

?>