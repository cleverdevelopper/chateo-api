<?php
namespace App;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use App\Controller\Api;
use App\Http\Response;
use GuzzleHttp\Client; 


class Chat implements MessageComponentInterface {
    protected $clients;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
        echo 'Server Started';
    }

    /*public function onOpen(ConnectionInterface $conn) {
        // Store the new connection to send messages to later
        $this->clients->attach($conn);

        $querystring = $conn->httpRequest->getUri()->getQuery();
        parse_str($querystring, $queryarray);

        $user_token = $queryarray['token'];
        $user_id = $queryarray['id'];
        $user_connection_id = $conn->resourceId;

        echo "token! ({$user_token})\n";
        echo "user id! ({$user_id})\n";
        echo "New connection! ({$user_connection_id})\n";

        

        Api\Users::updateToken($user_id, $user_token, $user_connection_id);
        echo "New connection! ({$conn->resourceId})\n";
    }*/


    public function onOpen(ConnectionInterface $conn)
    {
        // Store the new connection
        $this->clients->attach($conn, $conn);
    
        // Extract query parameters from the connection URI
        $querystring = $conn->httpRequest->getUri()->getQuery();
        parse_str($querystring, $queryarray);
        
        $user_connection_id = $conn->resourceId;
    
         // Send the $user_connection_id to the client
        $welcomeMessage = [
            'type' => 'welcome',
            'connectionId' => $user_connection_id,
        ];
        $conn->send(json_encode($welcomeMessage));

        
            //Api\Users::updateToken($user_id, $user_token, $user_connection_id);
            echo "New connection! ($user_connection_id)\n";
    }


   public function onMessage(ConnectionInterface $from, $msg) {
        $numRecv = count($this->clients) - 1;
        echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
            , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');
        
        $data = json_decode($msg, true);
        print_r($data);
        foreach ($this->clients as $client) {
            if ($from !== $client) {
                // The sender is not the receiver, send to each client connected
                $client->send($msg);
            }
        }
    }


    public function onClose(ConnectionInterface $conn) {
        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);

        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }
}