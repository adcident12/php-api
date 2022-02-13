<?php
    /**
     * @OA\Info(title="API Resume", version="1.0")
     * @OA\SecurityScheme(
     *  type="http",
     *  description=" Use /auth to get the JWT token",
     *  name="Authorization",
     *  in="header",
     *  scheme="bearer",
     *  bearerFormat="JWT",
     *  securityScheme="bearerAuth"
     * )
    */

    require $_SERVER['DOCUMENT_ROOT']. '/api/vendor/autoload.php';

    use \Firebase\JWT\JWT;
    use \Firebase\JWT\Key;

    Class Admin {
        private $conn;
        private $key = 'privatekey';
        private $db_table = 'admin';
        
        public function __construct($db) {
            $this->conn = $db;
        }

        /**
         * @OA\Get(path="/api/v1/auth", tags={"Admin"} ,
         * @OA\Response (response="200", description="Success"),
         * @OA\Response (response="404", description="Not Found")
         * )
        */
        public function auth() {
            $iat = time();
            $exp = $iat + 60 * 60;
            $payload = array(
                'iss' => 'http://localhost/api',
                'aud' => 'http://localhost/',
                'iat' => $iat,
                'exp' => $exp
            );
            $jwt = JWT::encode($payload, $this->key, 'HS256');
            return array(
                'token' => $jwt,
                'expires' => $exp
            );
        }

        /**
         * @OA\Get(path="/api/v1/admin/getAll", tags={"Admin"} ,
         * @OA\Response (response="200", description="Success"),
         * @OA\Response (response="404", description="Not Found"),
         * security={{"bearerAuth":{}}}
         * )
        */
        public function getAll() {
            $headers = apache_request_headers();
            if (isset($headers['Authorization'])) {
                $token = str_replace('Bearer ', '',$headers['Authorization']);
                try {
                    $token = JWT::decode($token, new Key($this->key, 'HS256'));
                    $query = "SELECT * FROM " .$this->db_table. " ORDER BY timestamp ASC";
                    $stmt = $this->conn->prepare($query);
                    $stmt->execute();
                    return $stmt;
                } catch (\Exception $e) {
                    return false;
                }
            } else {
                return false;
            }
        }

        /**
         * @OA\Post(path="/api/v1/admin/getByUsername", tags={"Admin"} ,
         * @OA\RequestBody(
         *  @OA\MediaType(
         *      mediaType="multipart/form-data", 
         *      @OA\Schema(required={"username"}, @OA\Property(property="username", type="string"))
         *  )
         * ),
         *  @OA\Response (response="200", description="Success"),
         *  @OA\Response (response="404", description="Not Found"),
         *  security={{"bearerAuth":{}}}
         * )
        */
        public function getByUsername($username) {
            $headers = apache_request_headers();
            if (isset($headers['Authorization'])) {
                $token = str_replace('Bearer ', '',$headers['Authorization']);
                try {
                    $token = JWT::decode($token, new Key($this->key, 'HS256'));
                    $query = "SELECT * FROM " .$this->db_table. " WHERE username= :username";
                    $stmt = $this->conn->prepare($query);
                    $stmt->bindParam(":username", $username);
                    $stmt->execute();
                    return $stmt;
                } catch (\Exception $e) {
                    return false;
                }
            } else {
                return false;
            }
        }

        /**
         * @OA\Post(path="/api/v1/admin/update", tags={"Admin"} ,
         * @OA\RequestBody(
         *  @OA\MediaType(
         *      mediaType="multipart/form-data", 
         *      @OA\Schema(required={"id"},
         *          @OA\Property(property="username", type="string"),
         *          @OA\Property(property="password", type="string"),
         *          @OA\Property(property="id", type="integer")
         *      )
         *  )
         * ),
         *  @OA\Response (response="200", description="Success"),
         *  @OA\Response (response="404", description="Not Found"),
         *  security={{"bearerAuth":{}}}
         * )
        */
        public function update() {
            $headers = apache_request_headers();
            if (isset($headers['Authorization'])) {
                $token = str_replace('Bearer ', '',$headers['Authorization']);
                try {
                    $token = JWT::decode($token, new Key($this->key, 'HS256'));
                    $query = "UPDATE " . $this->db_table . " SET username= :username, password= :password WHERE id= :id";
                    $stmt = $this->conn->prepare($query);
                    $stmt->bindParam(":username", $this->username);
                    $stmt->bindParam(":password", $this->password);
                    $stmt->bindParam(":id", $this->id);
                    if ($stmt->execute()) {
                        if ($stmt->rowCount()) {
                            return true;
                        } else {
                            return false;
                        } 
                    } else {
                        return false;
                    }
                } catch (\Exception $e) {
                    return false;
                }
            } else {
                return false;
            }
        }

        /**
         * @OA\Post(path="/api/v1/admin/insert", tags={"Admin"} ,
         * @OA\RequestBody(
         *  @OA\MediaType(
         *      mediaType="multipart/form-data", 
         *      @OA\Schema(required={"username", "password"},
         *          @OA\Property(property="username", type="string"),
         *          @OA\Property(property="password", type="string")
         *      )
         *  )
         * ),
         *  @OA\Response (response="200", description="Success"),
         *  @OA\Response (response="404", description="Not Found"),
         *  security={{"bearerAuth":{}}}
         * )
        */
        public function insert() {
            $headers = apache_request_headers();
            if (isset($headers['Authorization'])) {
                $token = str_replace('Bearer ', '',$headers['Authorization']);
                try {
                    $token = JWT::decode($token, new Key($this->key, 'HS256'));
                    $query = "INSERT INTO " . $this->db_table . " (username, password) VALUES (:username, :password)";
                    $stmt = $this->conn->prepare($query);
                    $stmt->bindParam(":username", $this->username);
                    $stmt->bindParam(":password", $this->password);
                    if ($stmt->execute()) {
                        if ($stmt->rowCount()) {
                            return true;
                        } else {
                            return false;
                        } 
                    } else {
                        return false;
                    }
                } catch (\Exception $e) {
                    return false;
                }
            } else {
                return false;
            }
        }

        /**
         * @OA\Post(path="/api/v1/admin/delete", tags={"Admin"} ,
         * @OA\RequestBody(
         *  @OA\MediaType(
         *      mediaType="multipart/form-data", 
         *      @OA\Schema(required={"id"},
         *          @OA\Property(property="id", type="integer")
         *      )
         *  )
         * ),
         *  @OA\Response (response="200", description="Success"),
         *  @OA\Response (response="404", description="Not Found"),
         *  security={{"bearerAuth":{}}}
         * )
        */
        public function delete() {
            $headers = apache_request_headers();
            if (isset($headers['Authorization'])) {
                $token = str_replace('Bearer ', '',$headers['Authorization']);
                try {
                    $token = JWT::decode($token, new Key($this->key, 'HS256'));
                    $query = "DELETE FROM " . $this->db_table . " WHERE id= :id";
                    $stmt = $this->conn->prepare($query);
                    $stmt->bindParam(":id", $this->id);
                    if ($stmt->execute()) {
                        if ($stmt->rowCount()) {
                            return true;
                        } else {
                            return false;
                        } 
                    } else {
                        return false;
                    }
                } catch (\Exception $e) {
                    return false;
                }
            } else {
                return false;
            }
        }
    }
?>