<?php

/////////////////////tipo  de almacen//////////////////////////////////
$app->get('/almacen/tipo', function ($request,$response) {

    $sql="SELECT tipo_almacen.id as id, tipo_almacen.nombre as nombre, almacen.nombre as almacen, 
    tipo_almacen.status as status, almacen.id as idalmacen
     FROM tipo_almacen LEFT JOIN almacen ON tipo_almacen.almacen = almacen.id";

    try {
        $db=new DB();
        $conn= $db->connect();

        $stmt=$conn->query($sql);
        $tipoalmacen=$stmt->fetchAll(PDO::FETCH_OBJ);

        $db=null;
        $response->getBody()->write(json_encode($tipoalmacen));
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

$app->post('/almacen/tipo', function ($request,$response,array $args) {
    $params = (array)$request->getParsedBody();

    $id = $params['id'];
    $nombre = strtoupper($params['nombre']);
    $idalmacen = $params['idalmacen'];
    $estatus = 'ACTIVO'; 

    $sql1="SELECT * FROM almacen where UPPER(nombre) = UPPER('$nombre')";
    $db1=new DB();
    $conn1= $db1->connect();
    $stmt1=$conn1->query($sql1);
    $data1=$stmt1->fetchAll();

     if(!$data1){
        $sql="INSERT INTO tipo_almacen(nombre,almacen,status) VALUES (:nombre,:idalmacen,:estatus)";
        $db=new DB();
        $conn= $db->connect();

        $stmt=$conn->prepare($sql);
        $stmt->bindParam(':nombre',$nombre);
        $stmt->bindParam(':idalmacen',$idalmacen);
        $stmt->bindParam(':estatus',$estatus);
        $almacen=$stmt->execute();


        $response->getBody()->write(json_encode($almacen));
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

$app->put('/almacen/tipo/{id}', function ($request,$response,array $args) {
    $params = (array)$request->getParsedBody();

    $id = $args['id'];
    $nombre = strtoupper($params['nombre']);
    $estatus = strtoupper($params['status']);
    $idalmacen = $params['idalmacen'];
   
    //si el nombre q vas a registrar es el mismo que ya tenias antes esta bien actualiza, todo queda igual
    $caso1=" SELECT * FROM tipo_almacen WHERE UPPER(nombre) = UPPER('$nombre') and UPPER(nombre) =
    UPPER((SELECT nombre FROM tipo_almacen WHERE id = '$id'))";
    $db1=new DB();
    $conn1= $db1->connect();
    $stmt1=$conn1->query($caso1);
    $data1=$stmt1->fetchAll();
    
    //si el nombre de la ciudad no es igual al q tenias, entonces verifica si no existe uno igual, 
    //si es asi da error
    $caso2=" SELECT * FROM tipo_almacen WHERE UPPER(nombre) = UPPER('$nombre') and UPPER(nombre) !=
    UPPER((SELECT nombre FROM tipo_almacen WHERE id = '$id'))";
    $db2=new DB();
    $conn2= $db2->connect();
    $stmt2=$conn2->query($caso2);
    $data2=$stmt2->fetchAll();

     if($data1){
        $sql="UPDATE tipo_almacen SET nombre=:nombre,status=:estatus,almacen=:idalmacen WHERE id =:id";
        $db=new DB();
        $conn= $db->connect();

        $stmt=$conn->prepare($sql);
        $stmt->bindParam(':id',$id);
        $stmt->bindParam(':nombre',$nombre);
        $stmt->bindParam(':estatus',$estatus);
        $stmt->bindParam(':idalmacen',$idalmacen);
        $tipoalmacen=$stmt->execute();
        
        $response->getBody()->write(json_encode($tipoalmacen));
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
            $sql="UPDATE tipo_almacen SET nombre=:nombre,status=:estatus,almacen=:idalmacen WHERE id =:id";
            $db=new DB();
            $conn= $db->connect();
    
            $stmt=$conn->prepare($sql);
            $stmt->bindParam(':id',$id);
            $stmt->bindParam(':nombre',$nombre);
            $stmt->bindParam(':estatus',$estatus);
            $stmt->bindParam(':idalmacen',$idalmacen);
            $tipoalmacen=$stmt->execute();
    
            $response->getBody()->write(json_encode($tipoalmacen));
            return $response
                ->withHeader('content-type','application-json')
                ->withStatus(200);
        }
        
});

$app->get('/almacen/tipo/mostrar/{idalmacen}', function ($request,$response,array $args) {

    $id = $args['idalmacen'];

    $sql="SELECT * FROM tipo_almacen where almacen=$id"; 

    try {
        $db=new DB();
        $conn= $db->connect();

        $stmt=$conn->query($sql);
        $tipoalmacen=$stmt->fetchAll(PDO::FETCH_OBJ);

        $db=null;
        $response->getBody()->write(json_encode($tipoalmacen));
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

?>