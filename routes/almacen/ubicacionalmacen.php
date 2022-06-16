<?php

/////////////////////Ubicacion de almacen//////////////////////////////////
$app->get('/almacen/ubicacion', function ($request,$response) {

    $sql="SELECT ubic_almacen.Id as id, ubic_almacen.nombre as nombre,almacen.Id as idalmacen, almacen.nombre as almacen,tipo_almacen.Id as idtipoalmacen,
     tipo_almacen.nombre as tipoalmacen, ubic_almacen.status as status
    FROM (ubic_almacen LEFT JOIN almacen ON ubic_almacen.almacen = almacen.Id) LEFT JOIN tipo_almacen ON ubic_almacen.tipo_almacen = tipo_almacen.Id;";

    try {
        $db=new DB();
        $conn= $db->connect();

        $stmt=$conn->query($sql);
        $ubicacionalmacen=$stmt->fetchAll(PDO::FETCH_OBJ);

        $db=null;
        $response->getBody()->write(json_encode($ubicacionalmacen));
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

$app->post('/almacen/ubicacion', function ($request,$response,array $args) {
    $params = (array)$request->getParsedBody();

    $id = $params['id'];
    $nombre = strtoupper($params['nombre']);
    $idalmacen = $params['idalmacen'];
    $idtipoalmacen = $params['idtipoalmacen'];
    $estatus = 'ACTIVO'; 

    $sql1="SELECT * FROM ubic_almacen where UPPER(nombre) = UPPER('$nombre')";
    $db1=new DB();
    $conn1= $db1->connect();
    $stmt1=$conn1->query($sql1);
    $data1=$stmt1->fetchAll();

     if(!$data1){
        $sql="INSERT INTO ubic_almacen(nombre,almacen,tipo_almacen,status) VALUES (:nombre,:idalmacen,:idtipoalmacen,:estatus)";
        $db=new DB();
        $conn= $db->connect();

        $stmt=$conn->prepare($sql);
        $stmt->bindParam(':nombre',$nombre);
        $stmt->bindParam(':idalmacen',$idalmacen);
        $stmt->bindParam(':idtipoalmacen',$idtipoalmacen);
        $stmt->bindParam(':estatus',$estatus);
        $ubialmacen=$stmt->execute();


        $response->getBody()->write(json_encode($ubialmacen));
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
$app->put('/almacen/ubicacion/{id}', function ($request,$response,array $args) {
    $params = (array)$request->getParsedBody();

    $id = $args['id'];
    $nombre = strtoupper($params['nombre']);
    $idalmacen = $params['idalmacen'];
    $idtipoalmacen = $params['idtipoalmacen'];
    $estatus = strtoupper($params['status']);
   
    //si el nombre q vas a registrar es el mismo que ya tenias antes esta bien actualiza, todo queda igual
    $caso1=" SELECT * FROM ubic_almacen WHERE UPPER(nombre) = UPPER('$nombre') and UPPER(nombre) =
    UPPER((SELECT nombre FROM ubic_almacen WHERE id = '$id'))";
    $db1=new DB();
    $conn1= $db1->connect();
    $stmt1=$conn1->query($caso1);
    $data1=$stmt1->fetchAll();
    
    //si el nombre de la ciudad no es igual al q tenias, entonces verifica si no existe uno igual, 
    //si es asi da error
    $caso2=" SELECT * FROM ubic_almacen WHERE UPPER(nombre) = UPPER('$nombre') and UPPER(nombre) !=
    UPPER((SELECT nombre FROM ubic_almacen WHERE id = '$id'))";
    $db2=new DB();
    $conn2= $db2->connect();
    $stmt2=$conn2->query($caso2);
    $data2=$stmt2->fetchAll();

     if($data1){
        $sql="UPDATE ubic_almacen SET nombre=:nombre,status=:estatus,almacen=:idalmacen,tipo_almacen=:idtipoalmacen WHERE id =:id";
        $db=new DB();
        $conn= $db->connect();

        $stmt=$conn->prepare($sql);
        $stmt->bindParam(':id',$id);
        $stmt->bindParam(':nombre',$nombre);
        $stmt->bindParam(':estatus',$estatus);
        $stmt->bindParam(':idalmacen',$idalmacen);
        $stmt->bindParam(':idtipoalmacen',$idtipoalmacen);
        $ubialmacen=$stmt->execute();
        
        $response->getBody()->write(json_encode($ubialmacen));
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
            $sql="UPDATE ubic_almacen SET nombre=:nombre,status=:estatus,almacen=:idalmacen,tipo_almacen=:idtipoalmacen WHERE id =:id";
            $db=new DB();
            $conn= $db->connect();
    
            $stmt=$conn->prepare($sql);
            $stmt->bindParam(':id',$id);
            $stmt->bindParam(':nombre',$nombre);
            $stmt->bindParam(':estatus',$estatus);
            $stmt->bindParam(':idalmacen',$idalmacen);
            $stmt->bindParam(':idtipoalmacen',$idtipoalmacen);
            $ubialmacen=$stmt->execute();
    
            $response->getBody()->write(json_encode($ubialmacen));
            return $response
                ->withHeader('content-type','application-json')
                ->withStatus(200);
        }
        
});

?>