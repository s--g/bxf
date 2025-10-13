<?php declare(strict_types = 1);

namespace BxF\Auth;

use BxF\Application;
use BxF\Http\Response\JsonBody;
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
        $authHeader = $_SERVER['HTTP_AUTHORIZATION']??'';
        if(!$authHeader)
            return false;
        
        // Expected format: "Bearer <jwt>"
        if(!preg_match('/Bearer\s(\S+)/', $authHeader, $matches))
            return false;
        
        $jwt = $matches[1];
        
        try
        {
            $this->payloadFromClient = JWT::decode(
                $jwt,
                new Key($this->config->getSigningPublicKey(), self::SIGNING_ALGORITHM)
            );
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
        
        reg()->setUser($user);
        return true;
    }
    
    /**
     * Provides an "authenticated" response with jwt in the body
     *
     * @param User $user
     * @return JsonBody
     */
    public function getAuthenticatedResponse(User $user): JsonBody
    {
        $jwt = JWT::encode([
                'user_id' => $user->getId()
            ],
            $this->config->getSigningPrivateKey(),
            self::SIGNING_ALGORITHM
        );
        
        return new JsonBody(
            'Authenticated', [
                'token' => $jwt
            ]
        );
    }
    
    public function onBootstrap(Application $application): bool
    {
        $this->authorize(Registry::get()->getRequest());
        reg()->setAuth($this);
        return true;
    }
    
    public function onPreRender(): bool
    {
        /**
         * @var Model\User $user
         
        $user = reg()->getUser();
        $customer = reg()->getCustomer();
        
        if(empty($user) || empty($customer))
            return true;
        
        $jwt = JWT::encode([
                'user_id' => $user->getId(),
                'customer_id' => $customer->getId()
            ],
            $this->config->getSigningPrivateKey(),
            self::SIGNING_ALGORITHM
        );
        
        reg()->getApplication()->getResponse->addHeader('Authorization: bearer '.$jwt);
         
         */
        
        return true;
    }
}