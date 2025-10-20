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
use BxF\Http\Cookie;
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
     * Retrieves the JWT from the session cookie and sets the user in the registry
     *
     * @param Request $request
     * @return bool
     */
    public function authorize(Request $request): bool
    {
        $jwt = reg()->getRequest()->getCookie('session');
        if(empty($jwt))
            return false;
        
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

        if(!isset($this->payloadFromClient->user_id))
            return false;
        
        $user = User::getById($this->payloadFromClient->user_id);
        
        if(empty($user))
            return false;
        
        reg()->setUser($user);
        return true;
    }
    
    public function updateSessionCookie(): void
    {
        $jwt = JWT::encode([
                'user_id' => reg()->getUser()?->getId()
            ],
            $this->config->getSigningPrivateKey(),
            self::SIGNING_ALGORITHM
        );
        
        $cookie = new Cookie('session', $jwt, 1, true, true);
        reg()->getApplication()->getResponse()->addCookie($cookie);
    }
    
    public function onBootstrap(Application $application): bool
    {
        $this->authorize(Registry::get()->getRequest());
        reg()->setAuth($this);
        return true;
    }
    
    public function onPreRender(): bool
    {
        $this->updateSessionCookie();
        return true;
    }
}