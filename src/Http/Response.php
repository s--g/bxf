<?php declare(strict_types = 1);

namespace BxF\Http;

use BxF\Http\Response\Body;
use BxF\Http\Response\Code;
use BxF\PropertyAccess;

/**
 * @method Cookie[] getCookies()
 * @method $this setCookies(Cookie[] $value)
 *
 * @method array getHeaders()
 * @method $this setHeaders(array $value)
 *
 * @method Code getCode()
 * @method $this setCode(Code $value)
 *
 * @method ?Body getBody()
 * @method $this setBody(?Body $value)
 */
class Response
{
    use PropertyAccess;
    
    /**
     * @var Cookie[]
     */
    protected array $cookies;
    
    /**
     * @var string[]
     */
    protected array $headers;
    
    protected Code $code;
    
    protected ?Body $body;
    
    public function __construct()
    {
        $this->code = Code::OK;
        $this->cookies = [];
        $this->headers = [];
        $this->body = null;
    }
    
    public function addHeader(string $header): static
    {
        $this->headers[] = $header;
        return $this;
    }
    
    /**
     * Adds a cookie, replacing any with the same name
     *
     * @param Cookie $cookie
     * @return $this
     */
    public function addCookie(Cookie $cookie): static
    {
        $this->cookies[$cookie->getName()] = $cookie;
        return $this;
    }
    
    public function render(): void
    {
        http_response_code($this->code->value);
        
        $cookieDomain = reg()->getConfig()->get('cookie_domain');
        if($cookieDomain === null)
            $cookieDomain = $_SERVER['HTTP_HOST'];
        
        $frontEndDomain = reg()->getConfig()->get('front_end_domain');
        
        foreach($this->cookies as $cookie)
        {
            setcookie($cookie->getName(), $cookie->getValue(), [
                'expires' => time() + 60 * 60 * $cookie->getHours(),
                'path' => '/',
                'domain' => $cookieDomain,
                'secure' => $cookie->getSecure(),
                'httponly' => $cookie->getHttpOnly(),
                'samesite' => ($cookieDomain == $frontEndDomain)?'Strict':'None'
            ]);
        }
        
        if(!headers_sent())
        {
            foreach($this->headers as $header)
                header($header);
        }
        
        $this->body->render();
    }
}