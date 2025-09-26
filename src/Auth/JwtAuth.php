<?php declare(strict_types = 1);

namespace BxF\Auth;

use BxF\Application;
use BxF\JwtAuthConfig;
use BxF\Registry;
use BxF\Http\Request;
use BxF\Plugin\BootstrapPlugin;
use BxF\Plugin\PreRenderPlugin;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use User;

class JwtAuth
    implements BootstrapPlugin, PreRenderPlugin
{
    const string SIGNING_ALGORITHM = 'RS256';
    
    protected JwtAuthConfig $config;
    
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
            $decoded = JWT::decode($jwt, new Key($this->config->getSigningPublicKey(), self::SIGNING_ALGORITHM));
        }
        catch(\UnexpectedValueException $e)
        {
            return false;
        }
        
        if(!isset($decoded->userId))
            return false;
        
        $user = User::getById($decoded->userId);
        
        if(empty($user))
            return false;
        
        Registry::setUser($user);
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
        $this->authorize(Registry::getRequest());
        return true;
    }
    
    public function onPreRender(): bool
    {
        /**
         * @var User $user
         */
        $user = Registry::getUser();
        
        if(empty($user))
            return true;
        
        $jwt = JWT::encode([
                'userId' => $user->getId()
            ],
            $this->config->getSigningPrivateKey(),
            self::SIGNING_ALGORITHM
        );
        
        Registry::getApplication()->addResponseHeader('Authorization: bearer '.$jwt);
        return true;
    }
}