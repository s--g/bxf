<?php declare(strict_types = 1);

namespace BxF\Auth;

use BxF\Application;
use BxF\JwtAuthConfig;
use BxF\Log\Item;
use BxF\Log\Priority;
use BxF\Registry;
use BxF\Http\Request;
use BxF\Plugin\BootstrapPlugin;
use BxF\Plugin\PreRenderPlugin;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Model\User;

class JwtAuth
    implements BootstrapPlugin, PreRenderPlugin
{
    const string SIGNING_ALGORITHM = 'RS256';
    
    protected JwtAuthConfig $config;
    
    protected \stdClass $payloadFromClient;
    
    public function __construct(JwtAuthConfig $config)
    {
        $this->config = $config;
    }
    
    /**
     * Retrieves the JWT from the Authorization header and sets the user in the registry
     *
     * @param Request $request
     * @return void
     */
    public function authorize(Request $request): bool
    {
        $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
        if(!$authHeader)
            return false;
        
        // Expected format: "Bearer <jwt>"
        if(!preg_match('/Bearer\s(\S+)/', $authHeader, $matches))
            return false;
        
        $jwt = $matches[1];
        
        try
        {
            $this->payloadFromClient = JWT::decode($jwt, new Key($this->config->getSigningPublicKey(), self::SIGNING_ALGORITHM));
        }
        catch(\UnexpectedValueException $ex)
        {
            reg()->getEventBus()->raiseEvent(
                Event::LogWrite,
                new Item($ex->getMessage, Priority::Error, $ex->getTrace())
            );
            
            return false;
        }
        
        /*
$this->payloadFromClient = new \stdClass();
$this->payloadFromClient->user_id = '519f3741-b43a-444d-b4c9-a0b20ab9b458';
$this->payloadFromClient->customer_id = '869d62cf-c2b6-4b20-bb9a-a45ceb672c1a';
        */

        if(!isset($this->payloadFromClient->user_id))
            return false;
        
        $user = User::getById($this->payloadFromClient->user_id);
        
        if(empty($user))
            return false;
        
        Registry::get()->setUser($user);
        return true;
    }
    
    /**
     * Takes a username and password and authenticates the user, responding with a JWT (in the body of the response).
     *
     * @param Request $request
     * @return void
     */
    public function authenticate(string $username, string $password)
    {
    
    }
    
    public function onBootstrap(Application $application): bool
    {
        $this->authorize(Registry::get()->getRequest());
        return true;
    }
    
    public function onPreRender(): bool
    {
        /**
         * @var Model\User $user
         */
        $user = Registry::get()->getUser();
        
        if(empty($user))
            return true;
        
        $jwt = JWT::encode([
                'user_id' => $user->getId(),
                'customer_id' => '869d62cf-c2b6-4b20-bb9a-a45ceb672c1a'
            ],
            $this->config->getSigningPrivateKey(),
            self::SIGNING_ALGORITHM
        );
        
        Registry::get()->getApplication()->addResponseHeader('Authorization: bearer '.$jwt);
        return true;
    }
}