<?php

/////////////////////Ubicacion de almacen//////////////////////////////////
$app->get('/articulos/subcategorias', function ($request,$response) {

    $sql="SELECT subcategorias.Id as id, subcategorias.nombre as nombre,categorias.Id as idcategoria, categorias.nombre as categoria,tipo_categorias.Id as idtipocategorias,
     tipo_categorias.nombre as tipocategorias, subcategorias.status as status
    FROM (subcategorias LEFT JOIN categorias ON subcategorias.categoria = categorias.Id) LEFT JOIN tipo_categorias ON subcategorias.tipo_categoria = tipo_categorias.Id;";

    try {
        $db=new DB();
        $conn= $db->connect();

        $stmt=$conn->query($sql);
        $subcategorias=$stmt->fetchAll(PDO::FETCH_OBJ);

        $db=null;
        $response->getBody()->write(json_encode($subcategorias));
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

$app->get('/articulos/subcategorias/', function ($request,$response) {

    $sql="SELECT subcategorias.Id as id, subcategorias.nombre as nombre,categorias.Id as idcategoria, categorias.nombre as categoria,tipo_categorias.Id as idtipocategorias,
     tipo_categorias.nombre as tipocategorias, subcategorias.status as status
    FROM (subcategorias LEFT JOIN categorias ON subcategorias.categoria = categorias.Id) LEFT JOIN tipo_categorias ON subcategorias.tipo_categoria = tipo_categorias.Id;";

    try {
        $db=new DB();
        $conn= $db->connect();

        $stmt=$conn->query($sql);
        $subcategorias=$stmt->fetchAll(PDO::FETCH_OBJ);

        $db=null;
        $response->getBody()->write(json_encode($subcategorias));
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

$app->post('/articulos/subcategorias', function ($request,$response,array $args) {
    $params = (array)$request->getParsedBody();

    $id = $params['id'];
    $nombre = strtoupper($params['nombre']);
    $idcategoria = $params['idcategoria'];
    $idtipocategorias = $params['idtipocategorias'];
    $estatus = 'ACTIVO'; 

    $sql1="SELECT * FROM subcategorias where UPPER(nombre) = UPPER('$nombre')";
    $db1=new DB();
    $conn1= $db1->connect();
    $stmt1=$conn1->query($sql1);
    $data1=$stmt1->fetchAll();

     if(!$data1){
        $sql="INSERT INTO subcategorias(nombre,categoria,tipo_categoria,status) VALUES (:nombre,:idcategoria,:idtipocategorias,:estatus)";
        $db=new DB();
        $conn= $db->connect();

        $stmt=$conn->prepare($sql);
        $stmt->bindParam(':nombre',$nombre);
        $stmt->bindParam(':idcategoria',$idcategoria);
        $stmt->bindParam(':idtipocategorias',$idtipocategorias);
        $stmt->bindParam(':estatus',$estatus);
        $subcategorias=$stmt->execute();


        $response->getBody()->write(json_encode($subcategorias));
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
$app->put('/articulos/subcategorias/{id}', function ($request,$response,array $args) {
    $params = (array)$request->getParsedBody();

    $id = $args['id'];
    $nombre = strtoupper($params['nombre']);
    $idcategoria = $params['idcategoria'];
    $idtipocategorias = $params['idtipocategorias'];
    $estatus = strtoupper($params['status']);
   
    //si el nombre q vas a registrar es el mismo que ya tenias antes esta bien actualiza, todo queda igual
    $caso1=" SELECT * FROM subcategorias WHERE UPPER(nombre) = UPPER('$nombre') and UPPER(nombre) =
    UPPER((SELECT nombre FROM subcategorias WHERE id = '$id'))";
    $db1=new DB();
    $conn1= $db1->connect();
    $stmt1=$conn1->query($caso1);
    $data1=$stmt1->fetchAll();
    
    //si el nombre de la ciudad no es igual al q tenias, entonces verifica si no existe uno igual, 
    //si es asi da error
    $caso2=" SELECT * FROM subcategorias WHERE UPPER(nombre) = UPPER('$nombre') and UPPER(nombre) !=
    UPPER((SELECT nombre FROM subcategorias WHERE id = '$id'))";
    $db2=new DB();
    $conn2= $db2->connect();
    $stmt2=$conn2->query($caso2);
    $data2=$stmt2->fetchAll();

     if($data1){
        $sql="UPDATE subcategorias SET nombre=:nombre,categoria=:idcategoria,tipo_categoria=:idtipocategorias,status=:estatus WHERE id =:id";
        $db=new DB();
        $conn= $db->connect();

        $stmt=$conn->prepare($sql);
        $stmt->bindParam(':id',$id);
        $stmt->bindParam(':nombre',$nombre);
        $stmt->bindParam(':estatus',$estatus);
        $stmt->bindParam(':idcategoria',$idcategoria);
        $stmt->bindParam(':idtipocategorias',$idtipocategorias);
        $subcategorias=$stmt->execute();
        
        $response->getBody()->write(json_encode($subcategorias));
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
            $sql="UPDATE subcategorias SET nombre=:nombre,categoria=:idcategoria,tipo_categoria=:idtipocategorias,status=:estatus WHERE id =:id";
            $db=new DB();
            $conn= $db->connect();
    
            $stmt=$conn->prepare($sql);
            $stmt->bindParam(':id',$id);
            $stmt->bindParam(':nombre',$nombre);
            $stmt->bindParam(':estatus',$estatus);
            $stmt->bindParam(':idcategoria',$idcategoria);
            $stmt->bindParam(':idtipocategorias',$idtipocategorias);
            $subcategorias=$stmt->execute();
    
            $response->getBody()->write(json_encode($subcategorias));
            return $response
                ->withHeader('content-type','application-json')
                ->withStatus(200);
        }
        
});

$app->get('/articulos/subcategorias/{idtipocategoria}', function ($request,$response,array $args) {

    $id = $args['idtipocategoria'];

    if($id !==""){
        $sql="SELECT * FROM subcategorias where tipo_categoria=$id"; 

    try {
        $db=new DB();
        $conn= $db->connect();

        $stmt=$conn->query($sql);
        $subcategorias=$stmt->fetchAll(PDO::FETCH_OBJ);

        $db=null;
        $response->getBody()->write(json_encode($subcategorias));
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
   
    }else{
        $data3[]=array("Result"=>"ha ocurrido un error");
            $response->getBody()->write(json_encode($data3));
            return $response
                ->withHeader('content-type','application-json')
                ->withStatus(500);
    }

   
});

?>